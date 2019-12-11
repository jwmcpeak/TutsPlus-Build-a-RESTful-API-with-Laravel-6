<?php

namespace App\Services\v1;


class ResourceService {
    protected $includes = [];
    protected $queryFields = [];
    protected $sortFields = [];

    protected function buildParameters($input) {
        $limit = $input['limit'] ?? 25;
        $offset = $input['offset'] ?? 0;

        if ($limit > 50) {
            $limit = 50;
        }

        // TODO: defaults for limit, offset, and max limit

        return [
            'limit' => $limit,
            'offset' => $offset,
            'sort' => $this->buildSort($input['sort'] ?? ''),
            'where' => $this->buildWhere($input['where'] ?? ''),
            'include' => $this->buildWith($input['include'] ?? '')
        ];
    }

    protected function buildWith($rawInclude) {
        if (empty($rawInclude)) {
            return [];
        }

        $includeable = $this->includes;
        $parts = explode(',', strtolower($rawInclude));

        return array_intersect($includeable, $parts);
    }

    protected function buildWhere($rawWhere) {
        if (empty($rawWhere)) {
            return [];
        }

        $queryable = collect($this->queryFields);

        $operators = collect([
            'eq' => '=',
            'ne' => '<>',
            'lt' => '<',
            'lte' => '<=',
            'gt' => '>',
            'gte' => '>=',
        ]);

        //column:operator:value,column2:operator2:value2
        $rawClause = collect(explode(',', strtolower($rawWhere)));
        $clauses = collect([]);

        $rawClause->each(function($item, $key) use($queryable, $operators, $clauses) {
            //column:operator:value
            $parts = explode(':', $item);

            if (count($parts) != 3) {
                return false;
            }

            $field = $queryable->get($parts[0]) ?? '';
            $operator = $operators->get($parts[1]) ?? '';

            if (empty($field) || empty($operator)) {
                return false;
            }

            $clauses->push([
                $field,
                $operator,
                $parts[2]
            ]);

        });

        return $clauses->all();
    }

    protected function buildSort($rawSort) {
        if (empty($rawSort)) {
            return [];
        }

        $orderable = collect($this->sortFields);

        $direction = collect([
            'asc' => 'asc',
            'desc' => 'desc'
        ]);

        $parts = explode(':', strtolower($rawSort));

        $field = $orderable->get($parts[0]);
        $dir = $direction->get($parts[1] ?? '') ?? 'asc';

        return [$field, $dir];

    }
}