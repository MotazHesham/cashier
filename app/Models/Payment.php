<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;
    use Auditable;

    public const PAYMENT_STATUS_SELECT = [
        'unpaid' => 'Un Paid',
        'paid'   => 'Paid',
    ];

    public const PAYMENT_TYPE_SELECT = [
        'cash'        => 'Cash',
        'credit_card' => 'Credit Card',
    ];

    public $table = 'payments';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'completed',
        'payment_order',
        'payment_type',
        'payment_status',
        'amount',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
