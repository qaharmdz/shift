<?php

declare(strict_types=1);

class ControllerCommonLanguage extends Controller
{
    public function index()
    {
        $this->load->language('common/language');

        $data['text_language'] = $this->language->get('text_language');

        $data['action'] = $this->url->link('common/language/language', '', $this->request->getBool('server.SECURE'));

        $data['code'] = $this->session->get('language');

        $this->load->model('localisation/language');

        $data['languages'] = array();

        $results = $this->model_localisation_language->getLanguages();

        foreach ($results as $result) {
            if ($result['status']) {
                $data['languages'][] = array(
                    'name' => $result['name'],
                    'code' => $result['code']
                );
            }
        }

        if (!$this->request->has('query.route')) {
            $data['redirect'] = $this->url->link('common/home');
        } else {
            $url_data = $this->request->get('query');

            $route = $url_data['route'];

            unset($url_data['route']);

            $url = '';

            if ($url_data) {
                $url = '&' . urldecode(http_build_query($url_data, '', '&'));
            }

            $data['redirect'] = $this->url->link($route, $url, $this->request->getBool('server.SECURE'));
        }

        return $this->load->view('common/language', $data);
    }

    public function language()
    {
        if ($this->request->has('post.code')) {
            $this->session->set('language', $this->request->get('post.code'));
        }

        $this->response->redirect($this->request->get('post.redirect', 'common/home'));
    }
}
