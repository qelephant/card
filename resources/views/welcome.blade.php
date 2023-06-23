@extends('layouts.app')
@section('title', 'Проактивные карты')

@section('content')
<div class="container mt-3">
    @foreach ($strategies as $strategy)
    <p class="list-group-item list-group-item-primary">{{ $strategy->name }}</p>
    <div class="list-group">
        @foreach ($strategy->cards as $card)
        <a href="{{ route('card.index', ['id' => $card->id]) }}" class="list-group-item list-group-item-action">{{$card->name}}</a>
        @endforeach
    </div>
    @endforeach
</div>
@endsection
