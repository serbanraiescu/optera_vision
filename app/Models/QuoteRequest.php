<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'locality',
        'location_type',
        'is_upgrade',
        'camera_count',
        'message',
        'lead_source',
        'status',
        'assigned_to',
    ];

    protected $casts = [
        'is_upgrade' => 'boolean',
        'camera_count' => 'integer',
    ];

    /**
     * Get the chronological notes and system timeline activities for this lead.
     */
    public function notes(): HasMany
    {
        return $this->hasMany(QuoteNote::class)->orderBy('created_at', 'asc');
    }

    /**
     * Get the admin user assigned to handle this lead.
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
