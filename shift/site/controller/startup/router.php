<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Startup;

use Shift\System\Core\Mvc;

class Router extends Mvc\Controller
{
    public function index()
    {
        // Register URL rewriter
        if ($this->config->get('config_seo_url')) {
            $this->router->addUrlRewrite($this);
        }

        $this->resolveAlias();
    }

    protected function resolveAlias()
    {
        $alias = [];

        if ($this->request->has('query._route_')) {
            $parts = array_filter(explode('/', $this->request->getString('query._route_')));

            foreach ($parts as $part) {
                // TODO: check site_id, language_id
                $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `alias` = ?s", [$part]);

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
                $this->request->set('query.route', $this->config->get('root.app_error'));
            }
        }
    }

    public function urlAlias(string $route, string $args = '', int $language_id = 0): string
    {
        $language_id = $language_id ?: $this->config->getInt('env.language_id');

        parse_str($args, $urlParams);

        $alias     = '';
        $paramList = [ // TODO: config(system.alias_*)
            'distinct' => ['information_id'],
            'multi'    => [],
        ];

        foreach ($urlParams as $param => $value) {
            if (in_array($param, $paramList['distinct'])) {
                $query = $this->db->get("SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE `language_id` = " . $language_id . " AND `param` = ?s AND `value` = ?s", [$param, $value]);

                if ($query->num_rows && $query->row['alias']) {
                    $alias .= '/' . $query->row['alias'];

                    unset($urlParams[$param]);
                }
            } elseif (in_array($param, $paramList['multi'])) {
                // TODO: content/category&category_id=x_y_z
            }
        }

        if (!$alias) {
            $query = $this->db->get("SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE `language_id` = " . $language_id . " AND `route` = ?s AND `param` = ''", [$route]);

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
