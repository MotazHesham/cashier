<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{ 

    public $table = 'order_product';

    protected $dates = [
        'created_at',
        'updated_at', 
    ];

    protected $fillable = [ 
        'order_id',
        'product_id',
        'product_name',
        'attributes',
        'quantity',
        'price',
        'extra_price',
        'total_cost',
        'created_at',
        'updated_at', 
    ]; 

    public function product()
    {
        return $this->belongsTo(Product::class);
    } 

    public function order()
    {
        return $this->belongsTo(Order::class);
    } 

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
