<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
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

    // Relationships
    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }

    public function enquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class, 'customer_id');
    }

    public function sentEnquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class, 'customer_id');
    }

    public function receivedEnquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class, 'provider_id');
    }

    public function enquiryReplies(): HasMany
    {
        return $this->hasMany(EnquiryReply::class);
    }

    // Helper methods
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isProvider(): bool
    {
        return $this->hasRole('provider');
    }

    public function isCustomer(): bool
    {
        return $this->hasRole('customer');
    }
}
