<?php

namespace EcomDemo;

class PaginationFilters
{
    /**
     * @var int
     */
    protected int $page = 1;

    /**
     * @var int
     */
    protected int $limit = 10;

    /**
     * @var string
     */
    protected string $sortBy = 'id';

    /**
     * @var bool
     */
    protected bool $desc = false;

    /**
     * @param int $page
     * @param int $limit
     */
    public function __construct(int $page, int $limit)
    {
        $this->page  = $page;
        $this->limit = $limit;
    }

    /**
     * @param string $column
     * @param bool $desc
     *
     * @return $this
     */
    public function sortBy(string $column, bool $desc = true): self
    {
        $this->sortBy = $column;
        $this->desc   = $desc;

        return $this;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return string
     */
    public function getSortBy(): string
    {
        return $this->sortBy;
    }

    /**
     * @return string
     */
    public function getSortDirection(): string
    {
        return $this->desc ? 'desc' : 'asc';
    }
}
