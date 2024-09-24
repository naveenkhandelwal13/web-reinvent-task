<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Todo extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $primaryKey = 'id';
    protected $fillable = [
        'task_name',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
    ];


}
