<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enquiry extends Model
{
    protected $fillable = [
        'user_id',
        'package_id',
        'name',
        'email',
        'phone',
        'whatsapp',
        'preferred_destinations',
        'travel_dates',
        'group_size',
        'budget_range',
        'special_interests',
        'message',
        'status',
        'assigned_to',
        'admin_notes',
        'source',
    ];

    protected function casts(): array
    {
        return [
            'group_size' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }
}
