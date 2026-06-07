<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'parent_pin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'parent_pin',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function anaks()
    {
        return $this->hasMany(Anak::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

       public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    /**
     * Relationship ke payments
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Accessor untuk mendapatkan data analisis yang lengkap
     */
     /**
     * Relationship ke trial usage
     */


    /**
     * Dapatkan trial usage atau buat jika belum ada
     */
   
    /**
     * Cek apakah user masih memiliki trial
     */
    public function hasTrialLeft(): bool
    {
        $trial = $this->getOrCreateTrialUsage();
        return $trial->hasTrialLeft();
    }

    /**
     * Dapatkan sisa trial user
     */
    public function getRemainingTrial(): int
    {
        $trial = $this->getOrCreateTrialUsage();
        return $trial->getRemainingTrial();
    }

    /**
     * Record penggunaan trial
     */
    public function recordTrialUsage(): bool
    {
        $trial = $this->getOrCreateTrialUsage();
        return $trial->incrementUsage();
    }

    /**
     * Cek apakah user memiliki langganan aktif
     */
    public function hasActiveSubscription(): bool
    {
        return $this->subscriptions()
            ->where('status', 'aktif')
            ->where('tanggal_berakhir', '>', now())
            ->exists();
    }

 

    /**
     * Cek apakah user bisa menggunakan fitur premium
     */
    public function canUsePremiumFeature(): bool
    {
        return $this->hasActiveSubscription() || $this->hasTrialLeft();
    }

    /**
     * Gunakan fitur premium (akan menggunakan trial jika belum berlangganan)
     */
    public function usePremiumFeature(): bool
    {
        // Jika sudah berlangganan, langsung bisa pakai
        if ($this->hasActiveSubscription()) {
            return true;
        }

        // Jika masih ada trial, gunakan trial
        if ($this->hasTrialLeft()) {
            return $this->recordTrialUsage();
        }

        return false;
    }

  
    
}
