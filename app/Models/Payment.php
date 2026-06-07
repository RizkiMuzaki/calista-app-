<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'user_id',
        'plan_id',
        'provider',
        'provider_transaction_id',
        'provider_subscription_id',
        'provider_payload',
        'reference',
        'merchant_ref',
        'payment_method',
        'payment_name',
        'amount',
        'fee_merchant',
        'fee_customer',
        'total_fee',
        'amount_received',
        'pay_code',
        'pay_url',
        'checkout_url',
        'status',
        'expired_time',
    ];

    protected $casts = [
        'amount' => 'integer', // Ubah ke integer untuk konsistensi
        'fee_merchant' => 'integer',
        'fee_customer' => 'integer',
        'total_fee' => 'integer',
        'amount_received' => 'integer',
        'expired_time' => 'datetime',
        'provider_payload' => 'array',
    ];

    // Scope untuk payment yang masih aktif
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['UNPAID', 'PENDING']);
    }

    // Scope untuk payment yang sudah selesai
    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['PAID', 'EXPIRED', 'FAILED']);
    }

    // Accessor untuk status display
    public function getStatusDisplayAttribute()
    {
        $statusMap = [
            'UNPAID' => 'Belum Bayar',
            'PENDING' => 'Menunggu Konfirmasi',
            'PAID' => 'Sudah Bayar',
            'EXPIRED' => 'Kadaluarsa',
            'FAILED' => 'Gagal'
        ];
        
        return $statusMap[$this->status] ?? $this->status;
    }

    // Method untuk cek apakah sudah expired
    public function getIsExpiredAttribute()
    {
        return $this->expired_time && $this->expired_time->isPast();
    }

      public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

     // Helper method untuk cek jenis payment
    public function getIsPlanPaymentAttribute()
    {
        return !is_null($this->plan_id);
    }

    public function getIsOrderPaymentAttribute()
    {
        return !is_null($this->pesanan_id);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
