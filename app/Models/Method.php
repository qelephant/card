<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Method extends Model
{
    use HasFactory;

    /**
     * The card that belong to the Method
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function card()
    {
        return $this->belongsToMany(Card::class, 'card_method');
    }

    /**
     * Get the user that owns the Method
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
