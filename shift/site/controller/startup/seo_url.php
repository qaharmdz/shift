<?php

declare(strict_types=1);

class ControllerStartupSeoUrl extends Controller
{
    public function index()
    {
        // Add rewrite to url class
        if ($this->config->get('config_seo_url')) {
            $this->url->addRewrite($this);
        }

        // Decode URL
        if ($this->request->has('query._route_')) {
            $parts = explode('/', $this->request->get('query._route_'));

            // remove any empty arrays from trailing
            if (utf8_strlen(end($parts)) == 0) {
                array_pop($parts);
            }

            foreach ($parts as $part) {
                $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = ?s", [$part]);

                if ($query->num_rows) {
                    $url = explode('=', $query->row['query']);

                    if ($url[0] == 'information_id') {
                        $this->request->set('query.information_id', $url[1]);
                    }

                    if ($query->row['query'] && $url[0] != 'information_id') {
                        $this->request->set('query.route', $query->row['query']);
                    }
                } else {
                    $this->request->set('query.route', $this->config->get('root.action_error'));

                    break;
                }
            }

            if (!$this->request->has('query.route')) {
                if ($this->request->has('query.information_id')) {
                    $this->request->set('query.route', 'information/information');
                }
            }
        }
    }

    public function rewrite($link)
    {
        $url_info = parse_url(str_replace('&amp;', '&', $link));

        $url = '';

        $data = array();

        parse_str($url_info['query'], $data);

        foreach ($data as $key => $value) {
            if (isset($data['route'])) {
                if ($data['route'] == 'information/information' && $key == 'information_id') {
                    $query = $this->db->get("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");

                    if ($query->num_rows && $query->row['keyword']) {
                        $url .= '/' . $query->row['keyword'];

                        unset($data[$key]);
                    }
                }
            }
        }

        if ($url) {
            unset($data['route']);

            $query = '';

            if ($data) {
                foreach ($data as $key => $value) {
                    $query .= '&' . rawurlencode((string)$key) . '=' . rawurlencode((is_array($value) ? http_build_query($value) : (string)$value));
                }

                if ($query) {
                    $query = '?' . str_replace('&', '&amp;', trim($query, '&'));
                }
            }

            return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
        } else {
            return $link;
        }
    }
}
