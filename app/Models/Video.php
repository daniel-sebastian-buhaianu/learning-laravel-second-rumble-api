<?php

namespace App\Models;

use App\Models\Channel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Video extends Model
{
    use HasFactory;

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
}
