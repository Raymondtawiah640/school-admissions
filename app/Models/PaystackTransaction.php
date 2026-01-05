<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaystackTransaction extends Model
{
    protected $fillable = [
        'reference',
        'email',
        'amount',
        'currency',
        'status',
        'gateway_response',
        'channel',
        'authorization_code',
        'card_type',
        'last4',
        'exp_month',
        'exp_year',
        'bank',
        'country_code',
        'brand',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];
}
