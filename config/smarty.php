<?php

declare(strict_types=1);

use App\Core\ImageCache;
use Parsedown;

/**
 * Smarty Template Engine Configuration
 */

return function(): Smarty {
    $smarty = new Smarty();

    // Основные пути
    $basePath = dirname(__DIR__);
    $smarty->setTemplateDir($basePath . '/resources/templates');
    $smarty->setCompileDir($basePath . '/storage/cache/smarty_compile');
    $smarty->setCacheDir($basePath . '/storage/cache/smarty_cache');

    // Конфигурация для разработки
    if (getenv('APP_DEBUG') === 'true') {
        $smarty->setForceCompile(true);
        $smarty->setCaching(Smarty::CACHING_OFF);
    } else {
        $smarty->setForceCompile(false);
        $smarty->setCaching(Smarty::CACHING_LIFETIME_SAVED);
    }

    // Register image thumbnail filter
    $imageCache = new ImageCache();
    $smarty->registerPlugin('modifier', 'thumb', function(
        string $path,
        int $width = 300,
        int $height = 200
    ) use ($imageCache): string {
        if (empty($path)) {
            return '';
        }
        return $imageCache->get($path, $width, $height);
    });

    // Register markdown filter
    $parsedown = new Parsedown();
    $smarty->registerPlugin('modifier', 'markdown', function(string $text) use ($parsedown): string {
        if (empty($text)) {
            return '';
        }
        return $parsedown->text($text);
    });

    return $smarty;
};
