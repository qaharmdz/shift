<?php

declare(strict_types=1);

namespace Shift\Site\Controller\Page;

use Shift\System\Mvc;

class Contact extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('page/contact');

        $this->document->setTitle($this->language->get('page_title'));

        if ($this->request->is('post') && !$errors = $this->validate($this->request->getArray('post'))) {
            $mail = $this->mail->getInstance();
            $mail->setFrom($this->config->get('system.site.email'), $this->config->get('system.site.name'));
            $mail->addAddress($this->config->get('system.site.email'), $this->config->get('system.site.name'));
            $mail->addReplyTo($this->request->get('post.email'), $this->request->get('post.name'));

            $mail->Subject = html_entity_decode(sprintf($this->language->get('email_subject'), $this->request->get('post.name')), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $mail->Body    = html_entity_decode($this->request->get('post.enquiry'), ENT_QUOTES | ENT_HTML5, 'UTF-8');

            $mail->isHTML(false);
            $mail->send();

            $this->response->redirect($this->router->url('page/contact/success'));
        }

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('text_home'), $this->router->url('page/home')],
            [$this->language->get('page_title'), $this->router->url('page/contact')],
        ]);

        // TODO: Error alert bar

        $data = [];
        $data['input'] = [
            'name'    => $this->request->get('post.name', $this->user->get('fullname')),
            'email'   => $this->request->get('post.email', $this->user->get('email')),
            'enquiry' => $this->request->get('post.enquiry', ''),
        ];
        $data['error'] = [
            'name'    => $errors['input']['name'] ?? '',
            'email'   => $errors['input']['email'] ?? '',
            'enquiry' => $errors['input']['enquiry'] ?? '',
        ];

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('page/contact', $data));
    }

    protected function validate(array $post): array
    {
        $errors = [];

        if (!$this->assert->lengthBetween(3, 32)->check($post['name'])) {
            $errors['input']['name'] = sprintf($this->language->get('error_length_between'), 3, 32);
        }

        if (!$this->assert->email()->check($post['email'])) {
            $errors['input']['email'] = $this->language->get('error_email');
        }

        if (!$this->assert->lengthBetween(5, 3000)->check($post['enquiry'])) {
            $errors['input']['enquiry'] = sprintf($this->language->get('error_enquiry'), 5, 3000);
        }

        if (isset($errors['input'])) {
            $errors['alerts'] = $this->language->get('error_form');
        }

        return $errors;
    }

    public function success()
    {
        $this->load->language('page/contact');

        $this->document->setTitle($this->language->get('page_title'));

        $this->document->addNode('breadcrumbs', [
            [$this->language->get('text_home'), $this->router->url('page/home')],
            [$this->language->get('page_title'), $this->router->url('page/contact')],
            ['Success'],
        ]);

        $data['page_title'] = $this->language->get('page_title');
        $data['content']    = $this->language->get('text_success');

        $data['layouts'] = $this->load->controller('block/position');
        $data['footer']  = $this->load->controller('block/footer');
        $data['header']  = $this->load->controller('block/header');

        $this->response->setOutput($this->load->view('page/success', $data));
    }
}
