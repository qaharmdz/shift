<?php

declare(strict_types=1);

namespace Shift\System\Helper;

/**
 * DataTables query builder.
 */
class DataTables
{
    protected $data = [];
    protected $charNot = '~';
    protected $charSeparator = '~';
    protected static $instance = null;

    /**
     * @param  array  $params       DataTables request parameter
     * @param  array  $filterMap
     * @param  array  $dateColumns  Date datatype DB table column
     *
     * @return array
     */
    public static function parse(array $params, array $filterMap, array $dateColumns = []): array
    {
        self::$instance = new self();

        $dateColumns = array_unique(array_merge(
            ['created', 'updated', 'publish', 'unpublish'],
            $dateColumns
        ));

        return self::$instance->request($params)->query($filterMap, $dateColumns)->data;
    }

    /**
     * Extract DataTables request parameter
     *
     * @param  array  $params   Request parameter
     *
     * @return \DataTables
     */
    protected function request(array $params)
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

        if ($params['search']['value']) {
            $data['search']['all'] = trim($params['search']['value']);
        }

        foreach ($params['columns'] as $key => $column) {
            if (filter_var($column['searchable'], FILTER_VALIDATE_BOOLEAN)) {
                $data['columns'][$key] = $column['data'];

                $columnSearch = [
                    'keyword' => trim($column['search']['value']),
                    'type'    => 'string',
                    'mode'    => null,
                    'negate'  => false
                ];

                if (in_array($columnSearch['keyword'], ['', $this->charNot, $this->charSeparator])) {
                    continue;
                }
                if (isset($column['search']['type']) && in_array($column['search']['type'], ['text', 'string', 'number', 'date'])) {
                    $columnSearch['type'] = $column['search']['type'];
                }

                // Default mode default by type
                if (!$columnSearch['mode']) {
                    switch ($columnSearch['type']) {
                        case 'text': // type string, mode match
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

                if (str_contains($columnSearch['keyword'], $this->charSeparator) && in_array($columnSearch['type'], ['number', 'date'])) {
                    $range = array_map('trim', explode($this->charSeparator, $columnSearch['keyword']));
                    $columnSearch['mode'] = 'range';

                    if ($columnSearch['type'] == 'number') {
                        $columnSearch['keyword'] = [
                            'min' => isset($range[0]) && $range[0] != '' ? $range[0] : null,
                            'max' => isset($range[1]) && $range[1] != '' ? $range[1] : null,
                        ];
                    } elseif ($columnSearch['type'] == 'date') {
                        $columnSearch['keyword'] = [
                            'from' => !empty($range[0]) ? Date::toUtc($range[0] . ' 00:00:00', $params['timezone']) : null,
                            'to'   => !empty($range[1]) ? Date::toUtc($range[1] . ' 23:59:59', $params['timezone']) : null,
                        ];
                    }
                }

                if (!is_array($columnSearch['keyword']) && str_starts_with($columnSearch['keyword'], $this->charNot)) {
                    $columnSearch['keyword'] = trim(substr($columnSearch['keyword'], 1));
                    $columnSearch['negate']  = true;
                }

                $data['search']['columns'][$column['data']] = $columnSearch;
            }
        }

        foreach ($params['order'] as $order) {
            $data['order'][$data['columns'][(int)$order['column']]] = $order['dir'] == 'asc' ? 'ASC' : 'DESC';
        }

        $data['limit'] = [
            'offset' => (int)$params['start'],
            'length' => (int)$params['length'],
        ];

        $this->data['params'] = $data;

        return self::$instance;
    }

    /**
     * SQL query builder
     *
     * @param  array  $filterMap
     * @param  array  $dateColumns  Date datatype DB table column
     *
     * @return \DataTables
     */
    protected function query(array $filterMap, array $dateColumns = [])
    {
        $data = array_merge(
            $this->data,
            [
                'query' => [
                    'where' => '',
                    'order' => '',
                    'limit' => ',',
                    'params' => [],
                ],
            ]
        );

        if ($data['params']) {
            $search = [];
            $params = [];

            if ($searchAllKeyword = $data['params']['search']['all']) {
                $search_all = [];
                foreach ($filterMap as $key => $dbColumn) {
                    if (!in_array($key, $dateColumns)) {
                        $search_all[$dbColumn] = $dbColumn . ' LIKE :search_all?s';
                    }
                }

                if ($search_all) {
                    $search[] = '(' . implode(' OR ', $search_all) . ')';
                    $params['search_all'] = '%' . $searchAllKeyword . '%';
                }
            }

            if ($data['params']['search']['columns']) {
                foreach ($data['params']['search']['columns'] as $key => $filter) {
                    switch ($filter['mode']) {
                        case 'match':
                            $search[] = $filterMap[$key] . ' ' . ($filter['negate'] ? '!=' : '=') . ' :' . $key . '?s';
                            $params[$key] = $filter['keyword'];
                            break;

                        case 'range':
                            if ($filter['type'] == 'number') {
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

                            if ($filter['type'] == 'date' && in_array($key, $dateColumns)) {
                                if (!is_null($filter['keyword']['from']) && is_null($filter['keyword']['to'])) {
                                    $search[] = $filterMap[$key] . ' >= :' . $key . '?s';
                                    $params[$key] = $filter['keyword']['from'];
                                }
                                if (is_null($filter['keyword']['from']) && !is_null($filter['keyword']['to'])) {
                                    $search[] = $filterMap[$key] . ' <= :' . $key . '?s';
                                    $params[$key] = $filter['keyword']['to'];
                                }
                                if (!is_null($filter['keyword']['from']) && !is_null($filter['keyword']['to'])) {
                                    $search[] = '(' . $filterMap[$key] . ' BETWEEN :' . $key . '_min?s AND :' . $key . '_max?s)';
                                    $params[$key . '_min'] = $filter['keyword']['from'];
                                    $params[$key . '_max'] = $filter['keyword']['to'];
                                }
                            }
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
                $data['query']['params'] = $params;
            }
        }

        if ($data['params']['order']) {
            $orders = [];

            foreach ($data['params']['order'] as $key => $value) {
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
        $data['query']['params']['_offset'] = (int)$data['params']['limit']['offset'];
        $data['query']['params']['_limit']  = (int)$data['params']['limit']['length'];

        $this->data = $data;

        return self::$instance;
    }
}
