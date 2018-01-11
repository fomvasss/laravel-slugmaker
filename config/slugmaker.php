<?php

return [

    'model' => \Fomvasss\SlugMaker\Models\Slug::class,

    'maximum_length' => 190,

    'generate_on_create' => true,

    'generate_on_update' => true,

    'separator' => '-',

    'default_source_fields' => '',

    'prefix' => '',

    'suffix' => '',
];
