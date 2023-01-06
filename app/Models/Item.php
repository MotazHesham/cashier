<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class Item extends Model
{
    use SoftDeletes;
    use Auditable;

    public $table = 'items';

    public const MEASURE_SELECT = [
        'piece' => 'قطعة',
        'bottle' => 'زجاجة',
        'box' => 'علبة',
        'kilo' => 'كيلو',
        'gram' => 'جرام',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'item',
        'measure',
        'current_stock',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function stock()
    {
        return $this->hasMany(Stock::class, 'item_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
