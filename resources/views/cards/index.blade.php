@extends('layouts.app')
@section('title', 'Проактивная карта')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
@endsection

@section('content')
    <div class="custom-container">
        <div class="container-fluid mt-3 ">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    Проактивная карта №{{ $card->id }} {{ $card->name }}
                </div>
                <div class="card-body">
                    @if (count($card['lessons']) > 0)
                        <h5 class="card-title">Список уроков</h5>
                        @foreach ($card['lessons'] as $lesson)
                            <div class="d-flex flex-row m-3">
                                <div class="col-md-3">
                                    <p class="card-text">Название: {{ $lesson['topic'] }} </p>
                                </div>
                                <div class="col-md-8">
                                    <div class="btn-group">
                                        <a href="{{ route('lesson.show', [$card->id, $lesson->id]) }}"
                                            class="btn btn-outline-info  " alt="Просмотр"><i class="fa fa-eye"
                                                aria-hidden="true"></i></a>
                                        <a href="{{ route('lesson.generate', $lesson->id) }}"
                                            class="btn btn-outline-primary"><i class="fa fa-download"
                                                aria-hidden="true"></i></a>
                                        <a href="{{ route('lesson.edit', [$card, $lesson]) }}" class="btn btn-outline-warning "><i class="fa fa-pencil"
                                                aria-hidden="true"></i></a>
                                        <form action="{{ route('lesson.destroy', [$card->id, $lesson->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger m-0"
                                                onclick="return confirm('{{ __('Вы действительно хотите удалить план урока?') }}')"><i
                                                    class="fa fa-ban" aria-hidden="true"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <h5 class="card-title">Нет созданных уроков</h5>
                    @endif
                    <form action="{{ route('lesson.create', ['card' => $card]) }}" method="GET">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary">Создать план урока</button>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-3">
                <table class="table-responsive table-bordered table-sm">
                    <thead class="table-light text-center ">
                        <tr>
                            <th colspan="5">

                                <p>{{ $card['strategy']['name'] }} {{ $card['strategy']['description'] }}</p>
                                <p>Урок {{ $card->id }}: {{ $card->name }}</p>

                            </th>
                        </tr>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Каковы ключевые принципы реализации стратегии?</th>
                            <th scope="col">Какой метод необходимо применить?</th>
                            <th scope="col">В какой форме предоставить обратную связь?</th>
                            <th scope="col">Полезные вопросы на уроке</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($card['principles'] as $key => $principle)
                            <td>
                                <div class="form-check form-switch mx-4">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        name="checkbox{{ $key }}" id="checkbox{{ $key }}" value="true"
                                        checked>
                                </div>
                            </td>
                            <td class="col-3">
                                <p>{{ $principle->name }}</p>
                            </td>
                            <td class="col-3">
                                <div class="input-group">
                                    <select class="custom-select" id="mySelect{{ $key + 1 }}" name="selectedMethod[]">
                                        {{-- <option value="{{ $methods[$key]['id'] }}">
                                        {{ $methods[$key]['name'] }}
                                    </option>
                                    <option value="{{ $methods[count($methods) - 1]['id'] }}">
                                        {{ $methods[count($methods) - 1]['name'] }}</option> --}}
                                        <option value="{{ $card['method'][$key]['id'] }}">
                                            {{ $card['method'][$key]['name'] }}
                                        </option>
                                        @foreach ($personalMethods as $personalMethod)
                                            <option value="{{ $personalMethod->id }}">{{ $personalMethod->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-success" type="button"
                                            onclick="openModal({{ $key + 1 }})">Подробнее</button>
                                    </div>
                                </div>
                            </td>
                            <td class="col-3">
                                <div class="input-group">
                                    <select class="custom-select" id="myFeedback{{ $key + 1 }}"
                                        name="selectedFeedback[]">
                                        <option value="{{ $card['feedback'][$key]['id'] }}">
                                            {{ $card['feedback'][$key]['name'] }}</option>
                                        @foreach ($personalFeedbacks as $personalFeedback)
                                            <option value="{{ $personalFeedback->id }}">{{ $personalFeedback->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-success" type="button"
                                            onclick="openFeedbackModal({{ $key + 1 }})">Подробнее</button>
                                    </div>
                                </div>
                            </td>
                            <td class="col-3">
                                <p>{{ $card['questions'][$key]['name'] }}</p>
                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                    </form>
                </table>
                <div class="modal fade" id="myModalMethod" tabindex="-1" role="dialog" aria-labelledby="myModalMethod"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myModalMethod">Полная информация</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть"
                                    onclick="closeModal()">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p id="selectedOption"></p>
                                <p id="selectedTarget"></p>
                                <p id="selectedDescription"></p>
                                <p id="selectedRequiredResources"></p>
                                <p id="selectedRecommendedStage"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-primary">Создать свой метод</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                    onclick="closeModal()">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="myModalFeedback" tabindex="-1" role="dialog"
                    aria-labelledby="myModalFeedback" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myModalFeedback">Форма обратной связи</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть"
                                    onclick="closeFeedbackModal()">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p id="selectedFeedOption"></p>
                                <p id="selectedFeedDescription"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-primary">Создать свою обратную
                                    связь</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                    onclick="closeFeedbackModal()">Close</button>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    </div>
@endsection

@section('script')
    <script>
        var methodsData = @json($card['method']).concat(@json($personalMethods));
        var feedbackData = @json($card['feedback']).concat(@json($personalFeedbacks));

        function openModal(key) {
            // Получение элементов из HTML
            var select = document.getElementById("mySelect" + key);
            var selectedOption = document.getElementById("selectedOption");
            var selectedTarget = document.getElementById("selectedTarget");
            var selectedDescription = document.getElementById("selectedDescription");
            var selectedRequiredResources = document.getElementById("selectedRequiredResources");
            var selectedRecommendedStage = document.getElementById("selectedRecommendedStage");
            // Открытие модального окна

            var selectedElement = methodsData.find(function(element) {
                return element.id == select.value;
            });
            // Получение выбранного значения
            selectedOption.textContent = "Выбранная опция: " + selectedElement.name; // Вывод выбранной опции
            selectedTarget.textContent = "Цель: " + selectedElement.target; // Вывод выбранной опции
            selectedDescription.textContent = "Описание: " + selectedElement.description; // Вывод выбранной опции
            selectedRequiredResources.textContent = "Необходимые ресурсы: " + selectedElement
                .required_resources; // Вывод выбранной опции
            selectedRecommendedStage.textContent = "Рекомендуемый этап урока: " + selectedElement
                .recommended_stage; // Вывод выбранной опции

            $("#myModalMethod").modal("show"); // Отображение модального окна с помощью Bootstrap
        }

        function openFeedbackModal(key) {
            // Получение элементов из HTML
            var select = document.getElementById("myFeedback" + key);
            var selectedOption = document.getElementById("selectedFeedOption");
            var selectedFeedDescription = document.getElementById("selectedFeedDescription");
            // Открытие модального окна
            var selectedFeedElement = feedbackData.find(function(element) {
                return element.id == select.value;
            });
            console.log(selectedFeedElement);
            selectedOption.textContent = "Название: " + selectedFeedElement.name; // Вывод выбранной опции
            selectedFeedDescription.innerHTML = selectedFeedElement.description; // Вывод выбранной опции

            $("#myModalFeedback").modal("show"); // Отображение модального окна с помощью Bootstrap
        }

        function closeModal() {
            $("#myModalMethod").modal("hide");
        }

        function closeFeedbackModal() {
            $("#myModalFeedback").modal("hide");
        }
    </script>
    <script>
        function updateCheckboxState(id) {
            var checkbox = document.getElementById('checkbox' + id);
            if (checkbox) {
                checkbox.value = "on";
            } else {
                checkbox.value = "off";
            }
            console.log(checkbox.value);
        }
    </script>

@endsection
