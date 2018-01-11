<?php

namespace Fomvasss\SlugMaker;

/**
 * Class SlugGenerator
 *
 * @package \Fomvasss\SlugGenerator
 */
class SlugHelper
{
    use SlugGenerator;

    protected $slugModelClass;

    protected $currentSlugModel = null;

    protected $slugConfig = [
        'separator' => '-',
        'prefix' => '',
        'suffix' => '',
        'maximum_length' => 190,
        'model' => \Fomvasss\SlugGenerator\Models\Slug::class,
    ];

    /**
     * SlugHelper constructor.
     */
    public function __construct()
    {
        //$this->slugConfig = array_merge($this->slugConfig, config('slugmaker', []));
        $this->slugConfig = config('slugmaker');
        $this->slugModelClass = app()->make($this->slugConfig['model']);
    }

    /**
     * @param $slug
     * @param null $modelClass
     * @return null
     */
    public function getModel($slug, $modelClass = null)
    {
        $slug = $this->slugModelClass
            ->byNameByClass($slug, $modelClass)
            ->first();

        return $slug ? $slug->slugable : null;
    }

    /**
     * @param array $slugs
     * @param null $modelClass
     * @return mixed
     */
    public function getModels(array $slugs, $modelClass = null)
    {
        $slugs = $this->slugModelClass
            ->with('slugable')
            ->byNamesByClass($slugs, $modelClass)
            ->get();

        return $slugs->map(function ($item) {
            return $item->slugable;
        });
    }

    /**
     * @param $slug
     * @param null $modelClass
     * @return null
     */
    public function getId($slug, $modelClass = null)
    {
        $model = $this->getModel($slug, $modelClass);
        return  $model ? $model->id : null;
    }

    /**
     * @param array $slugs
     * @param null $modelClass
     * @return mixed
     */
    public function getIds(array $slugs, $modelClass = null)
    {
        return $this->getModels($slugs, $modelClass)
            ->pluck('id')
            ->toArray();
    }

    /**
     * @param array $attributes
     * @param bool $useId
     * @return array
     */
    public function getIdsGroupedByClass(array $attributes, $useId = false)
    {
        $key = $useId ? 'slugable_id' : 'name';

        $slugs = $this->slugModelClass
            ->byClassesByNames($attributes, $key)
            ->get();

        return $this->groupedByClass($slugs
            ->pluck('slugable_type', 'slugable_id')
            ->toArray());
    }

    /**
     * @param $model
     * @param $slug
     * @return mixed
     */
    public function makeForModel($model, $slug = '')
    {
        $this->currentSlugModel = $model->slug;
        $slug = empty($slug) ? $this->getStrSlugByModelFields($model) : $slug;

        $newSlug = $this->getSlug($slug);

        if ($model->slug) {
            if ($this->slugConfig['generate_on_update']) {
                $model->slug()->update(['name' => $newSlug]);
            }
            return $newSlug;
        }

        if ($this->slugConfig['generate_on_create']) {
            $model->slug()->create(['name' => $newSlug]);
        }

        return $newSlug;
    }

    /**
     * @param $model
     * @return string
     */
    protected function getStrSlugByModelFields($model)
    {
        $str = '';
        foreach ($model->getSlugSourceFields() as $field) {
            $str .= $model->{$field}.'-';
        }

        return trim($str, '-');
    }

    /**
     * @param $model
     */
    public function deleteByModel($model)
    {
        if ($model->slug) {
            return $model->slug()->delete();
        }

        return;
    }

    /**
     * @param $attributes
     * @return array
     */
    private function groupedByClass($attributes): array
    {
        $res = [];
        foreach ($attributes as $id => $type) {
            $res[$type][] = $id;
        }

        return $res;
    }
}
