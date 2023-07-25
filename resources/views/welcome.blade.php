@extends('layouts.app')
@section('title', 'Проактивные карты')

@section('content')
    <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
        <h1 class="display-4 fw-normal"><img src="http://proactivecards.cpi-nis.kz/wp-content/uploads/2022/02/logo_eng_cpi300.png" alt=""></h1>
        <p class="fs-5 text-muted">Быть проактивным в формативном оценивании</p>
    </div>
    <div class="container mt-3 mb-3">
        <div class="list-group">
            @foreach ($strategies as $strategy)
                <a href="#" class="list-group-item list-group-item-action list-group-item-primary">
                    {{ $strategy->name }} {{ $strategy->description }}
                </a>
                @foreach ($strategy->cards as $card)
                    <a href="{{ route('card.index', ['id' => $card->id]) }}"
                        class="list-group-item list-group-item-action">{{ $card->name }}</a>
                @endforeach
            @endforeach
        </div>

    </div>
@endsection
