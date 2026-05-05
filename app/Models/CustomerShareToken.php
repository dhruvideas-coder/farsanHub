<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerShareToken extends Model
{
    protected $fillable = ['customer_id', 'user_id', 'token', 'expires_at'];

    protected $casts = ['expires_at' => 'datetime'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now());
    }
}
