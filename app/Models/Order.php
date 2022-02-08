<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    use MultiTenantModelTrait;
    use Auditable;

    public $table = 'orders';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'code',
        'paid_up',
        'total_cost',
        'voucher_code_id',
        'created_by_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function voucher_code()
    {
        return $this->belongsTo(VoucherCode::class, 'voucher_code_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
