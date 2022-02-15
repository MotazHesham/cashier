<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;
use Carbon\Carbon;

class AttributeProduct extends Model
{
    public $table = 'attribute_product'; 

    protected $dates = [ 
        'created_at',
        'updated_at', 
    ];

    protected $fillable = [
        'attribute_id',
        'product_id',
        'variant',
        'price', 
        'created_at',
        'updated_at', 
    ];

    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
