<?php

if (! function_exists('slug_get_model')) {
    function slug_get_model($slug, $class = null)
    {
        return app(\Fomvasss\SlugMaker\SlugHelper::class)->getModel($slug, $class);
    }
}

if (! function_exists('slug_get_models')) {
    function slug_get_models($slugs, $class = null)
    {
        return app(\Fomvasss\SlugMaker\SlugHelper::class)->getModels($slugs, $class);
    }
}

if (! function_exists('slug_get_id')) {
    function slug_get_id($slug, $class = null)
    {
        return app(\Fomvasss\SlugMaker\SlugHelper::class)->getId($slug, $class);
    }
}

if (! function_exists('slug_get_ids')) {
    function slug_get_ids($slugs, $class = null)
    {
        return app(\Fomvasss\SlugMaker\SlugHelper::class)->getIds($slugs, $class);
    }
}

if (! function_exists('slug_get_grouped_class')) {
    function slug_get_grouped_class($attributes, $useId = false)
    {
        return app(\Fomvasss\SlugMaker\SlugHelper::class)->getIdsGroupedByClass($attributes, $useId);
    }
}

if (! function_exists('slug_make')) {
    function slug_make($model, $slug)
    {
        return app(\Fomvasss\SlugMaker\SlugHelper::class)->makeForModel($model, $slug);
    }
}

if (! function_exists('slug_delete')) {
    function slug_delete($model)
    {
        return app(\Fomvasss\SlugMaker\SlugHelper::class)->deleteByModel($model);
    }
}
