<?php

declare(strict_types=1);

namespace Shift\Site\Controller\common;

use Shift\System\Mvc;

class Language extends Mvc\Controller
{
    public function index()
    {
        $this->load->model('extension/language');

        if ($this->model_extension_language->getTotalLanguages() > 1) {
            $this->load->language('common/language');

            $data['code'] = $this->session->get('language', $this->config->get('env.language_code'));
            $data['languages'] = [];

            $languages = $this->model_extension_language->getLanguages();
            foreach ($languages as $result) {
                $data['languages'][] = [
                    'name' => $result['name'],
                    'code' => $result['code'],
                    'flag' => $result['flag'],
                ];
            }

            $data['redirect'] = ['route' => 'common/home', 'args' => ''];
            if ($this->request->has('query.route')) {
                $url_data = $this->request->get('query');
                $route = $url_data['route'];
                unset($url_data['route']);

                $args = '';
                if ($url_data) {
                    $args = '&' . urldecode(http_build_query($url_data, '', '&'));
                }

                $data['redirect'] = ['route' => $route, 'args' => $args];
            }

            return $this->load->view('common/language', $data);
        }
    }

    public function language()
    {
        $this->load->model('extension/language');
        $languages = $this->model_extension_language->getLanguages();

        $this->session->set('language', $this->request->get('post.code'));

        $this->response->redirect($this->router->url(
            $this->request->get('post.redirect_route'),
            $this->request->get('post.redirect_args'),
            (int)$languages[$this->request->get('post.code')]['language_id'],
        ));
    }
}
