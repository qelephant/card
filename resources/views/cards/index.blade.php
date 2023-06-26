@extends('layouts.app')
@section('title', 'Проактивная карта')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@section('content')
    <div class="container-fluid mt-3">
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

                <a href="{{ route('lesson.create', ['card' => $card->id]) }}" class="btn btn-primary">Создать план урока</a>
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <div class="table-responsive">
                <table class="table table-bordered mb-0" style="color: black">
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
                            <th>Каковы ключевые принципы реализации стратегии?</th>
                            <th>Какой метод необходимо применить?</th>
                            <th>В какой форме предоставить обратную связь?</th>
                            <th colspan="2">Полезные вопросы на уроке</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($card['principles'] as $key => $principle)
                            <tr class="text-center">
                                <td>
                                    <a class="list-group-item list-group-item-action">{{ $principle->name }}</a>
                                </td>
                                <td>
                                    @if ($card['method'][$key]['id'] == '1')
                                        <p></p>
                                    @else
                                        <a href="#" class="list-group-item list-group-item-action" data-toggle="modal"
                                            data-target="#exampleModalCenter{{ $card['method'][$key]['name'] }}{{ $card['method'][$key]['id'] }}">{{ $card['method'][$key]['name'] }}</a>
                                        <div class="modal fade"
                                            id="exampleModalCenter{{ $card['method'][$key]['name'] }}{{ $card['method'][$key]['id'] }}"
                                            tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Title</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><b>Название</b> {{ $card['method'][$key]['name'] }}</p>
                                                        <p><b>Цель</b> {{ $card['method'][$key]['target'] }}
                                                        <p><b>Описание</b> {{ $card['method'][$key]['description'] }}
                                                        <p><b>Необходимые ресурсы</b>
                                                            {{ $card['method'][$key]['required_resources'] }}
                                                        <p><b>Рекомендуемый этап урока</b>
                                                            {{ $card['method'][$key]['recommended_stage'] }}
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </div>
                                    @endif
                                </td>
                                <td>
                                    <a href="#" class="list-group-item list-group-item-action" data-toggle="modal"
                                        data-target="#exampleModalCenter{{ $card['feedback'][$key]['name'] }}{{ $card['feedback'][$key]['id'] }}">{{ $card['feedback'][$key]['name'] }}</a>
                                    <!-- Modal -->
                                    <div class="modal fade"
                                        id="exampleModalCenter{{ $card['feedback'][$key]['name'] }}{{ $card['feedback'][$key]['id'] }}"
                                        tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle">Title</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><b>Название</b> {{ $card['feedback'][$key]['name'] }}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td colspan="2">
                                    <a
                                        class="list-group-item list-group-item-action">{{ $card['questions'][$key]['name'] }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- <div class="card m-3">
                        <div class="card-header">
                            Каковы ключевые принципы реализации стратегии?
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                @foreach ($card['principles'] as $principle)
                                    <a href="#" class="list-group-item list-group-item-action" data-toggle="modal" data-target="#exampleModalCenter">{{$principle->name}}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="card m-3" >
                        <div class="card-header">
                            Какой метод необходимо применить?
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                @foreach ($card['method'] as $method)
                                    @if ($method->id == '1')
                                        <a class="list-group-item list-group-item-action" ><p style="opacity: 0.0"></p></a>
                                    @else
                                        <a href="#" class="list-group-item list-group-item-action" data-toggle="modal" data-target="#exampleModalCenter{{$method->id}}">{{$method->name}}</a>
                                    @endif
                                    <div class="modal fade" id="exampleModalCenter{{$method->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Title</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                {{$method->name}}
                                                {{$method->target}}
                                                {{$method->required_resources}}
                                                {{$method->recomended_stage}}
                                            </div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="card m-3" >
                        <div class="card-header">
                            В какой форме предоставить обратную связь?
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                @foreach ($card['feedback'] as $feedback)
                                    <a href="#" class="list-group-item list-group-item-action" data-toggle="modal" data-target="#exampleModalCenter{{$feedback->id}}">{{$feedback->name}}</a>
                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModalCenter{{$feedback->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Title</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                {{$feedback->name}}
                                            </div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="card m-3" >
                        <div class="card-header">
                            Полезные вопросы на уроке
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                @foreach ($card['questions'] as $question)
                                    <a href="#" class="list-group-item list-group-item-action" data-toggle="modal" data-target="#exampleModalCenter">{{$question->name}}</a>
                                @endforeach
                            </div>
                        </div>
                    </div> --}}
        </div>

    </div>
@endsection
