<?php

namespace Fomvasss\SlugMaker;

trait ModelHasSlug
{
    public $slugSourceFields;

    /**
     * @return mixed
     */
    public function slug()
    {
        return $this->morphOne(config('slugmaker.model', \Fomvasss\SlugMaker\Models\Slug::class), 'slugable');
    }

    /**
     * The scope for fet slug name.
     */
    public function getSlugName()
    {
        if ($this->slug) {
            return $this->slug->name;
        }
        return;
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function makeSlug($slug = '')
    {
        return slug_make($this, $slug);
    }

    /**
     * Models by slugs.
     *
     * @param $query
     * @param string $slug
     * @return mixed
     */
    public function scopeBySlugs($query, $slug)
    {
        $slugs = is_array($slug) ? $slug : [$slug];

        return $query->whereHas('slug', function ($q) use ($slugs) {
            $q->whereIn('name', $slugs);
        });
    }

    /**
     * Find first model by slug.
     *
     * @param $query
     * @param string $slug
     * @return mixed
     */
    public function scopeFindBySlug($query, string $slug)
    {
        return $this->scopeBySlugs($query, $slug)->first();
    }

    /**
     * Find first model by slug or throw exciption.
     *
     * @param $query
     * @param string $slug
     * @return mixed
     */
    public function scopeFindOrFailBySlug($query, string $slug)
    {
        return $this->scopeBySlugs($query, $slug)->firstOrFail();
    }

    /**
     * Get models by slugs.
     *
     * @param $query
     * @param $slugs
     * @return mixed
     */
    public function scopeGetBySlugs($query, $slugs)
    {
        return $this->scopeBySlugs($query, $slugs)->get();
    }

    /**
     * Get array ids by slugs.
     *
     * @param $query
     * @param array $slugs
     * @return array
     */
    public function scopeGetArrayIdsBySlugs($query, array $slugs): array
    {
        return $this->scopeBySlugs($query, $slugs)->pluck('id')->toArray();
    }

    public function getSlugSourceFields(): array
    {
        $sourceFields = config('slugmaker.default_source_fields', []);

        return is_array($sourceFields) ? $sourceFields : [$sourceFields];
    }
}
