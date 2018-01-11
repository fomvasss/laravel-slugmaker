<?php

namespace Fomvasss\SlugMaker;

/**
 * Class SlugGenerator
 *
 * @package \Fomvasss\SlugGenerator
 */
trait SlugGenerator
{
    /**
     * @param string $str
     * @return string
     */
    public function getSlug(string $str)
    {
        $nonUniqueSlug = $this->makeNonUniqueSlug($str);

        return $this->makeUniqueSlug($nonUniqueSlug);
    }

    /**
     * @param string $str
     * @return string
     */
    protected function makeNonUniqueSlug(string $str): string
    {
        return str_slug($this->getClippedSlugWithPrefixSuffix($str), $this->slugConfig['separator']);
    }

    /**
     * @param string $slugSourceString
     * @return string
     */
    public function getClippedSlugWithPrefixSuffix(string $slugSourceString): string
    {
        $prefix = $this->slugConfig['prefix'];
        $suffix = $this->slugConfig['suffix'];

        $maximumLength= $this->slugConfig['maximum_length'];

        if ($strLen = strlen($prefix) + strlen($suffix)) {
            $limitWithoutPrefixSuffix = $maximumLength - ($strLen + 2);

            if ($limitWithoutPrefixSuffix < 1) {
                return str_limit($prefix.' '.$suffix, $maximumLength);
            }

            return $prefix.' '.str_limit($slugSourceString, $limitWithoutPrefixSuffix, '').' '.$suffix;
        }

        return str_limit($slugSourceString, $maximumLength);
    }

    /**
     * @param string $slug
     * @return string
     */
    protected function makeUniqueSlug(string $slug): string
    {
        $originalSlug = $slug;
        $i = 1;
        while ($this->otherRecordExistsWithSlug($slug) || $slug === '') {
            $slug = $originalSlug.'-'.$i++;
        }

        return $slug;
    }

    /**
     * @param string $slug
     * @return bool
     */
    protected function otherRecordExistsWithSlug(string $slug): bool
    {
        $classModel = app()->make($this->slugConfig['model']);

        return (bool) $classModel::where('name', $slug)
            ->where('id', '<>', optional($this->currentSlugModel)->id)
            ->withoutGlobalScopes()
            ->first();
    }
}
