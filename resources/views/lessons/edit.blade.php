@extends('layouts.app')
@section('title', 'Проактивная карта')

@section('style')
    <style>
    /* Style the header: fixed position (always stay at the top) */
    .header {
    position: fixed;
    top: 0;
    z-index: 1;
    width: 100%;
    background-color: #007bff;
    }

    /* The progress container (grey background) */
    .progress-container {
    width: 100%;
    height: 8px;
    background: #ccc;
    }

    /* The progress bar (scroll indicator) */
    .progress-bar {
    height: 8px;
    background: #04AA6D;
    width: 0%;
    }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="header">
        <p class="navbar-brand text-white">Проактивные карты</p>
        <div class="progress-container">
            <div class="progress-bar" id="myBar"></div>
        </div>
    </div>
    <div class="custom-container">
        <div class="container-fluid mt-3">
            <h2>Изменение урока</h2>
            <form action="{{ route('lesson.update', ['card' => $card, 'lesson' => $lesson]) }}" method="POST"
                enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <input type="hidden" id="card_id" name="card_id" value="{{ $card->id }}" />

                <div class="form-floating mb-3">
                    <select class="form-select" id="subject" name="subject">
                        <option selected>{{ $lesson->subject }}</option>
                        <option>Английский язык</option>
                        <option>Математика</option>
                        <option>Введение в науку</option>
                        <option>Познание мира</option>
                        <option>Искусство</option>
                        <option>Информатика</option>
                        <option>Казахский язык и литература Я1</option>
                        <option>Русский язык и литература Я1</option>
                        <option>Казахский язык Я2</option>
                        <option>Русский язык Я2</option>
                        <option>Естествознание</option>
                        <option>Всемирная история</option>
                        <option>История Казахстана</option>
                        <option>Физическая культура</option>
                        <option>Казахский язык Я1</option>
                        <option>Казахская литература</option>
                        <option>Руский язык Я1</option>
                        <option>Русская литература</option>
                        <option>Казахский язык и литература Я2</option>
                        <option>Русский язык и литература Я2</option>
                        <option>География</option>
                        <option>Биология</option>
                        <option>Физика</option>
                        <option>Химия</option>
                        <option>Человек. Общество. Право</option>
                        <option>Глабольные перспективы и проектные работы</option>
                        <option>Программирование</option>
                        <option>Графика и проектирование</option>
                        <option>Экономика</option>
                    </select>
                    <label for="subject">Предмет</label>
                    @error('subject')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-md">
                        <div class="form-floating mb-3">
                            <input type="date" id="planning_date" name="planning_date" class="form-control"
                                value="{{ old('planning_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}"
                                value="{{ $lesson->planning_date }}">
                            <label for="planning_date">Дата проведение:</label>
                            @error('planning_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="quarter" name="quarter" value="">
                                <option value="{{ $lesson->quarter }}">{{ $lesson->quarter }}</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                            <label for="quarter">Четверть</label>
                            @error('quarter')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="class" id="class"
                                value="{{ $lesson->class }}">
                            <label for="class">Класс</label>
                            @error('class')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="liter" id="liter"
                                value="{{ $lesson->liter }}">
                            <label for="liter">Литер</label>
                            @error('liter')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="topic" name="topic" value="{{ $lesson->topic }}">
                    <label for="topic">Тема урока</label>
                    @error('topic')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="goal" name="goal" value="{{ $lesson->goal }}">
                    <label for="goal">Цель урока</label>
                    @error('goal')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="evaluation_criteria" name="evaluation_criteria"
                        value="{{ $lesson->evaluation_criteria }}">
                    <label for="evaluation_criteria">Критерии оценивания</label>
                    @error('evaluation_criteria')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @foreach ($addibleInputs as $row)
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="{{ $row['name'] }}" name="{{ $row['inputName'] }}"
                            value="{{ $row['description'] }}">
                        <label for="{{ $row['name'] }}">{{ $row['name'] }}</label>
                    </div>
                @endforeach

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
                                                <textarea id="firstEditor">{!! $lesson->first_lesson_editor !!}</textarea>
                                                <br><br>
                                            </td>
                                            <td>
                                                <div class="form-control p-3 mb-3">{{ $lesson->first_lesson_resource }}
                                                </div>
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
                                                    <textarea>{!! $lesson->main_lesson_editor !!} </textarea>
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
                                                <p><b>Форма обратной связи:</b> {{ $lesson->feedback->get($key)->name }}
                                                </p>

                                                <textarea id="lessonEditor{{ $key }}">{!! $lesson->{"lesson_editor$key"} !!}</textarea>

                                                <p><b>Полезные вопросы на уроке:</b>
                                                    {{ $lesson->questions->get($key)->name }}
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
                                                <textarea>{!! $lesson->last_lesson_editor !!}</textarea>
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
        <button type="submit" class="btn btn-primary">Обновить</button>
        </form>
    </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script type="text/javascript">
        $('#firstEditor').summernote({
            height: 300,

        });
        $('#lessonEditor0').summernote({
            height: 300,

        });
        $('#lessonEditor1').summernote({
            height: 300,

        });
        $('#lessonEditor2').summernote({
            height: 300,

        });
        $('#lessonEditor3').summernote({
            height: 300,

        });
        $('#mainEditor').summernote({
            height: 300,

        });
        $('#lastEditor').summernote({
            height: 300,

        });
    </script>
    <script>
        // When the user scrolls the page, execute myFunction
        window.onscroll = function() {
            myFunction()
        };

        function myFunction() {
            var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            var scrolled = (winScroll / height) * 100;
            document.getElementById("myBar").style.width = scrolled + "%";
        }
    </script>
@endsection
