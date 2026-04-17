<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Paginator class - handles pagination logic
 *
 * Usage:
 *   $paginator = new Paginator($items, $perPage, $currentPage);
 *   $data = $paginator->paginate();
 *
 *   In view:
 *   {$pagination->getItems()}    - items for current page
 *   {$pagination->getTotal()}    - total items
 *   {$pagination->getTotalPages()} - total pages
 *   {$pagination->getCurrentPage()} - current page
 */
class Paginator
{
    private array $items;
    private int $perPage;
    private int $currentPage;
    private int $total;
    private int $totalPages;
    private array $paginatedItems;

    public function __construct(array $items, int $perPage = 10, int $currentPage = 1)
    {
        $this->items = $items;
        $this->perPage = max(1, $perPage);
        $this->currentPage = max(1, $currentPage);
        $this->total = count($items);
        $this->totalPages = (int)ceil($this->total / $this->perPage);

        // Validate current page
        if ($this->currentPage > $this->totalPages && $this->totalPages > 0) {
            $this->currentPage = $this->totalPages;
        }

        $this->paginatedItems = $this->paginate();
    }

    /**
     * Paginate the items
     */
    private function paginate(): array
    {
        if ($this->total === 0) {
            return [];
        }

        $offset = ($this->currentPage - 1) * $this->perPage;
        return array_slice($this->items, $offset, $this->perPage);
    }

    /**
     * Get items for current page
     */
    public function getItems(): array
    {
        return $this->paginatedItems;
    }

    /**
     * Get total number of items
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * Get total number of pages
     */
    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    /**
     * Get current page number
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * Get items per page
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * Check if there's a previous page
     */
    public function hasPreviousPage(): bool
    {
        return $this->currentPage > 1;
    }

    /**
     * Get previous page number
     */
    public function getPreviousPage(): int
    {
        return max(1, $this->currentPage - 1);
    }

    /**
     * Check if there's a next page
     */
    public function hasNextPage(): bool
    {
        return $this->currentPage < $this->totalPages;
    }

    /**
     * Get next page number
     */
    public function getNextPage(): int
    {
        return min($this->totalPages, $this->currentPage + 1);
    }

    /**
     * Get offset for database query
     */
    public function getOffset(): int
    {
        return ($this->currentPage - 1) * $this->perPage;
    }

    /**
     * Get pagination info as array
     */
    public function getInfo(): array
    {
        return [
            'items' => $this->paginatedItems,
            'total' => $this->total,
            'totalPages' => $this->totalPages,
            'currentPage' => $this->currentPage,
            'perPage' => $this->perPage,
            'hasPreviousPage' => $this->hasPreviousPage(),
            'hasNextPage' => $this->hasNextPage(),
            'previousPage' => $this->getPreviousPage(),
            'nextPage' => $this->getNextPage(),
            'offset' => $this->getOffset(),
        ];
    }

    /**
     * Get page numbers for display (with ellipsis)
     * Example: 1, 2, 3, ..., 10, 11, 12
     */
    public function getPageNumbers(int $window = 2): array
    {
        $pages = [];

        // Always include first page
        $pages[] = 1;

        // Add pages around current page
        $start = max(2, $this->currentPage - $window);
        $end = min($this->totalPages - 1, $this->currentPage + $window);

        // Add ellipsis if needed
        if ($start > 2) {
            $pages[] = '...';
        }

        // Add range
        for ($i = $start; $i <= $end; $i++) {
            $pages[] = $i;
        }

        // Add ellipsis if needed
        if ($end < $this->totalPages - 1) {
            $pages[] = '...';
        }

        // Always include last page if more than 1 page
        if ($this->totalPages > 1) {
            $pages[] = $this->totalPages;
        }

        return array_unique($pages);
    }

    /**
     * Check if page number is current
     */
    public function isCurrentPage(int $page): bool
    {
        return $page === $this->currentPage;
    }

    /**
     * Get query string for pagination
     */
    public function getQueryString(string $baseUrl, string $pageParam = 'page'): string
    {
        return $baseUrl . '?' . $pageParam . '=' . $this->currentPage;
    }

    /**
     * To array for Smarty template
     */
    public function toArray(): array
    {
        return $this->getInfo();
    }
}
