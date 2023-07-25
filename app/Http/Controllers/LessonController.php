<?php

namespace App\Http\Controllers;

use App\Http\Requests\LessonStoreRequest;
use App\Models\Card;
use App\Models\Feedback;
use App\Models\Lesson;
use App\Models\Method;
use Illuminate\Http\Request;
use App\Services\WordGenerator;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Row;
use PhpOffice\PhpWord\Element\Cell;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Reader\HTML;
use PhpOffice\PhpWord\TemplateProcessor;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $data = $request->all();
        $card = Card::with('strategy', 'principles', 'questions')->find(request()->route('card'));

        foreach ($card->principles as $key => $row) {
            if (!$request->has('checkbox' . +$key)) {
                // $card['principles'][$key]['select'] = 'not chosen';
                // $card['questions'][$key]['select'] = 'not chosen';
                // $card['principles'][$key] = null;
                // $card['questions'][$key] = null;
                unset($card['principles'][$key]);
                unset($card['questions'][$key]);
            }
        }
        $models = Method::whereIn('id', $data['selectedMethod'])->get();
        // Сортировка моделей в соответствии с порядком ключей
        $sortedModels = collect($data['selectedMethod'])->map(function ($key) use ($models) {
            return $models->where('id', $key);
        })->flatten();

        $feedback = Feedback::whereIn('id', $data['selectedFeedback'])->get();
        // Сортировка моделей в соответствии с порядком ключей
        $sortedFeedback = collect($data['selectedFeedback'])->map(function ($key) use ($feedback) {
            return $feedback->where('id', $key);
        })->flatten();

        $userArray = $card->principles->toArray();
        $idWithNoStage = ['1', '3', '12'];
        // Define the recommended stages in the desired sequence
        $recommendedStages = ['Начало урока', 'Основная часть урока', 'Конец урока'];
        if (in_array($card->id, $idWithNoStage)) {
            $recommendedStages = ['-', 'Начало урока', 'Основная часть урока', 'Конец урока'];
        }

        // Create an associative array to keep track of the elements by their recommended stage
        $stagesArray = [];

        // Loop through the user array to group elements by their recommended stage
        foreach ($userArray as $element) {
            $stage = $element['recommended_stage'] ?? null;
            if ($stage) {
                $stagesArray[$stage][] = $element;
            }
        }

        // Create a new array to store the sorted elements
        $sortedUserArray = [];

        // Check if each recommended stage exists and add elements accordingly
        foreach ($recommendedStages as $stage) {
            if (isset($stagesArray[$stage])) {
                // If the stage exists, add the elements to the sorted array
                $sortedUserArray = array_merge($sortedUserArray, $stagesArray[$stage]);
            } else {
                // If the stage is missing, add a new element with the missing stage
                $sortedUserArray[] = ['recommended_stage' => $stage, 'state' => 'alone'];
            }
        }

        return view('lessons.create', compact('data', 'card', 'sortedModels', 'sortedFeedback', 'sortedUserArray'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LessonStoreRequest $request)
    {

        $lesson = new Lesson();
        $lesson->fill($request->all());
        $lesson->language_goals = $request->input('Языковые_цели');
        $lesson->instilling_values = $request->input('Привитие_ценностей');
        $lesson->intersubject_communications = $request->input('Межпредметные_связи');
        $lesson->prior_knowledge = $request->input('Предварительные_знания');
        // $methods = Method::whereIn('id', $request->input('sortedModel'))->get();
        // $feedbacks = Feedback::whereIn('id', $request->input('sortedFeedback'))->get();
        $lesson->user_id = '1';
        $lesson->save();
        $lesson->principles()->attach($request->input('principles'));
        $lesson->questions()->attach($request->input('questions'));
        $lesson->methods()->attach($request->input('sortedFeedback'));
        $lesson->feedback()->attach($request->input('sortedFeedback'));

        // foreach($request->input('sortedModel') as $key => $value) {
        //     $lesson->methods()->attach($value);
        // }
        // foreach($request->input('sortedFeedback') as $key => $value) {
        //     $lesson->feedback()->attach($value);
        // }

        return redirect()->route('card.index', ['id' => $request->input('card_id')])->with('success', 'Урок успешно создан');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function show(Card $card, Lesson $lesson)
    {
        $lesson = $lesson->load('feedback', 'card', 'methods', 'principles', 'questions');
        $card = $card->load('strategy');
        $addibleInputs = [];
        if (!is_null($lesson->language_goals)) {
            $addibleInputs[] = ['name'=> 'Языковые цели', 'description' => $lesson->language_goals];
        }

        if (!is_null($lesson->instilling_values)) {
            $addibleInputs[] = ['name'=>'Межпредметные связи', 'description' => $lesson->instilling_values];
        }

        if (!is_null($lesson->intersubject_communications)) {
            $addibleInputs[] = ['name'=>'Предварительные знания', 'description'=>$lesson->intersubject_communications];
        }

        if (!is_null($lesson->prior_knowledge)) {
            $addibleInputs[] = ['name' => 'Предварительные знания', 'description'=>$lesson->prior_knowledge];
        }

        return view('lessons.show', compact('lesson', 'card', 'addibleInputs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function edit(Card $card, Lesson $lesson)
    {
        $lesson = $lesson->load('feedback', 'card', 'methods', 'principles', 'questions');
        $card = $card->load('strategy');
        $addibleInputs = [];
        if (!is_null($lesson->language_goals)) {
            $addibleInputs[] = ['name'=> 'Языковые цели', 'inputName' => 'language_goals', 'description' => $lesson->language_goals];
        }

        if (!is_null($lesson->instilling_values)) {
            $addibleInputs[] = ['name'=>'Межпредметные связи', 'inputName' => 'instilling_values', 'description' => $lesson->instilling_values];
        }

        if (!is_null($lesson->intersubject_communications)) {
            $addibleInputs[] = ['name'=>'Предварительные знания', 'inputName' => 'intersubject_communications', 'description'=>$lesson->intersubject_communications];
        }

        if (!is_null($lesson->prior_knowledge)) {
            $addibleInputs[] = ['name' => 'Предварительные знания', 'inputName' => 'prior_knowledge', 'description'=>$lesson->prior_knowledge];
        }

        return view('lessons.edit', compact('card', 'lesson', 'addibleInputs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function update(LessonStoreRequest $request, Card $card, Lesson $lesson)
    {
        $lesson->update($request->all());
        return redirect()->route('card.index', ['id' => $request->input('card_id')])->with('success', 'Урок успешно обновлен');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function destroy(Card $card, Lesson $lesson)
    {
        $lesson->delete();
        return response()->redirectToRoute('card.index', ['id' => $card->id])->with('success', 'Урок успешно удален');
    }

    public function generateDocument(Lesson $id)
    {

        $templatePath = public_path('templates/updated_document_template.docx');
        $outputPath = storage_path('app/public/generated_document.docx');
        $data = $id->toArray();
        $lesson = $id->load('feedback', 'card', 'methods', 'principles', 'questions', 'user');

        $data += ['author' => $id->user->name];
        $data += ['strategy_name' => $id->card->strategy->name];
        $data += ['strategy_id' => $id->card->strategy->id];
        $data += ['card_name' => $id->card->name];
        $data += ['card_id' => $id->card->id];
        // // data for add template
        // $data = [
        //     'topic' => $id->topic,
        //     'subject_name' => $id->subject_name,
        //     'planning_date' => $id->planning_date,
        //     'goal' => $id->goal,
        //     'evaluation_criteria' => $id->evaluation_criteria,
        //     'language_goals' => $id->language_goals ?? '',
        //     'instilling_values' => $id->instilling_values ?? '',
        //     'intersubject_communications' => $id->intersubject_communications ?? '',
        //     'prior_knowledge' => $id->prior_knowledge ?? '',
        //     'start_lesson_comments1' => $id->start_lesson_comments1 ?? '',
        //     'start_lesson_resource1' => $id->start_lesson_resource1 ?? '',
        //     'start_lesson_comments2' => $id->start_lesson_comments2 ?? '',
        //     'start_lesson_resource2' => $id->start_lesson_resource2 ?? '',
        //     'start_lesson_comments3' => $id->start_lesson_comments3 ?? '',
        //     'start_lesson_resource3' => $id->start_lesson_resource3 ?? '',
        //     'reflection' => $id->reflection ?? '',
        // ];

        $addibleInputs = [];
        if (!is_null($id->language_goals)) {
            $addibleInputs[] = ['name'=> 'Языковые цели', 'description' => $id->language_goals];
        }

        if (!is_null($id->instilling_values)) {
            $addibleInputs[] = ['name'=>'Межпредметные связи', 'description' => $id->instilling_values];
        }

        if (!is_null($id->intersubject_communications)) {
            $addibleInputs[] = ['name'=>'Предварительные знания', 'description'=>$id->intersubject_communications];
        }

        if (!is_null($id->prior_knowledge)) {
            $addibleInputs[] = ['name' => 'Предварительные знания', 'description'=>$id->prior_knowledge];
        }
     //   dd($addibleInputs);


        $dataWithRelations = [];
        for ($i = 0; $i < count($lesson->feedback); $i++) {
            $dataWithRelations[$i] = [
                'feedbackname' => $lesson->feedback[$i]->name,
                'feedbackdescription' => $lesson->feedback[$i]->description,
                'methodsname' => $lesson->methods[$i]->name,
                'methodsdescription' => $lesson->methods[$i]->description,
                'principlesname' => $lesson->principles[$i]->name,
                'principlesdescription' => $lesson->principles[$i]->description,
                'principlesrecommended_stage' => $lesson->principles[$i]->recommended_stage,
                'questionsname' => $lesson->questions[$i]->name,
                'questionsdescription' => $lesson->questions[$i]->description,
                'lessonresource' => $data["lesson_resource$i"]
            ];
        };
        dd($data['lesson_editor0']);
        //dd($dataWithRelations);
        // // adding methods array to data
        // $i = 1;
        // foreach ($id->card->method as $key => $method) {
        //     if ($id->card->method[$key]['id'] == '1') {
        //         continue;
        //     } else {
        //         $data['method' . $i] = $method->name;
        //         $data['methodTarget' . $i] = $method->target;
        //         $data['methodDescription' . $i] = $method->description;
        //         $data['methodRequired_resources' . $i] = $method->required_resources;
        //         $data['methodRecommended_stage' . $i] = $method->recommended_stage;
        //         $i++;
        //     }
        // }

        // foreach ($id->card->principles as $key => $principles) {
        //     $data['principle' . $key + 1] = $principles->name;
        // }
        // foreach ($id->card->questions as $key => $questions) {
        //     $data['question' . $key + 1] = $questions->name;
        // }
        // foreach ($id->card->feedback as $key => $feedback) {
        //     $data['feedback' . $key + 1] = $feedback->name;
        // }


        // // generate word document
        // //$generator = new WordGenerator($templatePath);
        // //$generator->generate($data, $outputPath);
        $templateProcessor = new TemplateProcessor($templatePath);
        $templateProcessor->setValues($data);
        $templateProcessor->cloneRowAndSetValues('principlesrecommended_stage', $dataWithRelations);
        $templateProcessor->cloneRowAndSetValues('name', $addibleInputs);

        //$templateProcessor->setValue('comment', $html);
        $templateProcessor->saveAs($outputPath);
        return response()->download($outputPath)->deleteFileAfterSend(true);
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            $request->file('upload')->move(public_path('media'), $fileName);

            $url = asset('media/' . $fileName);

            return response()->json(['fileName' => $fileName, 'uploaded' => 1, 'url' => $url]);
        }
        // if ($request->hasFile('upload')) {
        //     $originName = $request->file('upload')->getClientOriginalName();
        //     $fileName = pathinfo($originName, PATHINFO_FILENAME);
        //     $extension = $request->file('upload')->getClientOriginalExtension();
        //     $fileName = $fileName . '_' . time() . '.' . $extension;

        //     $request->file('upload')->move(public_path('media'), $fileName);

        //     $CKEditorFuncNum = $request->input('CKEditorFuncNum');

        //     $url = asset('media/' . $fileName);
        //     $msg = 'image upload successfully!';

        //     $response = "<script> window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

        //     @header('Content-type: text/html; charset=utf-8');

        //     echo $response;
        // }
        // }
    }
}
