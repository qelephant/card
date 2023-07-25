<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic', 'goal', 'quarter', 'subject', 'class', 'liter', 'planning_date', 'evaluation_criteria', 'language_goals', 'instilling_values', 'intersubject_communications', 'prior_knowledge',
        'first_lesson_editor', 'first_lesson_resource', 'lesson_editor0', 'lesson_resource0', 'lesson_editor1', 'lesson_resource1', 'lesson_editor2', 'lesson_resource2', 'lesson_editor3', 'lesson_resource3', 'main_lesson_editor', 'main_lesson_resource',
        'last_lesson_editor', 'last_lesson_resource', 'reflection', 'card_id', 'user_id'
    ];

    /**
     * Get the card that owns the Lesson
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * Get the user that owns the Lesson
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

     /**
     * Get all of the principle for the Card
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function principles()
    {
        return $this->belongsToMany(Principle::class);
    }

    /**
     * Get the questions that owns the Card
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->belongsToMany(Question::class);
    }


    /**
     * Get all of the feedback for the Lesson
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function feedback()
    {
        return $this->belongsToMany(Feedback::class);
    }

    /**
     * Get all of the methods for the Lesson
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function methods()
    {
        return $this->belongsToMany(Method::class);
    }
}
