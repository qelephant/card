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
            //'subject_name' => 'required|string',
            'planning_date' => 'required|date|after_or_equal:created_at',
            'evaluation_criteria' => 'required|string',
            'language_goals' => 'sometimes|string',
            'instilling_values' => 'string',
            'intersubject_communications' => 'string',
            'prior_knowledge' => 'string',
            'start_lesson_comments1' => 'sometimes|max:1000',
            'start_lesson_resource1' => 'sometimes|max:1000',
            'start_lesson_comments2' => 'sometimes|max:1000',
            'start_lesson_resource2' => 'sometimes|max:1000',
            'start_lesson_comments3' => 'sometimes|max:1000',
            'start_lesson_resource3' => 'sometimes|max:1000',
            'reflection'=> 'sometimes|max:1000',
            'card_id' => 'exists:cards,id',
            'user_id' => 'exists:users,id'
        ];
    }
}
