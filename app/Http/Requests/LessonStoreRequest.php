<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LessonStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'topic' => 'required|string',
            'goal' => 'required|string',
            'quarter' => 'required|string',
            'subject' => 'required|string',
            'class' => 'required|numeric',
            'liter' => 'required|alpha:ascii',
            'planning_date' => 'required|date|after_or_equal:created_at',
            'evaluation_criteria' => 'required|string',
            'language_goals' => 'sometimes|string',
            'instilling_values' => 'string',
            'intersubject_communications' => 'string',
            'prior_knowledge' => 'string',
            'first_lesson_editor' => 'sometimes|max:400',
            'first_lesson_resource' => 'sometimes|max:200',
            'lesson_editor0' => 'sometimes|max:400',
            'lesson_resource0' => 'sometimes|max:200',
            'lesson_editor1' => 'sometimes|max:400',
            'lesson_resource1' => 'sometimes|max:200',
            'lesson_editor2' => 'sometimes|max:400',
            'lesson_resource2' => 'sometimes|max:200',
            'lesson_editor3' => 'sometimes|max:400',
            'lesson_resource3' => 'sometimes|max:200',
            'main_lesson_editor' => 'sometimes|max:400',
            'main_lesson_resource' => 'sometimes|max:200',
            'last_lesson_editor' => 'sometimes|max:400',
            'last_lesson_resource' => 'sometimes|max:200',
            'reflection' => 'sometimes|max:1000',
            'card_id' => 'exists:cards,id',
            'user_id' => 'exists:users,id'
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'topic.required' => 'Email is required!',
            'goal.required' => 'Name is required!',
            'quarter.required' => 'Password is required!'
        ];
    }
}
