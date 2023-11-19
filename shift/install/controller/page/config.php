<?php

declare(strict_types=1);

namespace Shift\Install\Controller\Page;

use Shift\System\Mvc;

class Config extends Mvc\Controller
{
    public function index()
    {
        $this->document->setTitle($this->language->get('configuration'));

        $data = [];
        $post = $this->request->get('post', []);

        $data['error'] = '';
        $data['setting'] = array_replace_recursive(
            $this->config->getArray('root.database.config'),
            $this->config->getArray('root.database.table'),
            [
                'sitename'         => 'Shift',
                'email'            => '',
                'account_username' => 'admin',
                'account_password' => '',
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
                || !$this->assert->minLength(3)->check($post['account_username'])
                || !$this->assert->minLength(3)->check($post['account_password'])
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

                    $configFile = PATH_SHIFT . '_config.php';
                    if (is_writable(PATH_SHIFT)) {
                        $configdata = $this->getConfigData($post);

                        if (file_put_contents($configFile, '<?php') !== false) {
                            file_put_contents($configFile, $configdata);
                        } else {
                            $this->session->set('flash.config.content', $configdata);
                            // $this->session->pull('flash.auth.after_login', $this->config->get('root.route_default'));
                        }
                    }
                } catch (\Throwable $t) {
                    $data['error'] = $t->getMessage();
                }
            }
        }

        $this->response->setOutput($this->load->view('page/config', $data));
    }

    private function getConfigData(array $data)
    {
        $config = '<?php' . "\n";
        $config .= "\n";
        $config .= 'declare(strict_types=1);' . "\n";
        $config .= "\n";
        $config .= 'return [' . "\n";
        $config .= '    \'url_host\' => \'' . str_replace($_SERVER['PROTOCOL'], '', $this->config->get('root.url_host')) . '\',' . "\n";
        $config .= '    \'database\' => [' . "\n";
        $config .= '        \'config\' => [' . "\n";
        $config .= '            \'host\'     => \'' . $data['host'] . '\',' . "\n";
        $config .= '            \'username\' => \'' . $data['username'] . '\',' . "\n";
        $config .= '            \'password\' => \'' . $data['password'] . '\',' . "\n";
        $config .= '            \'database\' => \'' . $data['database'] . '\',' . "\n";
        $config .= '            \'port\'     => ' . $data['port'] . ',' . "\n";
        $config .= '        ],' . "\n";
        $config .= '        \'table\' => [' . "\n";
        $config .= '            \'prefix\' => \'sf_\',' . "\n";
        $config .= '        ],' . "\n";
        $config .= '    ]' . "\n";
        $config .= '];' . "\n";
        $config .= '';

        return $config;
    }
}
