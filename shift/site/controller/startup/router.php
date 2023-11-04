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
            $lastPartKey = array_key_last($parts);

            foreach ($parts as $key => $part) {
                $alias = $this->db->get(
                    "SELECT * FROM `" . DB_PREFIX . "route_alias` WHERE `site_id` = ?i AND `alias` = ?s",
                    [$this->config->getInt('env.site_id'), $part]
                )->row;

                if (!$alias) {
                    break;
                }

                $this->request->set('query.route', $alias['route']);

                if ($alias['param']) {
                    $this->request->set('query.' . $alias['param'], $alias['value']);
                }

                // Change language per $alias language_id
                if ($key == $lastPartKey && $alias['language_id'] !== $this->config->get('env.language_id')) {
                    $this->load->model('extension/language');
                    $languages = $this->model_extension_language->getLanguages();

                    if (!empty($languages[$alias['language_id']]['codename'])) {
                        $langCode = $languages[$alias['language_id']]['codename'];

                        $this->session->set('language', $langCode);
                        setcookie('language', $langCode, time() + 60 * 60 * 24 * 30, '/', ini_get('session.cookie_domain'), (bool)ini_get('session.cookie_secure'));

                        $this->config->set('env.language_id', (int)$alias['language_id']);
                        $this->config->set('env.language_code', $langCode);

                        $this->language->set('_param.active', $langCode);
                        $this->language->load($langCode);
                    }
                }
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

        // Base route no args
        $query = $this->db->get(
            "SELECT * FROM `" . DB_PREFIX . "route_alias` WHERE `language_id` = ?i AND `route` = ?s AND `param` = ''",
            [$language_id, $route]
        );

        if (!empty($query->row['alias'])) {
            $alias .= '/' . rtrim($query->row['alias'], '/');
        }

        $paramList = [
            'distinct' => $this->config->get('system.alias_distinct'),
            'multi'    => $this->config->get('system.alias_multi'),
        ];

        // Args URL alias
        foreach ($urlParams as $param => $value) {
            if (in_array($param, $paramList['distinct'])) {
                $query = $this->db->get(
                    "SELECT * FROM `" . DB_PREFIX . "route_alias` WHERE `language_id` = ?i AND `param` = ?s AND `value` = ?s",
                    [$language_id, $param, $value]
                );

                if (!empty($query->row['alias'])) {
                    $alias .= '/' . $query->row['alias'];
                    unset($urlParams[$param]);
                }
            } elseif (in_array($param, $paramList['multi'])) {
                $ids = explode('_', $value);

                foreach ($ids as $id) {
                    $query = $this->db->get(
                        "SELECT * FROM `" . DB_PREFIX . "route_alias` WHERE `language_id` = ?i AND `param` = ?s AND `value` = ?s",
                        [$language_id, $param, $id]
                    );

                    if (!empty($query->row['alias'])) {
                        $alias .= '/' . $query->row['alias'];
                        unset($urlParams[$param]);
                    }
                }
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
