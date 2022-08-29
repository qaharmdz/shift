<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Extension;

use Shift\System\Mvc;

class Event extends Mvc\Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('extension/event');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/event');

        $this->getList();
    }

    public function enable()
    {
        $this->load->language('extension/event');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/event');

        if ($this->request->has('query.event_id') && $this->validate()) {
            $this->model_extension_event->enableEvent($this->request->getInt('query.event_id'));

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

            $this->response->redirect($this->router->url('extension/event' . $url));
        }

        $this->getList();
    }

    public function disable()
    {
        $this->load->language('extension/event');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/event');

        if ($this->request->has('query.event_id') && $this->validate()) {
            $this->model_extension_event->disableEvent($this->request->get('query.event_id'));

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

            $this->response->redirect($this->router->url('extension/event' . $url));
        }

        $this->getList();
    }

    public function getList()
    {
        $sort  = $this->request->get('query.sort', 'code');
        $order = $this->request->get('query.order', 'ASC');
        $page  = $this->request->get('query.page', 1);

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
            'href' => $this->router->url('common/dashboard')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->router->url('extension/event' . $url)
        );

        $data['events'] = array();

        $filter_data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('env.limit'),
            'limit' => $this->config->get('env.limit')
        );

        $event_total = $this->model_extension_event->getTotalEvents();

        $results = $this->model_extension_event->getEvents($filter_data);

        foreach ($results as $result) {
            $data['events'][] = array(
                'event_id'   => $result['event_id'],
                'code'       => $result['code'],
                'trigger'    => $result['trigger'],
                'action'     => $result['action'],
                'status'     => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                'created'    => date($this->language->get('date_format_short'), strtotime($result['created'])),
                'enable'     => $this->router->url('extension/event/enable' . '&event_id=' . $result['event_id']),
                'disable'    => $this->router->url('extension/event/disable' . '&event_id=' . $result['event_id']),
                'enabled'    => $result['status']
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_event'] = $this->language->get('text_event');

        $data['column_code'] = $this->language->get('column_code');
        $data['column_trigger'] = $this->language->get('column_trigger');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_action'] = $this->language->get('column_action');

        $data['button_enable'] = $this->language->get('button_enable');
        $data['button_disable'] = $this->language->get('button_disable');

        $data['error_warning'] = '';
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        }

        $data['success'] = $this->session->pull('flash.success');
        $data['selected'] = $this->request->getArray('post.selected');

        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if ($this->request->has('query.page')) {
            $url .= '&page=' . $this->request->get('query.page');
        }

        $data['sort_code'] = $this->router->url('extension/event' . '&sort=code' . $url);
        $data['sort_trigger'] = $this->router->url('extension/event' . '&sort=trigger' . $url);
        $data['sort_action'] = $this->router->url('extension/event' . '&sort=action' . $url);
        $data['sort_status'] = $this->router->url('extension/event' . '&sort=status' . $url);
        $data['sort_date_added'] = $this->router->url('extension/event' . '&sort=date_added' . $url);

        $url = '';

        if ($this->request->has('query.sort')) {
            $url .= '&sort=' . $this->request->get('query.sort');
        }

        if ($this->request->has('query.order')) {
            $url .= '&order=' . $this->request->get('query.order');
        }

        $pagination = new \Shift\System\Library\Legacy\Pagination();
        $pagination->total = $event_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('env.limit');
        $pagination->url = $this->router->url('extension/event' . $url . '&page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($event_total) ? (($page - 1) * $this->config->get('env.limit')) + 1 : 0, ((($page - 1) * $this->config->get('env.limit')) > ($event_total - $this->config->get('env.limit'))) ? $event_total : ((($page - 1) * $this->config->get('env.limit')) + $this->config->get('env.limit')), $event_total, ceil($event_total / $this->config->get('env.limit')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/event', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/event')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
