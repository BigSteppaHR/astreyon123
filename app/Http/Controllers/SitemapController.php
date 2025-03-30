<?php

namespace App\Http\Controllers;

use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = Sitemap::create();

        // Main URLs
        $mainUrls = [
            '/' => 1.0,
            '/blog' => 0.9,
        ];

        foreach ($mainUrls as $url => $priority) {
            $sitemap->add(
                Url::create($url)
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority($priority)
            );
        }

        // Language versions
        $languages = ['en', 'de', 'fr', 'it', 'es'];
        
        foreach ($languages as $lang) {
            // Main language page
            $sitemap->add(
                Url::create("/{$lang}")
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(0.8)
            );

            // Blog in each language
            $sitemap->add(
                Url::create("/{$lang}/blog")
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(0.8)
            );
        }

        return $sitemap->render();
    }
}
