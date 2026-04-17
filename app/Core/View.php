<?php

declare(strict_types=1);

namespace App\Core;

use Smarty\Smarty;

/**
 * View class - wrapper for Smarty template engine
 */
class View
{
    private Smarty $smarty;
    private array $data = [];

    public function __construct(Smarty $smarty)
    {
        $this->smarty = $smarty;
    }

    /**
     * Assign a variable to the template
     */
    public function assign(string $key, mixed $value): self
    {
        $this->data[$key] = $value;
        $this->smarty->assign($key, $value);
        return $this;
    }

    /**
     * Assign multiple variables
     */
    public function assignMultiple(array $data): self
    {
        foreach ($data as $key => $value) {
            $this->assign($key, $value);
        }
        return $this;
    }

    /**
     * Get an assigned variable
     */
    public function get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    /**
     * Get all assigned variables
     */
    public function getAll(): array
    {
        return $this->data;
    }

    /**
     * Render a template
     */
    public function render(string $template): string
    {
        return $this->smarty->fetch($template);
    }

    /**
     * Check if template file exists
     */
    public function exists(string $template): bool
    {
        return $this->smarty->templateExists($template);
    }

    /**
     * Clear all assigned variables
     */
    public function clear(): self
    {
        $this->data = [];
        $this->smarty->clearAllAssign();
        return $this;
    }

    /**
     * Clear a specific assigned variable
     */
    public function clearVariable(string $key): self
    {
        unset($this->data[$key]);
        $this->smarty->clearAssign($key);
        return $this;
    }

    /**
     * Register a custom Smarty function
     */
    public function registerFunction(string $name, callable $callback): self
    {
        $this->smarty->registerPlugin('function', $name, $callback);
        return $this;
    }

    /**
     * Register a custom Smarty filter
     */
    public function registerFilter(string $type, callable $callback): self
    {
        $this->smarty->registerPlugin('filter', $type, $callback);
        return $this;
    }

    /**
     * Get the underlying Smarty instance
     */
    public function getSmarty(): Smarty
    {
        return $this->smarty;
    }
}
