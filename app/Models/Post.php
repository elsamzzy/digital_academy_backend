<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'post',
        'created_by',
    ];

    public function comments() {
        return $this->hasMany(Comment::class, 'posts_id', 'id');
    }

    public function owner() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
