<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentBlock extends Model
{
    protected $fillable = [
        'page_section_id',
        'block_key',
        'content',
        'sort_order',
    ];

    protected $casts = [
        'content' => 'array',
        'sort_order' => 'integer',
    ];

    /**
     * Get the page section that holds this content block.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(PageSection::class, 'page_section_id');
    }
}
