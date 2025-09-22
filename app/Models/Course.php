<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory, HasUuids, Sluggable, SoftDeletes;

    protected $fillable = [
        'instructor_id',
        'name',
        'slug',
        'description',
        'short_description',
        'learning_objectives',
        'prerequisites',
        'language',
        'thumbnail_url',
        'video_preview_url',
        'original_price',
        'price',
        'is_published',
        'is_featured',
        'duration_minutes',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['name'],
            ],
        ];
    }

    protected function casts(): array
    {
        return [
            'learning_objectives' => 'array',
            'prerequisites' => 'array',
            'original_price' => 'decimal:2',
            'price' => 'decimal:2',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    // Relationships
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withPivot(['status', 'enrolled_at', 'completed_at', 'progress_percentage', 'last_accessed_at', 'amount_paid', 'payment_method', 'payment_reference'])
            ->withTimestamps();
    }

    public function sections(): HasMany
    {
        return $this->hasMany(CourseSection::class)->orderBy('order_index');
    }

    public function publishedSections(): HasMany
    {
        return $this->hasMany(CourseSection::class)
            ->where('is_published', true)
            ->orderBy('order_index');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'course_categories')
            ->withPivot(['is_primary', 'order_index'])
            ->withTimestamps()
            ->orderByPivot('order_index');
    }

    public function primaryCategory()
    {
        return $this->categories()->wherePivot('is_primary', true)->first();
    }

    // Helper methods
    public function getSectionCount(): int
    {
        return $this->sections()->count();
    }

    public function getPublishedSectionCount(): int
    {
        return $this->publishedSections()->count();
    }

    public function hasSections(): bool
    {
        return $this->sections()->exists();
    }
}
