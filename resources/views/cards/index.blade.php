@extends('layouts.app')
@section('title', 'Проактивная карта')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@section('content')
    <div class="container-fluid mt-3 ">
        <div class="card">
            <div class="card-header">
                {{ $card->name }}
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $card->name }}</h5>
                @if (count($card['lessons']) > 0)
                    @foreach ($card['lessons'] as $lesson)
                        <div class="d-flex flex-row m-3">
                            <div class="col-md-3">
                                <p class="card-text">Название: {{ $lesson['topic'] }} </p>
                            </div>
                            <div class="col-md-8">
                                <div class="btn-group">
                                    <a href="{{ route('lesson.show', [$card->id, $lesson->id]) }}" class="btn btn-info"
                                        alt="Просмотр"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                    <a href="{{ route('lesson.generate', $lesson->id) }}" class="btn btn-primary"><i
                                            class="fa fa-download" aria-hidden="true"></i></a>
                                    <a href="#" class="btn btn-warning"><i class="fa fa-pencil"
                                            aria-hidden="true"></i></a>

                                    <form action="{{ route('lesson.destroy', [$card->id, $lesson->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"><i class="fa fa-ban"
                                                aria-hidden="true"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>Нет созданных уроков</p>
                @endif
                <form action="{{ route('lesson.create', ['card' => $card]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Создать план урока</button>
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="5">
                            <div class=" text-center">
                                <p>{{ $card['strategy']['name'] }} {{ $card['strategy']['description'] }}</p>
                                <p>Урок {{ $card->id }}: {{ $card->name }}</p>
                            </div>
                        </th>
                    </tr>
                    <tr class="text-center">
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
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked"
                                    name="checkbox{{ $key }}" checked>
                            </div>
                        </td>
                        <td class="col-3">
                            <p>{{ $principle->name }}</p>
                        </td>
                        <td class="col-3">
                            <div class="input-group">
                                <select class="custom-select" id="mySelect{{ $key + 1 }}" name="selectedMethod[]">
                                    <option value="{{ $methods[$key]['id'] }}">
                                        {{ $methods[$key]['name'] }}
                                    </option>
                                    <option value="{{ $methods[count($methods) - 1]['id'] }}">
                                        {{ $methods[count($methods) - 1]['name'] }}</option>
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-success" type="button"
                                        onclick="openModal({{ $key + 1 }})">Подробнее</button>
                                </div>
                            </div>
                        </td>
                        <td class="col-3">
                            <div class="input-group">
                                <select class="custom-select" id="myFeedback{{ $key + 1 }}" name="selectedFeedback[]">
                                    <option value="{{ $feedback[$key]['id'] }}">
                                        {{ $feedback[$key]['name'] }}
                                    </option>
                                    <option value="{{ $feedback[count($feedback) - 1]['id'] }}">
                                        {{ $feedback[count($feedback) - 1]['name'] }}</option>
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
            <div class="modal fade" id="myModalMethod" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Полная информация</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
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
                    </div>
                </div>
            </div>

            <div class="modal fade" id="myModalFeedback" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Форма обратной связи</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p id="selectedFeedOption"></p>
                            <p id="selectedFeedDescription"></p>
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
        var methodsData = @json($methods);
        var feedbackData = @json($feedback);

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
            console.log(select);
            // Получение выбранного значения
            selectedOption.textContent = "Выбранная опция: " + selectedElement.name; // Вывод выбранной опции
            selectedTarget.textContent = "Цель: " + selectedElement.target; // Вывод выбранной опции
            selectedDescription.textContent = "Описание: " + selectedElement.description; // Вывод выбранной опции
            selectedRequiredResources.textContent = "Необходимые ресурсы: " + selectedElement.required_resources; // Вывод выбранной опции
            selectedRecommendedStage.textContent = "Рекомендуемый этап урока: " + selectedElement.recommended_stage; // Вывод выбранной опции

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
    </script>

@endsection
