<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseSection extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'order_index',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    // Relationships
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index');
    }

    // Helper Methods
    public function isPublished(): bool
    {
        return $this->is_published;
    }

    public function getNextOrderIndex(): int
    {
        return $this->where('course_id', $this->course_id)->max('order_index') + 1;
    }
}

class Category extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'parent_id',
        'is_published',
        'order_index',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    // Relationships
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order_index');
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_categories')
            ->withPivot(['is_primary', 'order_index'])
            ->withTimestamps()
            ->orderByPivot('order_index');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeChildren($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index');
    }

    // Helper Methods
    public function isParent(): bool
    {
        return is_null($this->parent_id);
    }

    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    public function isPublished(): bool
    {
        return $this->is_published;
    }

    public function getFullName(): string
    {
        if ($this->parent) {
            return $this->parent->name.' > '.$this->name;
        }

        return $this->name;
    }

    public function getCourseCount(): int
    {
        return $this->courses()->count();
    }
}

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
