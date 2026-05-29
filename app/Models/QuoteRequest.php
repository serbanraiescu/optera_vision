<?php

namespace App\Models;

use App\Enums\QuoteStatus;
use App\Services\LeadValuationService;
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
        'client_id',
        'is_important',
        'assigned_at',
    ];

    protected $casts = [
        'is_upgrade' => 'boolean',
        'camera_count' => 'integer',
        'status' => QuoteStatus::class,
        'is_important' => 'boolean',
        'assigned_at' => 'datetime',
    ];

    protected $appends = ['estimated_value'];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::saved(function ($quoteRequest) {
            \Illuminate\Support\Facades\Cache::forget('admin_dashboard_metrics');
            if ($quoteRequest->assigned_to) {
                \Illuminate\Support\Facades\Cache::forget('admin_dashboard_metrics_tech_' . $quoteRequest->assigned_to);
            }
            if ($quoteRequest->isDirty('assigned_to') && $quoteRequest->getOriginal('assigned_to')) {
                \Illuminate\Support\Facades\Cache::forget('admin_dashboard_metrics_tech_' . $quoteRequest->getOriginal('assigned_to'));
            }
        });

        static::deleted(function ($quoteRequest) {
            \Illuminate\Support\Facades\Cache::forget('admin_dashboard_metrics');
            if ($quoteRequest->assigned_to) {
                \Illuminate\Support\Facades\Cache::forget('admin_dashboard_metrics_tech_' . $quoteRequest->assigned_to);
            }
        });
    }

    /**
     * Get dynamic estimated project valuation using LeadValuationService.
     */
    public function getEstimatedValueAttribute(): float
    {
        return resolve(LeadValuationService::class)->calculate($this);
    }

    /**
     * Get the chronological notes and system timeline activities for this lead.
     */
    public function notes(): HasMany
    {
        return $this->hasMany(QuoteNote::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the admin user assigned to handle this lead.
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the persistent client linked to this lead, if any.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Scope: Search by name, email, phone or locality.
     */
    public function scopeSearch($query, ?string $search)
    {
        if ($search) {
            $search = trim($search);
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('locality', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    /**
     * Scope: Filter by status (accepts QuoteStatus enum, string or array).
     */
    public function scopeStatus($query, $status)
    {
        if ($status) {
            if ($status instanceof QuoteStatus) {
                return $query->where('status', $status->value);
            }
            if (is_array($status)) {
                return $query->whereIn('status', $status);
            }
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Scope: Filter by assigned user.
     */
    public function scopeAssigned($query, ?int $userId)
    {
        if ($userId) {
            return $query->where('assigned_to', $userId);
        }
        return $query;
    }
}
