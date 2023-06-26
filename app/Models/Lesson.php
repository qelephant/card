<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic', 'goal', 'subject_name', 'planning_date', 'evaluation_criteria', 'language_goals', 'instilling_values', 'intersubject_communications', 'prior_knowledge',
        'start_lesson_comments1', 'start_lesson_resource1', 'start_lesson_comments2', 'start_lesson_resource2', 'start_lesson_comments3', 'start_lesson_resource3',
        'reflection', 'card_id', 'user_id'
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
}
