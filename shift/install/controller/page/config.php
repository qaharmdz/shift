<?php

declare(strict_types=1);

namespace Shift\Install\Controller\Page;

use Shift\System\Mvc;

class Config extends Mvc\Controller
{
    private $configFilepath = PATH_SHIFT . 'config.php';

    public function index()
    {
        $this->document->setTitle($this->language->get('configuration'));

        $data = [];
        $post = $this->request->get('post', []);

        $data['error'] = '';
        $data['setting'] = array_replace_recursive(
            $this->config->getArray('root.database.config'),
            [
                'prefix'        => $this->config->get('root.database.prefix'),
                'sitename'      => 'Shift',
                'email'         => '',
                'user_password' => '',
            ],
            $post,
        );

        if ($post) {
            if (
                !$this->assert->notEmpty()->check($post['host'])
                || !$this->assert->minLength(3)->check($post['username'])
                || !$this->assert->digits()->check($post['port'])
                || !$this->assert->notEmpty()->check($post['database'])
                || !$this->assert->email()->check($post['email'])
                || !$this->assert->minLength(3)->check($post['user_password'])
            ) {
                $data['error'] = $this->language->get('error_form');
            }

            if (empty($data['error'])) {
                try {
                    $db = new \Shift\System\Core\Database(
                        $post['host'],
                        $post['username'],
                        $post['password'],
                        $post['database'],
                        (int)$post['port'],
                    );

                    if (is_writable(PATH_SHIFT)) {
                        $post['url_host'] = $this->config->get('root.url_host');

                        $this->session->set('install.config', $post);
                        $configData = $this->getConfigData($post);

                        if (file_put_contents($this->configFilepath, '<?php') !== false) {
                            file_put_contents($this->configFilepath, $configData);
                            $this->response->redirect($this->router->url('page/install'));
                        } else {
                            $this->session->set('install.config_content', $configData);
                            $this->response->redirect($this->router->url('page/config/manual'));
                        }
                    }
                } catch (\Throwable $t) {
                    $data['error'] = $t->getMessage();
                }
            }
        }

        $this->response->setOutput($this->load->view('page/config', $data));
    }

    public function manual()
    {
        if (!$this->session->has('install.config_content')) {
            $this->response->redirect($this->router->url('page/config'));
        }

        $this->document->setTitle($this->language->get('configuration'));

        $data = [];
        $data['config_filepath'] = $this->configFilepath;
        $data['config_content']  = $this->session->get('install.config_content');

        $this->response->setOutput($this->load->view('page/config_manual', $data));
    }

    private function getConfigData(array $data)
    {
        $config = '<?php' . "\n";
        $config .= "\n";
        $config .= 'declare(strict_types=1);' . "\n";
        $config .= "\n";
        $config .= 'return [' . "\n";
        $config .= '    \'url_host\' => \'' . str_replace($_SERVER['PROTOCOL'], '', $data['url_host']) . '\',' . "\n";
        $config .= '    \'database\' => [' . "\n";
        $config .= '        \'config\' => [' . "\n";
        $config .= '            \'host\'     => \'' . $data['host'] . '\',' . "\n";
        $config .= '            \'username\' => \'' . $data['username'] . '\',' . "\n";
        $config .= '            \'password\' => \'' . $data['password'] . '\',' . "\n";
        $config .= '            \'database\' => \'' . $data['database'] . '\',' . "\n";
        $config .= '            \'port\'     => ' . $data['port'] . ',' . "\n";
        $config .= '        ],' . "\n";
        $config .= '        \'prefix\' => \'' . $data['prefix'] . '\',' . "\n";
        $config .= '    ]' . "\n";
        $config .= '];' . "\n";
        $config .= '';

        return $config;
    }
}
