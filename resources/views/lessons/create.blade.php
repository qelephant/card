@extends('layouts.app')
@section('title', 'Проактивная карта')

@section('style')
    <style>
        .hidden {
            display: none;
        }

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
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
@endsection

@section('content')
    <div class="header">
        <p class="navbar-brand text-white">Проактивные карты</p>
        <div class="progress-container">
            <div class="progress-bar" id="myBar"></div>
        </div>
    </div>
    <div class="custom-container mb-3">
        <div class="container-fluid w-80 mt-3">
            <h2>Создание урока</h2>
            @if (session()->has('message'))
                <p class="alert alert-success">{{ session('message') }}</p>
            @endif

            <form action="{{ route('lesson.store', ['card' => $card->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="card_id" name="card_id" value="{{ $card->id }}" />
                {{-- <input type="hidden" name="sortedModels" value="{{$sortedModels}}">
            <input type="hidden" name="sortedFeedback" value="{{$sortedFeedback}}"> --}}
                <div class="form-floating mb-3">
                    <select class="form-select" id="subject" name="subject">
                        <option selected>Выберите</option>
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
                                value="{{ old('planning_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}">
                            <label for="planning_date">Дата проведение:</label>
                            @error('planning_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="quarter" name="quarter">
                                <option value="">Выберите...</option>
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
                                value="{{ old('class') }}">
                            <label for="class">Класс</label>
                            @error('class')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="liter" id="liter"
                                value="{{ old('liter') }}">
                            <label for="liter">Литер</label>
                            @error('liter')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="topic" name="topic" value="{{ old('topic') }}">
                    <label for="topic">Тема урока</label>
                    @error('topic')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="goal" name="goal" value="{{ old('goal') }}">
                    <label for="goal">Цель урока</label>
                    @error('goal')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="evaluation_criteria" name="evaluation_criteria"
                        value="{{ old('evaluation_criteria') }}">
                    <label for="evaluation_criteria">Критерии оценивания</label>
                    @error('evaluation_criteria')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div id="dynamicFields"></div>
                <div class="form-floating mb-3" id="selectFieldGroup">
                    <select class="form-select" id="selectField" onchange="toggleSelectFieldGroup()">
                        <option value="">Выберите...</option>
                        <option name="language_goals" value="Языковые цели">Языковые цели</option>
                        <option name="instilling_values" value="Привитие ценностей">Привитие ценностей</option>
                        <option name="intersubject_communications" value="Межпредметные связи">Межпредметные связи
                        </option>
                        <option name="prior_knowledge" value="Предварительные знания">Предварительные знания</option>
                    </select>
                    <label for="selectField">Выберите элемент:</label>
                    <button type="button" class="btn btn-primary mt-2" onclick="addField()">Добавить поле</button>
                </div>

                <div class="row">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th colspan="3" style="text-align: center">
                                            Ход урока
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Запланированные этапы урока </th>
                                        <th>Запланированная деятельность на уроке</th>
                                        <th>Ресурсы</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $renderedStages = [];
                                        $hasResource = [];
                                    @endphp

                                    {{-- общая проверка на больше 0 --}}
                                    @if ($card->principles->count() > 0)
                                        @if (
                                            ($card['principles']->get(0)['recommended_stage'] ?? '') !== 'Начало урока' &&
                                                ($card['principles']->get(1)['recommended_stage'] ?? '') !== 'Начало урока')
                                            <tr>
                                                <td>Начало урока</td>
                                                <td>
                                                    <textarea id="firstEditor" name="first_lesson_editor" class="form-control" placeholder="Введите комментарий">{{ old('first_editor') }}</textarea>
                                                    <br><br>
                                                    </p>
                                                </td>
                                                <td>
                                                    <textarea name="first_lesson_resource" class="form-control" rows="10" cols="70"
                                                        placeholder="Введите ресурсы">{{ old('first_lesson_resource') }}</textarea>
                                                </td>
                                            </tr>
                                        @endif
                                        @foreach ($card['principles'] as $key => $principle)
                                            @php
                                                $nextPrinciple = $card['principles'][$key + 1] ?? null;
                                                $prevPrinciple = $card['principles'][$key - 1] ?? null;
                                            @endphp
                                            @if (
                                                $principle['recommended_stage'] === 'Конец урока' &&
                                                    (!$prevPrinciple || $prevPrinciple['recommended_stage'] !== 'Основная часть урока'))
                                                <tr>
                                                    <td>Основная часть урока</td>
                                                    <td>
                                                        <textarea id="mainEditor" name="first_lesson_editor" class="form-control" placeholder="Введите комментарий"></textarea>
                                                        <br><br>
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <textarea name="first_lesson_resource" class="form-control" rows="10" cols="70"
                                                            placeholder="Введите ресурсы">{{ old('first_lesson_resource') }}</textarea>
                                                    </td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td>{{ $card['principles'][$key]['recommended_stage'] }}</td>
                                                <td>
                                                    <p><b>Принцип: </b> {{ $principle->name }}</p>
                                                    <input type="hidden" name="principles[]"
                                                        value="{{ $principle->id }}">
                                                    <p><b>Метод: </b> {{ $sortedModels[$key]['name'] }}</p>
                                                    <input type="hidden" name="sortedModel[]"
                                                        value="{{ $sortedModels['1']['id'] }}">
                                                    <p><b>Форма обратной связи:</b> {{ $sortedFeedback['1']['name'] }}</p>
                                                    <input type="hidden" name="sortedFeedback[]"
                                                        value="{{ $sortedFeedback[$key]['id'] }}">
                                                    @if (!in_array($card['principles'][$key]['recommended_stage'], $renderedStages))
                                                        <textarea id="lessonEditor{{ $key }}" name="lesson_editor{{ $key }}"
                                                            class="form-control lessonEditor{{ $key }}" placeholder="Введите комментарий"></textarea>
                                                        @php
                                                            $renderedStages[] = $card['principles'][$key]['recommended_stage'];
                                                            $hasResource[] = $key;
                                                        @endphp
                                                    @endif
                                                    <p><b>Полезные вопросы на уроке:</b>
                                                        {{ $card['questions'][$key]['name'] }}
                                                        <input type="hidden" name="questions[]"
                                                            value="{{ $card['questions'][$key]['id'] }}">
                                                    </p>
                                                </td>
                                                <td>
                                                    @if (in_array($key, $hasResource))
                                                        <textarea name={{ "lesson_resource$key" }} class="form-control" rows="10" cols="70"
                                                            placeholder="Введите ресурсы">{{ old("lesson_resource$key") }}</textarea>
                                                    @endif
                                                </td>
                                            </tr>
                                            {{-- @if ($principle['recommended_stage'] === 'Начало урока' && (!$nextPrinciple || $nextPrinciple['recommended_stage'] !== 'Основная часть урока'))
        <tr>
            <td>Основная часть урока</td>
            <td>
                <textarea id="mainEditor" name="main_lesson_editor" class="form-control" placeholder="Введите комментарий"></textarea>
                <br><br>
                </p>
            </td>
            <td>
                <textarea name="main_lesson_resource" class="form-control" rows="10" cols="70"
                    placeholder="Введите ресурсы">{{ old('main_lesson_resource') }}</textarea>
            </td>
        </tr>
    @endif --}}
                                        @endforeach

                                        @if ($card->principles->last()['recommended_stage'] !== 'Конец урока')
                                            <tr>
                                                <td>Конец урока</td>
                                                <td>
                                                    <textarea id="lastEditor" name="last_lesson_editor" class="form-control" placeholder="Введите комментарий">{{ old('last_editor') }}</textarea>
                                                    <br><br>
                                                    </p>
                                                </td>
                                                <td>
                                                    <textarea name="last_lesson_resource" class="form-control" rows="10" cols="70"
                                                        placeholder="Введите ресурсы">{{ old('last_lesson_resource') }}</textarea>
                                                </td>
                                            </tr>
                                        @endif
                                    @else
                                        <tr>
                                            <td>Начало урока</td>
                                            <td>
                                                <textarea id="firstEditor" name="first_lesson_editor" class="form-control" placeholder="Введите комментарий">{{ old('first_lesson_editor') }}</textarea>
                                                <br><br>
                                                </p>
                                            </td>
                                            <td>
                                                <textarea name="first_lesson_resource" class="form-control" rows="10" cols="70"
                                                    placeholder="Введите ресурсы">{{ old('first_lesson_resource') }}</textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Основная часть урока</td>
                                            <td>
                                                <textarea id="mainEditor" name="main_lesson_editor" class="form-control" placeholder="Введите комментарий">{{ old('main_lesson_editor') }}</textarea>
                                                <br><br>
                                                </p>
                                            </td>
                                            <td>
                                                <textarea name="first_lesson_resource" class="form-control" rows="10" cols="70"
                                                    placeholder="Введите ресурсы">{{ old('first_lesson_resource') }}</textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Конец урока</td>
                                            <td>
                                                <textarea id="lastEditor" name="last_lesson_editor" class="form-control" placeholder="Введите комментарий">{{ old('last_lesson_editor') }}</textarea>
                                                <br><br>
                                                </p>
                                            </td>
                                            <td>
                                                <textarea name="last_lesson_resource" class="form-control" rows="10" cols="70"
                                                    placeholder="Введите ресурсы">{{ old('last_lesson_resource') }}</textarea>
                                            </td>
                                        </tr>
                                    @endif

                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
@endsection

@section('script')
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

        function uploadImage(file, editor) {
            let formData = new FormData();
            formData.append('image', file);

            $.ajax({
                url: "route('lesson.store', ['card' => $card->id]",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    let imageUrl = response.url;
                    $(editor).summernote('insertImage', imageUrl);
                }
            });
        }
    </script>
    <script>
        function addField() {
            var selectElement = document.getElementById('selectField');
            var selectedValue = selectElement.value;

            if (selectedValue === '') {
                return;
            }

            var dynamicFieldsDiv = document.getElementById('dynamicFields');

            // Проверяем, есть ли уже поле с таким элементом
            var existingField = document.querySelector('[data-element="' + selectedValue + '"]');
            if (existingField) {
                // Если поле уже существует, сбрасываем выбор и выходим из функции
                selectElement.value = '';
                return;
            }

            var formGroup = document.createElement('div');
            formGroup.classList.add('form-group');

            var label = document.createElement('label');
            label.textContent = selectedValue;
            formGroup.appendChild(label);

            var inputField = document.createElement('input');
            inputField.setAttribute('type', 'text');
            inputField.setAttribute('name', selectedValue);
            inputField.classList.add('form-control');
            inputField.setAttribute('data-element', selectedValue);
            formGroup.appendChild(inputField);

            var deleteButton = document.createElement('button');
            deleteButton.setAttribute('type', 'button');
            deleteButton.classList.add('btn', 'btn-danger', 'mt-2');
            deleteButton.textContent = 'Удалить поле';
            deleteButton.addEventListener('click', function() {
                dynamicFieldsDiv.removeChild(formGroup);
                selectElement.appendChild(createOption(selectedValue, selectedValue));
                toggleSelectFieldGroup();
            });
            formGroup.appendChild(deleteButton);

            dynamicFieldsDiv.appendChild(formGroup);

            // Удаляем выбранный элемент из списка
            selectElement.removeChild(selectElement.options[selectElement.selectedIndex]);
            selectElement.value = '';

            toggleSelectFieldGroup();
        }

        function createOption(value, text) {
            var option = document.createElement('option');
            option.value = value;
            option.textContent = text;
            return option;
        }

        function toggleSelectFieldGroup() {
            var selectFieldGroup = document.getElementById('selectFieldGroup');
            var selectElement = document.getElementById('selectField');
            var addButton = document.querySelector('.btn-primary');

            if (selectElement.options.length <= 1) {
                selectFieldGroup.classList.add('hidden');
                addButton.classList.add('hidden');
            } else {
                selectFieldGroup.classList.remove('hidden');
                addButton.classList.remove('hidden');
            }
        }
    </script>
    {{-- <script src="https://cdn.ckeditor.com/ckeditor5/38.1.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor1'), {
                ckfinder: {
                    uploadUrl: '{{ route('lesson.upload')  . '?_token=' . csrf_token() }}',
                }
            })
            .catch(error => {

            });
    </script> --}}
    {{-- <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
//     <script>
//         CKEDITOR.replace( 'editor1', {
//             filebrowserUploadUrl: "{{ route('lesson.upload')  . '?_token=' . csrf_token() }}",
//             filebrowserUploadMethod: "form",
//         } );
// </script> --}}
    {{-- <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.4/summernote.css" rel="stylesheet">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.4/summernote.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
         $('#editor1').summernote({
               height: 300,
          });
       });
    </script> --}}
@endsection
