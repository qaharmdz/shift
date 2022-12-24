<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Startup;

use Shift\System\Mvc;
use Shift\System\Exception;

class Router extends Mvc\Controller
{
    public function index()
    {
        $this->router->addUrlGenerator($this);

        $this->resolveAlias();
    }

    protected function resolveAlias()
    {
        $alias = [];

        if ($this->request->has('query._route_')) {
            $routeAlias = $this->request->getString('query._route_');
            $parts = array_filter(explode('/', $routeAlias));

            foreach ($parts as $part) {
                // TODO: check site_id, language_id
                $query = $this->db->get("SELECT * FROM `" . DB_PREFIX . "route_alias` WHERE `alias` = ?s", [$part]);

                if (!$query->num_rows) {
                    $alias = [];
                    break;
                }

                $alias = $query->row;
                if (false !== $index = array_search($this->config->get('env.language_id'), array_column($query->rows, 'language_id'))) {
                    $alias = $query->rows[$index];
                }

                $this->request->set('query.route', $alias['route']);

                if ($alias['param']) {
                    $this->request->set('query.' . $alias['param'], $alias['value']);
                }

                // TODO: change language per $alias language_id
            }

            if (!$alias) {
                throw new Exception\NotFoundHttpException(sprintf('Unable to resolve URL alias "%s".', $routeAlias));
            }
        }
    }

    public function generateAlias(string $route, string $args = '', int $language_id = 0): string
    {
        $language_id = $language_id ?: $this->config->getInt('env.language_id');

        parse_str($args, $urlParams);

        $alias     = '';
        $paramList = [
            'distinct' => $this->config->get('system.alias_distinct'),
            'multi'    => $this->config->get('system.alias_multi'),
        ];

        foreach ($urlParams as $param => $value) {
            if (in_array($param, $paramList['distinct'])) {
                $query = $this->db->get(
                    "SELECT * FROM `" . DB_PREFIX . "route_alias` WHERE `language_id` = ?i AND `param` = ?s AND `value` = ?s",
                    [$language_id, $param, $value]
                );

                if ($query->num_rows && $query->row['alias']) {
                    $alias .= '/' . $query->row['alias'];

                    unset($urlParams[$param]);
                }
            } elseif (in_array($param, $paramList['multi'])) {
                // TODO: content/category&category_id=x_y_z
            }
        }

        if (!$alias) {
            $query = $this->db->get(
                "SELECT * FROM `" . DB_PREFIX . "route_alias` WHERE `language_id` = ?i AND `route` = ?s AND `param` = ''",
                [$language_id, $route]
            );

            if ($query->num_rows && $query->row['alias']) {
                $alias .= '/' . rtrim($query->row['alias'], '/');
            }
        }

        if ($alias) {
            $query = '';

            if ($urlParams) {
                foreach ($urlParams as $param => $value) {
                    $query .= '&' . rawurlencode((string)$param) . '=' . rawurlencode((string)$value);
                }

                if ($query) {
                    $query = '?' . trim($query, '&');
                }
            }

            $alias = $this->config->get('env.url_app') . ltrim($alias, '/') . $query;
        }

        return $alias;
    }
}
