<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    /**
     * fillable
     */
    protected $fillable = ['product_id', 'type', 'quantity'];

     /**
     * relation products
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}