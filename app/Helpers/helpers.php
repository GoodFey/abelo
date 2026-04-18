<?php

declare(strict_types=1);

/**
 * Dump and die - like Laravel's dd()
 */
function dd(...$vars): void
{
    foreach ($vars as $var) {
        echo '<pre style="background: #f5f5f5; padding: 15px; border: 1px solid #ddd; margin: 10px 0; font-family: monospace; overflow-x: auto;">';
        var_dump($var);
        echo '</pre>';
    }
    die();
}

/**
 * Dump variable - like Laravel's dump()
 */
function dump(...$vars): void
{
    foreach ($vars as $var) {
        echo '<pre style="background: #f5f5f5; padding: 15px; border: 1px solid #ddd; margin: 10px 0; font-family: monospace; overflow-x: auto;">';
        var_dump($var);
        echo '</pre>';
    }
}

