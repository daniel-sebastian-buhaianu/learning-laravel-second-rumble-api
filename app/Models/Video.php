<?php

namespace App\Models;

use App\Models\Channel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Video extends Model
{
    use HasFactory;

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
}
