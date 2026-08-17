<?php

namespace App\Models;

use App\Notifications\VerifyUserNotification;
use Carbon\Carbon;
use DateTimeInterface;
use Hash;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, SoftDeletes, Notifiable, HasFactory, InteractsWithMedia;

    public $table = 'users';

    protected $hidden = [
        'remember_token',
        'password',
    ];

    protected $dates = [
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'email_verified_at',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
        'referral_code',
        'referred_by',
        'wallet_balance',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getIsAdminAttribute()
    {
        return $this->roles()->where('id', 1)->exists();
    }

    public function scopeAdmins($query)
    {
        return $query->whereHas('roles', fn ($q) => $q->where('id', 1));
    }

    public function scopeCustomers($query)
    {
        return $query->whereDoesntHave('roles', fn ($q) => $q->where('id', 1));
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        self::created(function (self $user) {
            $registrationRole = config('panel.registration_default_role');
            if (! $user->roles()->get()->contains($registrationRole)) {
                $user->roles()->attach($registrationRole);
            }
        });
        self::creating(function (self $user) {
            if (empty($user->referral_code)) {
                $user->referral_code = self::generateUniqueReferralCode();
            }
        });
    }

    public static function generateUniqueReferralCode(): string
    {
        do {
            $code = strtoupper(Str::random(10));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }

    public function referrer()
    {
        return $this->belongsTo(self::class, 'referred_by');
    }

    public function referredUsers()
    {
        return $this->hasMany(self::class, 'referred_by');
    }

    public function referralsMade()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_image')->singleFile();
    }

    public function getProfileImageUrlAttribute()
    {
        return $this->getFirstMediaUrl('profile_image') ?: null;
    }
}
