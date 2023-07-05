@extends('layouts.app')
@section('title', 'Проактивная карта')

@section('style')
    <style>
        .hidden {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid mt-3">
        <h2>Создание урока</h2>

        @if (count($errors) > 0)
            @foreach ($errors->all() as $error)
                <p class="alert alert-danger">{{ $error }}</p>
            @endforeach
        @endif

        @if (session()->has('message'))
            <p class="alert alert-success">{{ session('message') }}</p>
        @endif

        <form action="{{ route('lesson.store', ['card' => $card->id]) }}" method="post">
            @csrf
            <input type="hidden" id="card_id" name="card_id" value="{{ $card->id }}" />
            <div class="form-group">
                <label for="topic">Тема урока</label>
                <input type="text" class="form-control" id="topic" name="topic" value="{{ $lesson->topic }}">
                @error('topic')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="goal">Цель урока</label>
                <input type="text" class="form-control" id="goal" name="goal" value="{{ $lesson->goal }}">
                @error('goal')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="planning_date">Дата проведение:</label>
                <input type="date" id="planning_date" name="planning_date" class="form-control" min="{{ date('Y-m-d') }}"
                    value="{{ $lesson->planning_date }}">
            </div>
            <div class="form-group">
                <label for="evaluation_criteria">Критерии оценивания</label>
                <input type="text" class="form-control" id="evaluation_criteria" name="evaluation_criteria"
                    value="{{ $lesson->evaluation_criteria }}">
                @error('evaluation_criteria')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            @isset($lesson->language_goals)
                <div class="form-group">
                    <label for="language_goals">Языковые цели</label>
                    <input type="text" class="form-control" id="language_goals" name="language_goals" data-element="Языковые цели"
                        value="{{ $lesson->language_goals }}">
                    @error('language_goals')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            @endisset

            @isset($lesson->intersubject_communications)
                <div class="form-group">
                    <label for="intersubject_communications">Привитие ценностей</label>
                    <input type="text" class="form-control" id="intersubject_communications" name="intersubject_communications" data-element="Языковые цели"
                        value="{{ $lesson->intersubject_communications }}">
                    @error('intersubject_communications')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            @endisset

            <div id="dynamicFields"></div>
            <div class="form-group" id="selectFieldGroup">
                <label for="selectField">Выберите элемент:</label>
                <select class="form-control" id="selectField" onchange="toggleSelectFieldGroup()">
                    <option value="">Выберите...</option>
                    <option name="language_goals" value="Языковые цели">Языковые цели</option>
                    <option name="instilling_values" value="Привитие ценностей">Привитие ценностей</option>
                    <option name="intersubject_communications" value="Межпредметные связи">Межпредметные связи</option>
                    <option name="prior_knowledge" value="Предварительные знания">Предварительные знания</option>
                </select>
                <button type="button" class="btn btn-primary mt-2" onclick="addField()">Добавить поле</button>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection

@section('script')
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
        selectElement.removeChild(selectElement.querySelector('option[value="' + selectedValue + '"]'));
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

        // Проверяем наличие полей с атрибутом data-element
        var existingFields = document.querySelectorAll('[data-element]');
        var existingValues = Array.from(existingFields).map(function(field) {
            return field.getAttribute('data-element');
        });

        // Удаляем уже существующие значения из списка выбора
        var options = selectElement.options;
        for (var i = options.length - 1; i >= 0; i--) {
            if (existingValues.includes(options[i].value)) {
                selectElement.remove(i);
            }
        }

        if (selectElement.options.length <= 1) {
            selectFieldGroup.classList.add('hidden');
            addButton.classList.add('hidden');
        } else {
            selectFieldGroup.classList.remove('hidden');
            addButton.classList.remove('hidden');
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        toggleSelectFieldGroup();
    });
</script>
@endsection
