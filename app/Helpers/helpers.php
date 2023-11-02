<?php

use Illuminate\Support\Str;

if (!function_exists('formatSlug')) {
    function formatSlug($slug, $postType = 'news')
    {
        if (Str::contains($slug, ['http://', 'https://'])) {
            return $slug;
        }

        return '/' . $postType . '/' . $slug;
    }
}
