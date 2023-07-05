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
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Row;
use PhpOffice\PhpWord\Element\Cell;
use PhpOffice\PhpWord\PhpWord;

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
        if ($request->isMethod('post')) {
        $data = $request->all();
        $card = Card::with('strategy', 'principles', 'questions')->find(request()->route('card'));

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

        return view('lessons.create', compact('card', 'sortedModels', 'sortedFeedback'));
        }
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
        $lesson->user_id = '1';
        $lesson->subject_name = 'ICT';
        $lesson->save();

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
        return view('lessons.show', compact('lesson'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function edit(Card $card, Lesson $lesson)
    {
        return view('lessons.edit', compact('card', 'lesson'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lesson $lesson)
    {
        //
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

        $templatePath = public_path('templates/document_template.docx');
        $outputPath = storage_path('app/public/generated_document.docx');


        // data for add template
        $data = [
            'topic' => $id->topic,
            'subject_name' => $id->subject_name,
            'planning_date' => $id->planning_date,
            'goal' => $id->goal,
            'evaluation_criteria' => $id->evaluation_criteria,
            'language_goals' => $id->language_goals ?? '',
            'instilling_values' => $id->instilling_values ?? '',
            'intersubject_communications' => $id->intersubject_communications ?? '',
            'prior_knowledge' => $id->prior_knowledge ?? '',
            'start_lesson_comments1' => $id->start_lesson_comments1 ?? '',
            'start_lesson_resource1' => $id->start_lesson_resource1 ?? '',
            'start_lesson_comments2' => $id->start_lesson_comments2 ?? '',
            'start_lesson_resource2' => $id->start_lesson_resource2 ?? '',
            'start_lesson_comments3' => $id->start_lesson_comments3 ?? '',
            'start_lesson_resource3' => $id->start_lesson_resource3 ?? '',
            'reflection' => $id->reflection ?? '',
        ];

        // adding methods array to data
        $i = 1;
        foreach ($id->card->method as $key => $method) {
            if ($id->card->method[$key]['id'] == '1') {
                continue;
            } else {
                $data['method' . $i] = $method->name;
                $data['methodTarget' . $i] = $method->target;
                $data['methodDescription' . $i] = $method->description;
                $data['methodRequired_resources' . $i] = $method->required_resources;
                $data['methodRecommended_stage' . $i] = $method->recommended_stage;
                $i++;
            }
        }

        foreach ($id->card->principles as $key => $principles) {
            $data['principle' . $key + 1] = $principles->name;
        }
        foreach ($id->card->questions as $key => $questions) {
            $data['question' . $key + 1] = $questions->name;
        }
        foreach ($id->card->feedback as $key => $feedback) {
            $data['feedback' . $key + 1] = $feedback->name;
        }

        // generate word document
        $generator = new WordGenerator($templatePath);
        $generator->generate($data, $outputPath);

        return response()->download($outputPath)->deleteFileAfterSend(true);
    }
}
