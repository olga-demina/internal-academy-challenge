<?php

namespace App\Models;

use App\Enums\RegistrationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workshop extends Model {
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $appends = ['available_seats'];

    public function getAvailableSeatsAttribute(): int {
        return $this->capacity - ($this->confirmed_registrations_count ?? 0);
    }

    public function scopeWithAvailableSeats($query): void
    {
        $query->withCount([
            'registrations as confirmed_registrations_count' => fn($q) => $q->where('status', RegistrationStatus::Confirmed),
        ]);
    }

    public function scopeWithUserRegistration($query, int $userId): void
    {
        $query->withExists([
            'registrations as is_registered' => fn($q) => $q
                ->where('user_id', $userId)
                ->where('status', RegistrationStatus::Confirmed),
        ]);
    }

    public function creator(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function registrations(): HasMany {
        return $this->hasMany(Registration::class);
    }
}
