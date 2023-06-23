<?php

namespace App\Http\Controllers;

use App\Http\Requests\LessonStoreRequest;
use App\Models\Card;
use App\Models\Lesson;
use Illuminate\Http\Request;

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
    public function create()
    {
        $card = Card::with('strategy', 'principles' , 'questions', 'feedback', 'method')->find(request()->route('card'));
        return view('lessons.create', compact('card'));
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
    public function show(Lesson $lesson)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function edit(Lesson $lesson)
    {
        //
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
    public function destroy(Lesson $lesson)
    {
        //
    }
}
