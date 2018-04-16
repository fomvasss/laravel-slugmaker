# Generate slugs in a separate table when saving Eloquent models

[![Latest Version on Packagist](https://img.shields.io/packagist/v/fomvasss/laravel-slugmaker.svg?style=flat-square)](https://packagist.org/packages/fomvasss/laravel-slugmaker)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Quality Score](https://img.shields.io/scrutinizer/g/fomvasss/laravel-slugmaker.svg?style=flat-square)](https://scrutinizer-ci.com/g/fomvasss/laravel-slugmaker)
[![StyleCI](https://styleci.io/repos/112867240/shield?branch=master)](https://styleci.io/repos/112867240)
[![Total Downloads](https://img.shields.io/packagist/dt/fomvasss/laravel-slugmaker.svg?style=flat-square)](https://packagist.org/packages/fomvasss/laravel-slugmaker)

This package provides a trait that will generate in a separate table a unique slug when saving any Eloquent model. 

The slugs are generated with Laravels `str_slug` method, whereby spaces are converted to '-'.

## Installation

You can install the package via composer:
``` bash
composer require fomvasss/laravel-slugmaker
```
---
! For Laravel < v5.5

Add the ServiceProvider to the providers array in config/app.php:

```bash
Fomvasss\SlugMaker\SlugMakerServiceProvider::class,
```
---
Publish config file:
```bash
php artisan vendor:publish --tag=slugmaker-config
```

Publish the migration file:
```bash
php artisan vendor:publish --tag=slugmaker-migrations
```
and run the migration:
```bash
php artisan migrate
```

## Usage

### Configure

! Pay attention! Your model should not have a field named "slug"

Your Eloquent models should use the `Fomvasss\SlugMaker\ModelHasSlug` trait.

The trait contains an method `getSlugSourceFields()` that you must implement yourself. 

Also the trait contains public method `slug()` for relations your item-model with item-slug:

```php
public function slug()
{
    return $this->morphOne(\Fomvasss\SlugMaker\Models\Slug::class, 'slugable');
}
```

Here's an example of how to implement the trait:

```php
<?php

namespace App\Models;

use Fomvasss\SlugMaker\ModelHasSlug;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use ModelHasSlug;
    
    protected $with = ['slug'];
    
    /**
     * Get the default fields for generating the slug.
     */
    public function getSlugSourceFields()
    {
        return ['id', 'name', 'created_at']; // "124-article-test-slug-2017-12-26-135705"
    }
}
```

### Get & make slugs

You can to get slug with relation-method `slug()`:

```php
$article = Article::find(13);
$slug = $article->slug->name;
```

Or use next scope-methods in your models:
- `getSlugName()`
- `findBySlug($slug)`
- `findOrFailBySlug($slug)`
- `getBySlugs(array $slugs)`
- `getArrayIdsBySlugs(array $slugs)`
- `makeSlug(string $str = '')`

For example:

```php
$slug = Article::find(13)->getSlugName();
$slug = Article::findBySlug('my-slug');
$slug = Article::getBySlugs(['my-slug-1', 'my-slug-2']);
$slug = Article::getArrayIdsBySlugs(['my-slug-1', 'my-slug-2']);
```

#### Usage function helpers:

- `slug_get($slug)`
- `slug_get_models($slugs, $class = null)`
- `slug_get_id($slug, $class = null)`
- `slug_get_ids($slugs, $class = null)`
- `slug_get_grouped_class($attributes)`
- `slug_make($model, $slug = '')` // if empty $slug - generate with `getSlugSourceFields(): array`
- `slug_delete($model)`


#### Usage class SlugHelper:

```php
$helper = new Fomvasss\SlugMaker\SlugHelper();
$helper->getModel($slug, $modelClass = null);
$helper->getModels(array $slugs, $modelClass = null);
$helper->getId($slug, $modelClass = null);
$helper->getIds(array $slugs, $modelClass = null);
$helper->getIdsGroupedByClass(array $attributes);
$helper->makeForModel($model, $slug = '');
$helper->deleteByModel($model);
```

The `getIds()` return the array:
```php
[
    1, 2, 8, 3
]
```
```php
$attributes = [
    App\Models\Article::class => ['slug-article-name',],
    App\Models\Page::class => ['slug-page-name-1', 'slug-page-name-2'],
    App\Models\Product::class => 'slug-product-name',
    ];
```
The `getIdsGroupedByClass($attributes)` return the array:
```php
[
    'App\Models\Article' => [1],
    'App\Models\Page' => [2, 8],
    'App\Models\Product' => [3],
]
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.


## Security

If you discover any security related issues, please email fomvasss@gmail.com instead of using the issue tracker.


## Credits

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
