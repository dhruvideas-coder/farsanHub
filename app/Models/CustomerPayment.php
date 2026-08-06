<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_id',
        'month_year',
        'payment_date',
        'payment_amount',
        'account',
        'transaction_id',
    ];

    protected $casts = [
        'payment_date'   => 'date',
        'payment_amount' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
