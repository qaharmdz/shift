<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Design;

use Shift\System\Mvc;

class Banner extends Mvc\Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('design/banner');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('design/banner');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('design/banner');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('design/banner');

        if ($this->request->is('POST') && $this->validateForm()) {
            $this->model_design_banner->addBanner($this->request->get('post', []));

            $this->session->set('flash.success', $this->language->get('text_success'));

            $url = '';

            if ($this->request->has('query.sort')) {
                $url .= '&sort=' . $this->request->get('query.sort');
            }

            if ($this->request->has('query.order')) {
                $url .= '&order=' . $this->request->get('query.order');
            }

            if ($this->request->has('query.page')) {
                $url .= '&page=' . $this->request->get('query.page');
            }

            $this->response->redirect($this->router->url('design/banner', 'token=' . $this->session->get('token') . $url));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('design/banner');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('design/banner');

        if ($this->request->is('POST') && $this->validateForm()) {
            $this->model_design_banner->editBanner($this->request->get('query.banner_id'), $this->request->get('post', []));

            $this->session->set('flash.success', $this->language->get('text_success'));

            $url = '';

            if ($this->request->has('query.sort')) {
                $url .= '&sort=' . $this->request->get('query.sort');
            }

            if ($this->request->has('query.order')) {
                $url .= '&order=' . $this->request->get('query.order');
            }

            if ($this->request->has('query.page')) {
                $url .= '&page=' . $this->request->get('query.page');
            }

            $this->response->redirect($this->router->url('design/banner', 'token=' . $this->session->get('token') . $url));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('design/banner');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('design/banner');

        if ($this->request->has('post.selected') && $this->validateDelete()) {
            foreach ($this->request->get('post.selected') as $banner_id) {
                $this->model_design_banner->deleteBanner($banner_id);
            }

            $this->session->set('flash.success', $this->language->get('text_success'));

            $url = '';

            if ($this->request->has('query.sort')) {
                $url .= '&sort=' . $this->request->get('query.sort');
            }

            if ($this->request->has('query.order')) {
                $url .= '&order=' . $this->request->get('query.order');
            }

            if ($this->request->has('query.page')) {
                $url .= '&page=' . $this->request->get('query.page');
            }

            $this->response->redirect($this->router->url('design/banner', 'token=' . $this->session->get('token') . $url));
        }

        $this->getList();
    }

    protected function getList()
    {
        if ($this->request->has('query.sort')) {
            $sort = $this->request->get('query.sort');
        } else {
            $sort = 'name';
        }

        if ($this->request->has('query.order')) {
            $order = $this->request->get('query.order');
        } else {
            $order = 'ASC';
        }

        if ($this->request->has('query.page')) {
            $page = $this->request->get('query.page');
        } else {
            $page = 1;
        }

        $url = '';

        if ($this->request->has('query.sort')) {
            $url .= '&sort=' . $this->request->get('query.sort');
        }

        if ($this->request->has('query.order')) {
            $url .= '&order=' . $this->request->get('query.order');
        }

        if ($this->request->has('query.page')) {
            $url .= '&page=' . $this->request->get('query.page');
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->router->url('common/dashboard', 'token=' . $this->session->get('token'))
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->router->url('design/banner', 'token=' . $this->session->get('token') . $url)
        );

        $data['add'] = $this->router->url('design/banner/add', 'token=' . $this->session->get('token') . $url);
        $data['delete'] = $this->router->url('design/banner/delete', 'token=' . $this->session->get('token') . $url);

        $data['banners'] = array();

        $filter_data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('env.limit'),
            'limit' => $this->config->get('env.limit')
        );

        $banner_total = $this->model_design_banner->getTotalBanners();

        $results = $this->model_design_banner->getBanners($filter_data);

        foreach ($results as $result) {
            $data['banners'][] = array(
                'banner_id' => $result['banner_id'],
                'name'      => $result['name'],
                'status'    => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'edit'      => $this->router->url('design/banner/edit', 'token=' . $this->session->get('token') . '&banner_id=' . $result['banner_id'] . $url)
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_action'] = $this->language->get('column_action');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['success'] = $this->session->pull('flash.success');

        if ($this->request->has('post.selected')) {
            $data['selected'] = (array)$this->request->get('post.selected');
        } else {
            $data['selected'] = array();
        }

        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if ($this->request->has('query.page')) {
            $url .= '&page=' . $this->request->get('query.page');
        }

        $data['sort_name'] = $this->router->url('design/banner', 'token=' . $this->session->get('token') . '&sort=name' . $url);
        $data['sort_status'] = $this->router->url('design/banner', 'token=' . $this->session->get('token') . '&sort=status' . $url);

        $url = '';

        if ($this->request->has('query.sort')) {
            $url .= '&sort=' . $this->request->get('query.sort');
        }

        if ($this->request->has('query.order')) {
            $url .= '&order=' . $this->request->get('query.order');
        }

        $pagination = new \Shift\System\Library\Legacy\Pagination();
        $pagination->total = $banner_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('env.limit');
        $pagination->url = $this->router->url('design/banner', 'token=' . $this->session->get('token') . $url . '&page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($banner_total) ? (($page - 1) * $this->config->get('env.limit')) + 1 : 0, ((($page - 1) * $this->config->get('env.limit')) > ($banner_total - $this->config->get('env.limit'))) ? $banner_total : ((($page - 1) * $this->config->get('env.limit')) + $this->config->get('env.limit')), $banner_total, ceil($banner_total / $this->config->get('env.limit')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('design/banner_list', $data));
    }

    protected function getForm()
    {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !$this->request->has('query.banner_id') ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_default'] = $this->language->get('text_default');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_title'] = $this->language->get('entry_title');
        $data['entry_link'] = $this->language->get('entry_link');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_banner_add'] = $this->language->get('button_banner_add');
        $data['button_remove'] = $this->language->get('button_remove');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['banner_image'])) {
            $data['error_banner_image'] = $this->error['banner_image'];
        } else {
            $data['error_banner_image'] = array();
        }

        $url = '';

        if ($this->request->has('query.sort')) {
            $url .= '&sort=' . $this->request->get('query.sort');
        }

        if ($this->request->has('query.order')) {
            $url .= '&order=' . $this->request->get('query.order');
        }

        if ($this->request->has('query.page')) {
            $url .= '&page=' . $this->request->get('query.page');
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->router->url('common/dashboard', 'token=' . $this->session->get('token'))
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->router->url('design/banner', 'token=' . $this->session->get('token') . $url)
        );

        if (!$this->request->has('query.banner_id')) {
            $data['action'] = $this->router->url('design/banner/add', 'token=' . $this->session->get('token') . $url);
        } else {
            $data['action'] = $this->router->url('design/banner/edit', 'token=' . $this->session->get('token') . '&banner_id=' . $this->request->get('query.banner_id') . $url);
        }

        $data['cancel'] = $this->router->url('design/banner', 'token=' . $this->session->get('token') . $url);

        $banner_info = [];
        if ($this->request->has('query.banner_id') && !$this->request->is('POST')) {
            $banner_info = $this->model_design_banner->getBanner($this->request->get('query.banner_id'));
        }

        $data['token'] = $this->session->get('token');

        // Todo: $data['name'] = $this->request->get('post.name', Arr::get($banner_info, 'name', ''));
        if ($this->request->has('post.name')) {
            $data['name'] = $this->request->get('post.name');
        } elseif (!empty($banner_info)) {
            $data['name'] = $banner_info['name'];
        } else {
            $data['name'] = '';
        }

        if ($this->request->has('post.status')) {
            $data['status'] = $this->request->get('post.status');
        } elseif (!empty($banner_info)) {
            $data['status'] = $banner_info['status'];
        } else {
            $data['status'] = true;
        }

        $this->load->model('extension/language');

        $data['languages'] = $this->model_extension_language->getLanguages();

        if ($this->request->has('post.banner_image')) {
            $banner_images = $this->request->get('post.banner_image');
        } elseif ($this->request->has('query.banner_id')) {
            $banner_images = $this->model_design_banner->getBannerImages($this->request->get('query.banner_id'));
        } else {
            $banner_images = array();
        }

        $data['banner_images'] = array();

        foreach ($banner_images as $key => $value) {
            foreach ($value as $banner_image) {
                if (is_file(DIR_MEDIA . $banner_image['image'])) {
                    $image = $banner_image['image'];
                    $thumb = $banner_image['image'];
                } else {
                    $image = '';
                    $thumb = 'image/no-image.png';
                }

                $data['banner_images'][$key][] = array(
                    'title'      => $banner_image['title'],
                    'link'       => $banner_image['link'],
                    'image'      => $image,
                    'thumb'      => $this->image->construct($thumb, 100, 100),
                    'sort_order' => $banner_image['sort_order']
                );
            }
        }

        $data['placeholder'] = $this->image->construct('image/no-image.png', 100, 100);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('design/banner_form', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'design/banner')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->get('post.name')) < 3) || (utf8_strlen($this->request->get('post.name')) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if ($this->request->has('post.banner_image')) {
            foreach ($this->request->get('post.banner_image') as $language_id => $value) {
                foreach ($value as $banner_image_id => $banner_image) {
                    if ((utf8_strlen($banner_image['title']) < 2) || (utf8_strlen($banner_image['title']) > 64)) {
                        $this->error['banner_image'][$language_id][$banner_image_id] = $this->language->get('error_title');
                    }
                }
            }
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'design/banner')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
