@extends('layouts.app')
@section('title', 'Проактивная карта')


@section('content')
    <div class="custom-container">
        <div class="container-fluid">
            <h1 class="mt-5">Полная информация о плане урока</h1>
            <p class="lead">{{ $card->strategy->name }} {{ $card->strategy->description }}</p>
            <br>
            </p>
            <div class="card">
                <div class="card-body">
                    <div class="row mt-3 mb-3">
                        <div class="col">
                            <dt class="col-sm-3">Учитель</dt>
                            <dd class="col-sm-9">Admin</dd>
                        </div>
                        <div class="col">
                            <dt class="col-sm-3">Предмет</dt>
                            <dd class="col-sm-9">{{ $lesson->subject }}</dd>
                        </div>
                    </div>
                    <div class="row  mt-3 mb-3">
                        <div class="col">
                            <dt class="col-sm-3">Тема урока</dt>
                            <dd class="col-sm-9">{{ $lesson->topic }}</dd>
                        </div>
                        <div class="col">
                            <dt class="col-sm-3">Цель урока</dt>
                            <dd class="col-sm-9">{{ $lesson->goal }}</dd>
                        </div>
                    </div>
                    <div class="row  mt-3 mb-3">
                        <div class="col">
                            <dt class="col-sm-3">Класс</dt>
                            <dd class="col-sm-9">{{ $lesson->class }}</dd>
                        </div>
                        <div class="col">
                            <dt class="col-sm-3">Литер</dt>
                            <dd class="col-sm-9">{{ $lesson->liter }}</dd>
                        </div>
                    </div>
                    <div class="row  mt-3 mb-3">
                        <div class="col">
                            <dt class="col-sm-3">Дата проведение</dt>
                            <dd class="col-sm-9">{{ $lesson->planning_date }}</dd>
                        </div>
                        <div class="col">
                            <dt class="col-sm-3">Критерии оценивания</dt>
                            <dd class="col-sm-9">{{ $lesson->evaluation_criteria }}</dd>
                        </div>
                    </div>

                    @foreach ($addibleInputs as $key => $row)
                        @if ($loop->iteration % 2 === 1)
                            <div class="row mt-3 mb-3">
                        @endif
                        <div class="col">
                            <dt class="col-sm-3">{{ $row['name'] }}</dt>
                            <dd class="col-sm-9">{{ $row['description'] }}</dd>
                        </div>
                        @if ($loop->iteration % 2 === 0 || $loop->last)
                </div>
                @endif
                @endforeach
            </div>
        </div>

        <div class="row">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <tbody>
                            <tr>
                                <th colspan="3" style="text-align: center">
                                    Ход урока
                                </th>
                            </tr>
                            <tr>
                                <th>Запланированные этапы урока </th>
                                <th>Запланированная деятельность на уроке</th>
                                <th class="col-2">Ресурсы</th>
                            </tr>
                            @php
                                $renderedStages = [];
                                $hasResource = [];
                            @endphp

                            @if ($lesson->first_lesson_editor || $lesson->first_lesson_resource)
                                <tr>
                                    <td>Начало урока</td>
                                    <td>
                                        <div class="form-control p-3 mb-3">{!! $lesson->first_lesson_editor !!}</div>
                                        <br><br>
                                    </td>
                                    <td>
                                        <div class="form-control p-3 mb-3">{{ $lesson->first_lesson_resource }}</div>
                                    </td>
                                </tr>
                            @endif


                            @foreach ($lesson->principles as $key => $principle)
                                @php
                                    $prevPrinciple = $card['principles'][$key - 1] ?? null;
                                @endphp
                                @if (
                                    $lesson->main_lesson_editor &&
                                        $principle['recommended_stage'] === 'Конец урока' &&
                                        (!$prevPrinciple || $prevPrinciple['recommended_stage'] !== 'Основная часть урока'))
                                    <tr>
                                        <td>Основная часть урока</td>
                                        <td>
                                            <div class="form-control p-3 mb-3">{!! $lesson->main_lesson_editor !!} </div>
                                            <br><br>
                                            </p>
                                        </td>
                                        <td>
                                            <div class="form-control p-3 mb-3">{!! $lesson->main_lesson_resource !!}</div>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>{{ $principle->recommended_stage }}</td>
                                    <td>
                                        <p><b>Принцип: </b> {{ $principle->name }}</p>
                                        <p><b>Метод: </b>{{ $lesson->methods->get($key)->name }} </p>
                                        <p><b>Форма обратной связи:</b> {{ $lesson->feedback->get($key)->name }}</p>
                                        @if ($lesson->{"lesson_editor$key"})
                                            <div class="form-control p-3 mb-3">{!! $lesson->{"lesson_editor$key"} !!}</div>
                                        @else
                                            <p>null</p>
                                        @endif
                                        <p><b>Полезные вопросы на уроке:</b> {{ $lesson->questions->get($key)->name }}
                                        </p>
                                    </td>
                                    <td>
                                        @if ($lesson->{"lesson_resource$key"})
                                            <div class="form-control p-3 mb-3">{!! $lesson->{"lesson_resource$key"} !!}</div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            @if ($lesson->last_lesson_editor || $lesson->last_lesson_resource)
                                <tr>
                                    <td>Конец урока</td>
                                    <td>
                                        <div class="form-control p-3 mb-3">{!! $lesson->last_lesson_editor !!}</div>
                                        <br><br>
                                        </p>
                                    </td>
                                    <td>
                                        <div class="form-control p-3 mb-3">{!! $lesson->last_lesson_resource !!}</textarea>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
