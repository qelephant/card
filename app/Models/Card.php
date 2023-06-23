<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    /**
     * Get the strategy that owns the Card
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function strategy()
    {
        return $this->belongsTo(Strategy::class);
    }

    /**
     * Get all of the principle for the Card
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function principles()
    {
        return $this->hasMany(Principle::class);
    }

    /**
     * Get the questions that owns the Card
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Get all of the lessons for the Card
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    /**
     * The feedback that belong to the Card
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function feedback()
    {
        return $this->belongsToMany(Feedback::class, 'card_feedback');
    }

    /**
     * The method that belong to the Card
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function method()
    {
        return $this->belongsToMany(Method::class);
    }

}
