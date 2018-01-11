<?php

namespace Fomvasss\SlugMaker\Models;

trait ScopesTrait
{
    public function scopeByClassesByNames($query, array $attributes, $key = 'name')
    {
        foreach ($attributes as $type => $slugs) {
            if (! empty($slugs)) {
                $names = is_array($slugs) ? $slugs : [$slugs];
                $query->orWhere('slugable_type', $type)->whereIn($key, $names);
            }
        }

        return $query;
    }

    public function scopeByNameByClass($query, $name, $class = null)
    {
        $query->where('name', $name);
        return $class ? $query->where('slugable_type', $class) : $query;
    }

    public function scopeByNamesByClass($query, $name, $class = null)
    {
        $query->whereIn('name', $name);
        return $class ? $query->where('slugable_type', $class) : $query;
    }
}
