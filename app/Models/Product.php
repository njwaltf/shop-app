<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $guarded = [
        'id'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    use HasFactory;
}
