<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Design;

use Shift\System\Core\Mvc;

class Layout extends Mvc\Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('design/layout');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('design/layout');

        $this->getList();
    }

    public function add()
    {
        $this->load->language('design/layout');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('design/layout');

        if ($this->request->is('POST') && $this->validateForm()) {
            $this->model_design_layout->addLayout($this->request->get('post', []));

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

            $this->response->redirect($this->router->url('design/layout', 'token=' . $this->session->get('token') . $url));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('design/layout');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('design/layout');

        if ($this->request->is('POST') && $this->validateForm()) {
            $this->model_design_layout->editLayout($this->request->get('query.layout_id'), $this->request->get('post', []));

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

            $this->response->redirect($this->router->url('design/layout', 'token=' . $this->session->get('token') . $url));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('design/layout');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('design/layout');

        if ($this->request->has('post.selected') && $this->validateDelete()) {
            foreach ($this->request->get('post.selected') as $layout_id) {
                $this->model_design_layout->deleteLayout($layout_id);
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

            $this->response->redirect($this->router->url('design/layout', 'token=' . $this->session->get('token') . $url));
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
            'href' => $this->router->url('design/layout', 'token=' . $this->session->get('token') . $url)
        );

        $data['add'] = $this->router->url('design/layout/add', 'token=' . $this->session->get('token') . $url);
        $data['delete'] = $this->router->url('design/layout/delete', 'token=' . $this->session->get('token') . $url);

        $data['layouts'] = array();

        $filter_data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('env.limit'),
            'limit' => $this->config->get('env.limit')
        );

        $layout_total = $this->model_design_layout->getTotalLayouts();

        $results = $this->model_design_layout->getLayouts($filter_data);

        foreach ($results as $result) {
            $data['layouts'][] = array(
                'layout_id' => $result['layout_id'],
                'name'      => $result['name'],
                'edit'      => $this->router->url('design/layout/edit', 'token=' . $this->session->get('token') . '&layout_id=' . $result['layout_id'] . $url)
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_name'] = $this->language->get('column_name');
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

        $data['sort_name'] = $this->router->url('design/layout', 'token=' . $this->session->get('token') . '&sort=name' . $url);

        $url = '';

        if ($this->request->has('query.sort')) {
            $url .= '&sort=' . $this->request->get('query.sort');
        }

        if ($this->request->has('query.order')) {
            $url .= '&order=' . $this->request->get('query.order');
        }

        $pagination = new \Pagination();
        $pagination->total = $layout_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('env.limit');
        $pagination->url = $this->router->url('design/layout', 'token=' . $this->session->get('token') . $url . '&page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($layout_total) ? (($page - 1) * $this->config->get('env.limit')) + 1 : 0, ((($page - 1) * $this->config->get('env.limit')) > ($layout_total - $this->config->get('env.limit'))) ? $layout_total : ((($page - 1) * $this->config->get('env.limit')) + $this->config->get('env.limit')), $layout_total, ceil($layout_total / $this->config->get('env.limit')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('design/layout_list', $data));
    }

    protected function getForm()
    {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !$this->request->has('query.layout_id') ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_route'] = $this->language->get('text_route');
        $data['text_module'] = $this->language->get('text_module');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_content_top'] = $this->language->get('text_content_top');
        $data['text_content_bottom'] = $this->language->get('text_content_bottom');
        $data['text_column_left'] = $this->language->get('text_column_left');
        $data['text_column_right'] = $this->language->get('text_column_right');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_remove'] = $this->language->get('text_remove');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_site'] = $this->language->get('entry_site');
        $data['entry_route'] = $this->language->get('entry_route');
        $data['entry_module'] = $this->language->get('entry_module');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_route_add'] = $this->language->get('button_route_add');
        $data['button_module_add'] = $this->language->get('button_module_add');
        $data['button_edit'] = $this->language->get('button_edit');
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
            'href' => $this->router->url('design/layout', 'token=' . $this->session->get('token') . $url)
        );

        if (!$this->request->has('query.layout_id')) {
            $data['action'] = $this->router->url('design/layout/add', 'token=' . $this->session->get('token') . $url);
        } else {
            $data['action'] = $this->router->url('design/layout/edit', 'token=' . $this->session->get('token') . '&layout_id=' . $this->request->get('query.layout_id') . $url);
        }

        $data['cancel'] = $this->router->url('design/layout', 'token=' . $this->session->get('token') . $url);

        $data['token'] = $this->session->get('token');

        if ($this->request->has('query.layout_id') && !$this->request->is('POST')) {
            $layout_info = $this->model_design_layout->getLayout($this->request->get('query.layout_id'));
        }

        if ($this->request->has('post.name')) {
            $data['name'] = $this->request->get('post.name');
        } elseif (!empty($layout_info)) {
            $data['name'] = $layout_info['name'];
        } else {
            $data['name'] = '';
        }

        $this->load->model('setting/site');

        $data['sites'] = $this->model_setting_site->getSites();

        if ($this->request->has('post.layout_route')) {
            $data['layout_routes'] = $this->request->get('post.layout_route');
        } elseif ($this->request->has('query.layout_id')) {
            $data['layout_routes'] = $this->model_design_layout->getLayoutRoutes($this->request->get('query.layout_id'));
        } else {
            $data['layout_routes'] = array();
        }

        $this->load->model('extension/extension');

        $this->load->model('extension/module');

        $data['extensions'] = array();

        // Get a list of installed modules
        $extensions = $this->model_extension_extension->getInstalled('module');

        // Add all the modules which have multiple settings for each module
        foreach ($extensions as $code) {
            $this->load->language('extension/module/' . $code);

            $module_data = array();

            $modules = $this->model_extension_module->getModulesByCode($code);

            foreach ($modules as $module) {
                $module_data[] = array(
                    'name' => strip_tags($module['name']),
                    'code' => $code . '.' .  $module['module_id']
                );
            }

            if ($this->config->has($code . '_status') || $module_data) {
                $data['extensions'][] = array(
                    'name'   => strip_tags($this->language->get('heading_title')),
                    'code'   => $code,
                    'module' => $module_data
                );
            }
        }

        // Modules layout
        if ($this->request->has('post.layout_module')) {
            $layout_modules = $this->request->get('post.layout_module');
        } elseif ($this->request->has('query.layout_id')) {
            $layout_modules = $this->model_design_layout->getLayoutModules($this->request->get('query.layout_id'));
        } else {
            $layout_modules = array();
        }

        $data['layout_modules'] = array();

        // Add all the modules which have multiple settings for each module
        foreach ($layout_modules as $layout_module) {
            $part = explode('.', $layout_module['code']);

            $this->load->language('extension/module/' . $part[0]);

            if (!isset($part[1])) {
                $data['layout_modules'][] = array(
                    'name'       => strip_tags($this->language->get('heading_title')),
                    'code'       => $layout_module['code'],
                    'edit'       => $this->router->url('extension/module/' . $part[0], 'token=' . $this->session->get('token')),
                    'position'   => $layout_module['position'],
                    'sort_order' => $layout_module['sort_order']
                );
            } else {
                $module_info = $this->model_extension_module->getModule($part[1]);

                if ($module_info) {
                    $data['layout_modules'][] = array(
                        'name'       => strip_tags($module_info['name']),
                        'code'       => $layout_module['code'],
                        'edit'       => $this->router->url('extension/module/' . $part[0], 'token=' . $this->session->get('token') . '&module_id=' . $part[1]),
                        'position'   => $layout_module['position'],
                        'sort_order' => $layout_module['sort_order']
                    );
                }
            }
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('design/layout_form', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'design/layout')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->get('post.name')) < 3) || (utf8_strlen($this->request->get('post.name')) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'design/layout')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        $this->load->model('setting/site');
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('catalog/information');

        foreach ($this->request->get('post.selected') as $layout_id) {
            if ($this->config->get('system.setting.layout_id') == $layout_id) {
                $this->error['warning'] = $this->language->get('error_default');
            }

            $site_total = $this->model_setting_site->getTotalSitesByLayoutId($layout_id);

            if ($site_total) {
                $this->error['warning'] = sprintf($this->language->get('error_site'), $site_total);
            }

            $product_total = $this->model_catalog_product->getTotalProductsByLayoutId($layout_id);

            if ($product_total) {
                $this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
            }

            $category_total = $this->model_catalog_category->getTotalCategoriesByLayoutId($layout_id);

            if ($category_total) {
                $this->error['warning'] = sprintf($this->language->get('error_category'), $category_total);
            }

            $information_total = $this->model_catalog_information->getTotalInformationsByLayoutId($layout_id);

            if ($information_total) {
                $this->error['warning'] = sprintf($this->language->get('error_information'), $information_total);
            }
        }

        return !$this->error;
    }
}
