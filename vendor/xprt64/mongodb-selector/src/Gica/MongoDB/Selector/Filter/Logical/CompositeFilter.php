<?php


namespace Gica\MongoDB\Selector\Filter\Logical;


use Gica\Selector\Filter;

abstract class CompositeFilter implements Filter
{
    /**
     * @var Filter[]
     */
    private $filters;

    public function __construct(Filter ...$filters)
    {
        $this->filters = $filters;
    }

    public function withAddedFilter(Filter $filter): self
    {
        $other = clone $this;
        $other->filters[] = $filter;
        return $other;
    }

    public function applyFilter(array $fields): array
    {
        if (!isset($fields[$this->getToken()]) || !is_array($fields[$this->getToken()])) {
            $fields[$this->getToken()] = [];
        }

        foreach ($this->filters as $filter) {
            $fields[$this->getToken()][] = $filter->applyFilter([]);
        }

        return $fields;
    }

    abstract protected function getToken(): string;

    public function hasFilters(): bool
    {
        return !empty($this->filters);
    }
}