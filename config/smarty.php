<?php

declare(strict_types=1);

/**
 * Smarty Template Engine Configuration
 */

use Smarty\Smarty;

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

    return $smarty;
};
