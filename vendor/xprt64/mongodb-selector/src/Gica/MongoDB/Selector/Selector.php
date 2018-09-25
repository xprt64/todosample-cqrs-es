<?php
/******************************************************************************
 * Copyright (c) 2017 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\MongoDB\Selector;


use Gica\Iterator\IteratorTransformer\IteratorMapper;
use Gica\Selector\Filter;
use Gica\Selector\Selectable;
use MongoDB\Collection;

class Selector implements \IteratorAggregate, Selectable
{
    /** @var Filter[] */
    private $filters = [];
    private $skip;
    private $limit;
    private $sort;
    /**
     * @var \MongoDB\Collection
     */
    private $collection;

    static $sequence = 0;

    public $debug = false;

    private $iteratorMapper = null;

    public function __construct(
        \MongoDB\Collection $collection
    )
    {
        $this->collection = $collection;
    }

    /**
     * @inheritdoc
     */
    public function addFilter(Filter $filter, string $filterId = null)
    {
        if (null === $filterId) {
            $filterId = 'unnamed_filter_' . self::$sequence++;
        }

        return $this->mutate(function (self $selector) use ($filter, $filterId) {
            $selector->filters[$filterId] = $filter;
        });
    }

    public function removeFilterById(string $filterId)
    {
        return $this->mutate(function (self $selector) use ($filterId) {
            unset($selector->filters[$filterId]);
        });
    }

    public function skip(int $skip): self
    {
        return $this->mutate(function (self $selector) use ($skip) {
            $selector->skip = $skip;
        });
    }

    public function limit(int $limit): self
    {
        return $this->mutate(function (self $selector) use ($limit) {
            $selector->limit = $limit;
        });
    }

    private function mutate(callable $mutator): self
    {
        $that = clone $this;
        $mutator($that);
        return $that;
    }

    public function sort($field, bool $ascending): self
    {
        return $this->mutate(function (self $selector) use ($field, $ascending) {
            $selector->sort[$field] = ($ascending ? 1 : -1);
        });
    }

    public function clearSort(): self
    {
        return $this->mutate(function (self $selector) {
            $selector->sort = [];
        });
    }

    public function constructQuery(): array
    {
        $query = [];

        foreach ($this->filters as $filter) {
            $query = $filter->applyFilter($query);
        }

        return $query;
    }

    public function find($projection = null)
    {
        $query = $this->constructQuery();
        $options = $this->getFindOptions();
        if ($projection) {
            $options['projection'] = $projection;
        }
        if ($this->debug) {
            echo $this->collection->getCollectionName() . "\n";
            var_dump($query);
            var_dump($options);
            die();
        }
        return $this->collection->find($query, $options);
    }

    public function findOne($projection = null)
    {
        $query = $this->constructQuery();
        $options = $this->getFindOptions();
        if ($projection) {
            $options['projection'] = $projection;
        }
        if ($this->debug) {
            echo $this->collection->getCollectionName() . "\n";
            var_dump($query);
            var_dump($options);
            die();
        }
        return $this->collection->findOne($query, $options);
    }

    public function fetchAsDto(callable $deserializer)
    {
        $cursor = $this->find();

        $toDto = new IteratorMapper($deserializer);

        return $toDto($cursor);
    }

    public function count()
    {
        $query = $this->constructQuery();
        return $this->collection->count($query);
    }

    public function hasDocuments(): bool
    {
        $query = $this->constructQuery();
        return !empty(iterator_to_array($this->collection->find($query, ['projection' => ['_id' => 1], 'limit' => 1, 'returnKey' => true])));
    }

    private function getFindOptions()
    {
        $options = [];

        if ($this->skip > 0) {
            $options['skip'] = $this->skip;
        }

        if ($this->limit > 0) {
            $options['limit'] = $this->limit;
        }

        if ($this->sort) {
            $options['sort'] = $this->sort;
        }

        return $options;
    }

    public function setIteratorMapper(callable $iteratorMapper): self
    {
        return $this->mutate(function (self $selector) use ($iteratorMapper) {
            $selector->iteratorMapper = $iteratorMapper;
        });
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        $cursor = $this->find();
        if (is_callable($this->iteratorMapper)) {
            return ($this->iteratorMapper)($cursor);
        }
        return $cursor;
    }

    /**
     * @param $fieldName
     * @return CountByFieldResult[]|\Iterator
     */
    public function countByField($fieldName)
    {
        $query = $this->constructQuery();

        $mongoStack = [];

        if ($query) {
            $mongoStack[] = [
                '$match' => $query,
            ];
        }

        $mongoStack[] = [
            '$group' => [
                '_id'   => '$' . $fieldName,
                'count' => [
                    '$sum' => 1,
                ],
            ],
        ];

        $toDto = new IteratorMapper(function ($document) {
            return new CountByFieldResult($document['_id'], $document['count']);
        });

        $cursor = $this->collection->aggregate($mongoStack);

        return iterator_to_array($toDto($cursor));
    }

    /**
     * @param $fieldName
     * @param array $projection
     * @return array
     */
    public function countByFieldWithProjection($fieldName, $projection)
    {
        $query = $this->constructQuery();

        $mongoStack = [];

        if ($query) {
            $mongoStack[] = [
                '$match' => $query,
            ];
        }
        if (is_string($fieldName)) {
            $id = '$' . $fieldName;
        } else {
            $id = [];
            foreach ($fieldName as $k) {
                $id[$k] = '$' . $k;
            }
        }

        $group = [
            '_id'   => $id,
            'count' => [
                '$sum' => 1,
            ],
        ];
        foreach ($projection as $k => $v) {
            $group[$k] = ['$first' => '$' . $k];
        }
        $mongoStack[] = [
            '$group' => $group,
        ];

        $mongoStack[] = [
            '$sort' => [
                is_string($fieldName) ? $fieldName : $fieldName[0] => 1,
            ],
        ];

        $cursor = $this->collection->aggregate($mongoStack);
        return iterator_to_array($cursor);
    }

    /**
     * @param $fieldName
     * @param array $projection
     * @return array
     */
    public function sumByFieldWithProjection($groupByFields, $projection, $sumField)
    {
        $query = $this->constructQuery();

        $mongoStack = [];

        if ($query) {
            $mongoStack[] = [
                '$match' => $query,
            ];
        }
        if (is_string($groupByFields)) {
            $id = '$' . $groupByFields;
        } else {
            $id = [];
            foreach ($groupByFields as $k) {
                $id[$k] = '$' . $k;
            }
        }

        $group = [
            '_id' => $id,
            'sum' => [
                '$sum' => '$' . $sumField,
            ],
        ];
        foreach ($projection as $k => $v) {
            $group[$k] = ['$first' => '$' . $k];
        }
        $mongoStack[] = [
            '$group' => $group,
        ];

        $mongoStack[] = [
            '$sort' => [
                is_string($groupByFields) ? $groupByFields : $groupByFields[0] => 1,
            ],
        ];

        $cursor = $this->collection->aggregate($mongoStack);
        return iterator_to_array($cursor);
    }

    /**
     * @param $fieldName
     * @param array $projection
     * @return array
     */
    public function avgByFieldWithProjection($groupByFields, $projection, $sumField)
    {
        $query = $this->constructQuery();

        $mongoStack = [];

        if ($query) {
            $mongoStack[] = [
                '$match' => $query,
            ];
        }
        if (is_string($groupByFields)) {
            $id = '$' . $groupByFields;
        } else {
            $id = [];
            foreach ($groupByFields as $k) {
                $id[$k] = '$' . $k;
            }
        }

        $group = [
            '_id' => $id,
            'sum' => [
                '$avg' => '$' . $sumField,
            ],
        ];
        foreach ($projection as $k => $v) {
            $group[$k] = ['$first' => '$' . $k];
        }
        $mongoStack[] = [
            '$group' => $group,
        ];

        $mongoStack[] = [
            '$sort' => [
                is_string($groupByFields) ? $groupByFields : $groupByFields[0] => 1,
            ],
        ];

        $cursor = $this->collection->aggregate($mongoStack);
        return iterator_to_array($cursor);
    }

    /**
     * @param $fieldName
     * @return CountByFieldResult[]
     */
    public function countByArrayField($fieldName)
    {
        $query = $this->constructQuery();

        $mongoStack = [];

        if ($query) {
            $mongoStack[] = [
                '$match' => $query,
            ];
        }
        $mongoStack[] = [
            '$unwind' => '$' . $fieldName,
        ];

        $mongoStack[] = [
            '$group' => [
                '_id'   => '$' . $fieldName,
                'count' => [
                    '$sum' => 1,
                ],
            ],
        ];

        $toDto = new IteratorMapper(function ($document) {
            return new CountByFieldResult($document['_id'], $document['count']);
        });

        $cursor = $this->collection->aggregate($mongoStack);

        return iterator_to_array($toDto($cursor));
    }


    /**
     * @param $fieldName
     * @return float
     */
    public function sumField($fieldName)
    {
        $query = $this->constructQuery();

        $mongoStack = [];

        if ($query) {
            $mongoStack[] = [
                '$match' => $query,
            ];
        }

        $mongoStack[] = [
            '$group' => [
                '_id'    => null,
                'result' => [
                    '$sum' => '$' . $fieldName,
                ],
            ],
        ];

        $toDto = new IteratorMapper(function ($document) {
            return $document['result'];
        });

        $cursor = $this->collection->aggregate($mongoStack);

        return iterator_to_array($toDto($cursor))[0];
    }

    public function extractDistinctNestedField($fieldPath, array $distinctFields, $limit = null, $skip = null, $sortBy = null, $sortAscending = true)
    {
        $query = $this->constructQuery();

        $mongoStack = [
            [
                '$match' => $query,
            ],
        ];

        $fields = explode('.', $fieldPath);

        foreach ($fields as $field) {
            $mongoStack[] = [
                '$unwind' => '$' . $field,
            ];
        }

        $group = [
            'count' => [
                '$sum' => 1,
            ],
        ];

        foreach ($distinctFields as $field) {
            $group['_id'][$this->escapeFieldName($field)] = '$' . $fieldPath . '.' . $field;
        }

        $mongoStack[] = [
            '$group' => $group,
        ];

        if ($sortBy) {
            $sortByEscaped = $this->escapeFieldName($sortBy);
            $mongoStack[] = [
                '$sort' => ['_id.' . $sortByEscaped => $sortAscending ? 1 : -1],
            ];
        }

        if ($skip > 0) {
            $mongoStack[] = [
                '$skip' => $skip,
            ];
        }

        if ($limit > 0) {
            $mongoStack[] = [
                '$limit' => $limit,
            ];
        }

//        var_dump($mongoStack);
//        die();

        $unEscaper = new IteratorMapper(function ($document) {
            $result = [];
            foreach ($document as $k => $v) {
                $result[$this->unEscapeFieldName($k)] = $v;
            }
            return $result;
        });

        $toDto = new IteratorMapper(function ($document) {
            $result = $document['_id'];
            $result['count'] = $document['count'];
            return $result;
        });

        $cursor = $this->collection->aggregate($mongoStack);

        return $unEscaper($toDto($cursor));
    }

    public function getDistinctNestedFieldCount($fieldPath, array $distinctFields)
    {
        $query = $this->constructQuery();

        $mongoStack = [
        ];

        if ($query) {
            $mongoStack[] = [
                '$match' => $query,
            ];
        }

        $fields = explode('.', $fieldPath);

        foreach ($fields as $field) {
            $mongoStack[] = [
                '$unwind' => '$' . $field,
            ];
        }

        $group = [
        ];

        foreach ($distinctFields as $field) {
            $group['_id'][$field] = '$' . $fieldPath . '.' . $field;
        }

        $mongoStack[] = [
            '$group' => $group,
        ];

        $mongoStack[] = [
            '$group' => [
                '_id'   => null,
                'count' => ['$sum' => 1],
            ],
        ];

        $cursor = $this->collection->aggregate($mongoStack);

        $result = iterator_to_array($cursor);

        return reset($result)['count'];
    }

    private function escapeFieldName($field)
    {
        return str_replace('.', '____', $field);
    }

    private function unEscapeFieldName($field)
    {
        return str_replace('____', '.', $field);
    }

    public function withCollection(Collection $collection): self
    {
        $other = clone $this;
        $other->collection = $collection;
        return $other;
    }

    public function fetchIds(string $theClassOfTheId)
    {
        $cursor = $this->find(['_id' => true]);
        $dto = new IteratorMapper(function($document) use ($theClassOfTheId){
            return \call_user_func([$theClassOfTheId, 'fromString'], (string)$document['_id']);
        });
        return $dto($cursor);
    }

    public function fetchAsArray():array
    {
        return iterator_to_array($this->getIterator(), false);
    }
}