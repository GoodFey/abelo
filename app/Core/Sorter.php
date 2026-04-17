<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Sorter class - handles data sorting
 *
 * Usage:
 *   $sorter = new Sorter($items, 'name', 'asc');
 *   $sorted = $sorter->sort();
 *
 *   In controller:
 *   $sortBy = $request->query('sort', 'created_at');
 *   $sortDir = $request->query('dir', 'desc');
 *   $sorter = new Sorter($items, $sortBy, $sortDir);
 *
 *   In view:
 *   <a href="?sort=name&dir=asc">Имя</a>
 *   <a href="?sort=created_at&dir=desc">Дата</a>
 */
class Sorter
{
    private array $items;
    private string $sortBy;
    private string $sortDir;
    private array $validDirections = ['asc', 'desc'];
    private array $sortedItems;

    public function __construct(array $items, string $sortBy = 'id', string $sortDir = 'asc')
    {
        $this->items = $items;
        $this->sortBy = $sortBy;
        $this->sortDir = in_array(strtolower($sortDir), $this->validDirections)
            ? strtolower($sortDir)
            : 'asc';

        $this->sortedItems = $this->sort();
    }

    /**
     * Sort items
     */
    private function sort(): array
    {
        if (empty($this->items)) {
            return [];
        }

        $items = $this->items;

        // Determine if we're sorting objects or arrays
        $isObject = is_object($items[0]);

        usort($items, function ($a, $b) use ($isObject) {
            // Get values
            if ($isObject) {
                $aVal = $this->getObjectValue($a, $this->sortBy);
                $bVal = $this->getObjectValue($b, $this->sortBy);
            } else {
                $aVal = $a[$this->sortBy] ?? null;
                $bVal = $b[$this->sortBy] ?? null;
            }

            // Compare
            $result = $this->compareValues($aVal, $bVal);

            // Apply direction
            return $this->sortDir === 'desc' ? -$result : $result;
        });

        return $items;
    }

    /**
     * Get value from object
     */
    private function getObjectValue(object $obj, string $property): mixed
    {
        if (property_exists($obj, $property)) {
            return $obj->$property;
        }

        // Try getter method
        $getter = 'get' . ucfirst($property);
        if (method_exists($obj, $getter)) {
            return $obj->$getter();
        }

        return null;
    }

    /**
     * Compare two values
     */
    private function compareValues(mixed $a, mixed $b): int
    {
        // Handle nulls
        if ($a === null && $b === null) {
            return 0;
        }
        if ($a === null) {
            return 1;
        }
        if ($b === null) {
            return -1;
        }

        // Numeric comparison
        if (is_numeric($a) && is_numeric($b)) {
            $a = (float)$a;
            $b = (float)$b;

            if ($a < $b) {
                return -1;
            }
            if ($a > $b) {
                return 1;
            }
            return 0;
        }

        // String comparison
        $a = (string)$a;
        $b = (string)$b;
        return strcasecmp($a, $b);
    }

    /**
     * Get sorted items
     */
    public function getSorted(): array
    {
        return $this->sortedItems;
    }

    /**
     * Get current sort field
     */
    public function getSortBy(): string
    {
        return $this->sortBy;
    }

    /**
     * Get current sort direction
     */
    public function getSortDir(): string
    {
        return $this->sortDir;
    }

    /**
     * Get opposite direction (for toggle)
     */
    public function getOppositeDir(): string
    {
        return $this->sortDir === 'asc' ? 'desc' : 'asc';
    }

    /**
     * Get URL parameter for toggling sort
     */
    public function getToggleSortUrl(string $field, string $baseUrl = ''): string
    {
        $dir = $this->sortBy === $field ? $this->getOppositeDir() : 'asc';
        return $baseUrl . '?sort=' . $field . '&dir=' . $dir;
    }

    /**
     * Get sort info as array
     */
    public function getInfo(): array
    {
        return [
            'items' => $this->sortedItems,
            'sortBy' => $this->sortBy,
            'sortDir' => $this->sortDir,
            'opposite' => $this->getOppositeDir(),
        ];
    }

    /**
     * Check if sorting by field
     */
    public function isSortingBy(string $field): bool
    {
        return $this->sortBy === $field;
    }

    /**
     * Get sort indicator for column header
     */
    public function getSortIndicator(string $field): string
    {
        if (!$this->isSortingBy($field)) {
            return '';
        }

        return $this->sortDir === 'asc' ? ' ↑' : ' ↓';
    }

    /**
     * Multi-field sort (advanced)
     */
    public function sortBy(string $field, string $direction = 'asc'): self
    {
        $this->sortBy = $field;
        $this->sortDir = in_array(strtolower($direction), $this->validDirections)
            ? strtolower($direction)
            : 'asc';

        $this->sortedItems = $this->sort();

        return $this;
    }

    /**
     * Filter and sort chain
     */
    public function andSort(string $field, string $direction = 'asc'): self
    {
        return $this->sortBy($field, $direction);
    }

    /**
     * To array for templates
     */
    public function toArray(): array
    {
        return $this->getInfo();
    }
}
