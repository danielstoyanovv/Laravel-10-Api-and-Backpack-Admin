<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'author',
        'content',
        'liked_from',
        'user_id'
    ];
}
