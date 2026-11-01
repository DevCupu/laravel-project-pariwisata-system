<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'short_description',
        'description',
        'duration_minutes',
        'price_from',
        'min_age',
        'is_featured',
        'is_active',
        'sort_order',
        'hero_image_path',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    protected static function booted(): void
    {
        static::saving(function (Activity $activity) {
            if (empty($activity->slug)) {
                $activity->slug = Str::slug($activity->title);
            }
        });

        static::saved(function (Activity $activity) {
            $activity->syncFacility();
        });

        static::deleted(function (Activity $activity) {
            $activity->facility()->delete();
        });
    }

    public function facility(): MorphOne
    {
        return $this->morphOne(Facility::class, 'bookable');
    }

    public function syncFacility(): void
    {
        $data = [
            'name' => $this->title,
            'slug' => 'activity-'.$this->slug,
            'category' => 'wahana',
            'description' => $this->short_description ?: $this->description,
            'capacity_per_day' => 0,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order ?? 0,
        ];

        $this->facility()->updateOrCreate(
            ['bookable_type' => self::class, 'bookable_id' => $this->id],
            $data
        );
    }
}
