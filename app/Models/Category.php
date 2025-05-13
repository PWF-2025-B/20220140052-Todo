<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'user_id',
    ];

    /**
     * Get all todos that belong to this category.
     */
    public function todos()
    {
        return $this->hasMany(Todo::class);
    }
}
