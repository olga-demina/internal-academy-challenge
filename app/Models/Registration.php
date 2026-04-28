<?php

namespace App\Models;

use App\Enums\RegistrationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model {
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'status' => RegistrationStatus::class,
    ];


    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function workshop(): BelongsTo {
        return $this->belongsTo(Workshop::class);
    }
}
