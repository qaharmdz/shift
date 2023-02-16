<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Content;

use Shift\System\Mvc;

class Setting extends Mvc\Controller
{
    public function index()
    {
        $this->load->config('content/setting');
        $this->load->model('setting/site');
        $this->load->model('setting/setting');
        $this->load->language('content/general');
        $this->load->language('content/setting');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('content')],
            [$this->language->get('page_title'), $this->router->url('content/setting')],
        ]);

        $data = [];

        $data['site_id'] = $this->request->getInt('query.site_id', 0);
        $data['setting'] = array_replace_recursive(
            $this->config->getArray('content.setting.form'),
            $this->model_setting_setting->getSetting('content', 'setting', $data['site_id']),
        );

        $data['sites'] = [];
        foreach ($this->model_setting_site->getSites() as $key => $site) {
            $data['sites'][$key] = $site;
            $data['sites'][$key]['url_setting'] = $this->router->url('content/setting', 'site_id=' . $site['site_id']);
        }

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('content/setting', $data));
    }

    public function save()
    {
        /*
        $this->load->config('content/setting');
        $this->load->model('content/setting');
        $this->load->language('content/setting');

        if (!$this->user->hasPermission('modify', 'content/setting')) {
            return $this->response->setOutputJson($this->language->get('error_permission'), 403);
        }
        if (!$this->request->is(['post', 'ajax'])) {
            return $this->response->setOutputJson($this->language->get('error_request_method'), 405);
        }
        if (!$this->request->has('post.tag_id')) {
            return $this->response->setOutputJson($this->language->get('error_precondition'), 412);
        }

        $data = [];
        $post = array_replace_recursive(
            $this->config->getArray('content.category.form'),
            $this->request->get('post', [])
        );
        $tag_id = (int)$post['tag_id'];

        unset($post['content'][0]);

        if ($errors = $this->validate($post)) {
            return $this->response->setOutputJson($errors, 422);
        }

        if (!$tag_id) {
            // $data['new_id'] = $this->model_content_setting->addTag($post);
        } else {
            // $this->model_content_setting->editTag($tag_id, $post);
        }

        // Redirect
        if ($post['action'] === 'close') {
            $data['redirect'] = $this->router->url('content/setting');
        }
        if ($post['action'] === 'new') {
            $data['redirect'] = $this->router->url('content/setting/form');
        }
        if (isset($data['new_id']) && empty($data['redirect'])) {
            $data['redirect'] = $this->router->url('content/setting/form', 'tag_id=' . $data['new_id']);
        }
        */

        $data = [];
        $this->response->setOutputJson($data);
    }

    protected function validate(array $post): array
    {
        $errors = [];

        foreach ($post['content'] as $language_id => $content) {
            if (!$this->assert->lengthBetween(2, 200)->check($content['title'])) {
                $errors['items']['content[' . $language_id . '][title]'] = sprintf($this->language->get('error_length_between'), 2, 200);
            }
        }

        foreach ($post['alias'] as $language_id => &$alias) {
            if (!$alias = str_replace(' ', '-', trim($alias))) {
                continue;
            }

            if (count(array_keys($post['alias'], $alias)) > 1) {
                $errors['items']['alias[' . $language_id . ']'] = $this->language->get('error_alias_unique');
            }

            if (empty($errors['items']['alias[' . $language_id . ']'])) {
                $aliases = $this->db->get(
                    "SELECT * FROM `" . DB_PREFIX . "route_alias` WHERE `language_id` != ?i AND `alias` = ?s",
                    [$language_id, $alias]
                )->rows;

                foreach ($aliases as $alias) {
                    if (
                        !$post['tag_id']
                        || (
                            $alias['route'] == 'content/setting'
                            && $alias['param'] == 'tag_id'
                            && $alias['value'] != $post['tag_id']
                        )
                    ) {
                        $errors['items']['alias[' . $language_id . ']'] = $this->language->get('error_alias_exist');
                        break;
                    }
                }
            }
        }

        if (isset($errors['items'])) {
            $errors['response'] = $this->language->get('error_form');
        }

        return $errors;
    }
}
