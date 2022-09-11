<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Order extends Model
{
    use SoftDeletes;
    use MultiTenantModelTrait;
    use Auditable;

    public $table = 'orders';

    public const PAYMENT_TYPE_SELECT = [
        'cash' => 'cash',
        'qr_code'   => 'Qr Code',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'code',
        'entry_date',
        'paid_up',
        'discount',
        'total_cost',
        'payment_type',
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
    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format') . ' ' .config('panel.time_format')) : null;
    }

    public function products()
    {
        return $this->hasMany(OrderProduct::class);
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
