<?php

declare(strict_types=1);

namespace Shift\System\Helper;

class Datatables
{
    protected $params;
    protected $rangeSeparator = '~';
    protected $data = [];

    public function data()
    {
        return $this->data;
    }

    public function pullData()
    {
        $data = $this->data;

        $this->params = [];
        $this->data   = [];

        return $data;
    }

    public function parse(array $params)
    {
        $data = [
            'columns' => [],
            'search'  => [
                'all'     => '',
                'columns' => []
            ],
            'order'   => [],
            'limit'   => [],
        ];

        foreach ($params['columns'] as $key => $column) {
            if (filter_var($column['searchable'], FILTER_VALIDATE_BOOLEAN)) {
                $data['columns'][$key] = $column['data'];

                $columnSearch = [
                    'keyword'  => trim($column['search']['value']),
                    'type'     => 'string',
                    'mode'     => null,
                    'negate'   => false
                ];

                if (in_array($columnSearch['keyword'], ['', '!', $this->rangeSeparator])) {
                    continue;
                }
                if (isset($column['search']['type']) && in_array($column['search']['type'], ['string', 'number', 'date'])) {
                    $columnSearch['type'] = $column['search']['type'];
                }
                if (isset($column['search']['mode']) && in_array($column['search']['mode'], ['match', 'range', 'like'])) {
                    $columnSearch['mode'] = $column['search']['mode'];
                }

                // Default mode default by type
                if (!$columnSearch['mode']) {
                    switch ($columnSearch['type']) {
                        case 'number':
                            $columnSearch['mode'] = 'match';
                            break;

                        case 'date':
                            $columnSearch['mode'] = 'range';
                            break;

                        case 'string':
                        default:
                            $columnSearch['mode'] = 'like';
                            break;
                    }
                }

                if (in_array($columnSearch['mode'], ['match', 'range']) && str_contains($columnSearch['keyword'], $this->rangeSeparator)) {
                    $range = array_map('trim', explode($this->rangeSeparator, $columnSearch['keyword']));
                    $columnSearch['mode'] = 'range';

                    if ($columnSearch['type'] == 'number') {
                        $columnSearch['keyword'] = [
                            'min' => isset($range[0]) ? (int)$range[0] : null,
                            'max' => isset($range[1]) ? (int)$range[1] : null
                        ];
                    } elseif ($columnSearch['type'] == 'date') {
                        // TODO: further test, convert to UTC
                        $columnSearch['keyword'] = [
                            'from' => !empty($range[0]) ? $range[0] : null,
                            'to'   => !empty($range[1]) ? $range[0] : null,
                        ];
                    }
                }

                if (!is_array($columnSearch['keyword']) && str_starts_with($columnSearch['keyword'], '!')) {
                    $columnSearch['keyword']  = trim(substr($columnSearch['keyword'], 1));
                    $columnSearch['negate'] = true;
                }

                $data['search']['columns'][$column['data']] = $columnSearch;
            }
        }

        if ($params['search']['value']) {
            $data['search']['all'] = trim($params['search']['value']);
        }

        foreach ($params['order'] as $order) {
            $data['order'][$data['columns'][(int)$order['column']]] = $order['dir'] == 'asc' ? 'ASC' : 'DESC';
        }

        $data['limit'] = [
            'offset' => (int)$params['start'],
            'length' => (int)$params['length'],
        ];

        $this->data['params'] = $data;

        return $this;
    }

    public function sqlQuery(array $filterMap, array $dateMap = [])
    {
        if (empty($this->data['params'])) {
            return null;
        }

        $dateMap = array_unique(array_merge(['created', 'updated', 'publish', 'unpublish'], $dateMap));
        $data    = [
            'query' => [
                'where' => '',
                'order' => '',
                'limit' => ',',
            ],
            'params' => [],
        ];

        if ($this->data['params']) {
            $search = [];
            $params = [];

            if ($searchAllKeyword = $this->data['params']['search']['all']) {
                $search_all = [];
                foreach ($filterMap as $filterKey => $dbColumn) {
                    if (!in_array($filterKey, $dateMap)) {
                        $search_all[$dbColumn] = $dbColumn . ' LIKE :search_all?s';
                    }
                }

                if ($search_all) {
                    $search[] = '(' . implode(' OR ', $search_all) . ')';
                    $params['search_all'] = '%' . $searchAllKeyword . '%';
                }
            }

            if ($this->data['params']['search']['columns']) {
                foreach ($this->data['params']['search']['columns'] as $key => $filter) {
                    switch ($filter['mode']) {
                        case 'match':
                            $search[] = $filterMap[$key] . ' ' . ($filter['negate'] ? '!=' : '=') . ' :' . $key . '?s';
                            $params[$key] = $filter['keyword'];
                            break;

                        case 'range':
                            if ($filter['type'] = 'number') {
                                if (!is_null($filter['keyword']['min']) && is_null($filter['keyword']['max'])) {
                                    $search[] = $filterMap[$key] . ' >= :' . $key . '?i';
                                    $params[$key] = (int)$filter['keyword']['min'];
                                }
                                if (is_null($filter['keyword']['min']) && !is_null($filter['keyword']['max'])) {
                                    $search[] = $filterMap[$key] . ' <= :' . $key . '?i';
                                    $params[$key] = (int)$filter['keyword']['max'];
                                }
                                if (!is_null($filter['keyword']['min']) && !is_null($filter['keyword']['max'])) {
                                    $search[] = '(' . $filterMap[$key] . ' BETWEEN :' . $key . '_min?i AND :' . $key . '_max?i)';
                                    $params[$key . '_min'] = (int)$filter['keyword']['min'];
                                    $params[$key . '_max'] = (int)$filter['keyword']['max'];
                                }
                            }

                            // TODO: date range
                            break;

                        case 'like':
                        default:
                            $search[] = $filterMap[$key] . ' ' . ($filter['negate'] ? 'NOT LIKE' : 'LIKE') . ' :' . $key . '?s';
                            $params[$key] = '%' . $filter['keyword'] . '%';
                            break;
                    }
                }
            }

            if ($search) {
                $data['query']['where'] = implode(' AND ', $search);
                $data['params'] = $params;
            }
        }

        if ($this->data['params']['order']) {
            $orders = [];

            foreach ($this->data['params']['order'] as $key => $value) {
                if (isset($filterMap[$key])) {
                    $orders[] = $filterMap[$key] . ' ' . $value;
                }
            }

            if ($orders) {
                $data['query']['order'] = implode(', ', $orders);
            }
        }

        // Limit
        $data['query']['limit'] = ':_offset?i, :_limit?i';
        $data['params']['_offset'] = (int)$this->data['params']['limit']['offset'];
        $data['params']['_limit']  = (int)$this->data['params']['limit']['length'];

        $this->data['sql'] = $data;

        return $this;
    }
}
