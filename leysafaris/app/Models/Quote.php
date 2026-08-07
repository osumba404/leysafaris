<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quote extends Model
{
    protected $fillable = [
        'enquiry_id',
        'package_id',
        'reference',
        'title',
        'total_amount',
        'currency',
        'line_items',
        'notes',
        'status',
        'valid_until',
        'sent_at',
        'accepted_at',
        'pdf_path',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'line_items' => 'array',
            'valid_until' => 'date',
            'sent_at' => 'datetime',
            'accepted_at' => 'datetime',
        ];
    }

    public function enquiry(): BelongsTo
    {
        return $this->belongsTo(Enquiry::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
