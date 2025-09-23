<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseCategory extends Pivot
{
    use HasUuids;

    protected $table = 'course_categories';

    protected $fillable = [
        'course_id',
        'category_id',
        'is_primary',
        'order_index',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Helper Methods
    public function isPrimary(): bool
    {
        return $this->is_primary;
    }
}
