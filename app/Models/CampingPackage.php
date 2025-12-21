<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CampingPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'nights',
        'capacity',
        'tagline',
        'description',
        'includes',
        'image_path',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'integer',
        'nights' => 'integer',
        'capacity' => 'integer',
        'includes' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getImageUrlAttribute(): ?string
    {
        $path = $this->image_path;
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (Str::startsWith($path, '/storage/')) {
            return $path;
        }

        if (Str::startsWith($path, 'storage/')) {
            return '/'.$path;
        }

        return Storage::url($path);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    protected static function booted(): void
    {
        static::saving(function (CampingPackage $package) {
            if (empty($package->slug)) {
                $package->slug = Str::slug($package->name);
            }
        });

        static::saved(function (CampingPackage $package) {
            $package->syncFacility();
        });

        static::deleted(function (CampingPackage $package) {
            $package->facility()->delete();
        });
    }

    public function facility(): MorphOne
    {
        return $this->morphOne(Facility::class, 'bookable');
    }

    public function syncFacility(): void
    {
        $data = [
            'name' => $this->name,
            'slug' => 'camp-' . $this->slug,
            'category' => 'camping',
            'description' => $this->description,
            'capacity_per_day' => $this->capacity ?? 0,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order ?? 0,
        ];

        $this->facility()->updateOrCreate(
            ['bookable_type' => self::class, 'bookable_id' => $this->id],
            $data
        );
    }
}
