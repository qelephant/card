@extends('layouts.app')
@section('title', 'Проактивная карта')

@section('content')
    <div class="container">
        <p>Тема урока: {{ $lesson->topic }}</p>
        <p>Цель урока: {{ $lesson->goal }}</p>
        <p>Предмет: {{ $lesson->subject_name }}</p>
        <p>Дата проведение урока: {{ $lesson->planning_date }}</p>
        <p>{{ $lesson->evaluation_criteria }}</p>
        <p>{{ $lesson->language_goals }}</p>
    </div>
@endsection
