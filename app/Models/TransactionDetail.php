<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $guarded = ['id'];

    // Define the relationship with Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    use HasFactory;
}

