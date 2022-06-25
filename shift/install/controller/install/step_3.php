<?php

declare(strict_types=1);

class ControllerInstallStep3 extends Controller
{
    private $error = array();

    public function index()
    {
        $this->language->load('install/step_3');

        if ($this->request->is('POST') && $this->validate()) {
            $this->load->model('install/install');

            $this->model_install_install->database($this->request->get('post'));

            $output  = '<?php' . "\n";
            $output .= "\n";
            $output .= '// HTTP' . "\n";
            $output .= 'define(\'URL_APP\', \'' . HTTP_SHIFT . '\');' . "\n\n";

            $output .= '// HTTPS' . "\n";
            $output .= 'define(\'URL_APP\', \'' . HTTP_SHIFT . '\');' . "\n\n";

            $output .= '// DIR' . "\n";
            $output .= 'define(\'DIR_APPLICATION\', \'' . PATH_SHIFT . 'catalog/\');' . "\n";
            $output .= 'define(\'DIR_SYSTEM\', \'' . PATH_SHIFT . 'system/\');' . "\n";
            $output .= 'define(\'DIR_IMAGE\', \'' . PATH_SHIFT . 'image/\');' . "\n";
            $output .= 'define(\'DIR_LANGUAGE\', \'' . PATH_SHIFT . 'catalog/language/\');' . "\n";
            $output .= 'define(\'DIR_TEMPLATE\', \'' . PATH_SHIFT . 'catalog/view/theme/\');' . "\n";
            // $output .= 'define(\'DIR_CONFIG\', \'' . PATH_SHIFT . 'system/config/\');' . "\n";
            $output .= 'define(\'DIR_CACHE\', \'' . PATH_SHIFT . 'system/storage/cache/\');' . "\n";
            // $output .= 'define(\'DIR_LOGS\', \'' . PATH_SHIFT . 'system/storage/logs/\');' . "\n";
            $output .= 'define(\'DIR_UPLOAD\', \'' . PATH_SHIFT . 'system/storage/upload/\');' . "\n\n";

            $output .= '// DB' . "\n";
            $output .= 'define(\'DB_DRIVER\', \'' . addslashes($this->request->get('post.db_driver')) . '\');' . "\n";
            $output .= 'define(\'DB_HOSTNAME\', \'' . addslashes($this->request->get('post.db_hostname')) . '\');' . "\n";
            $output .= 'define(\'DB_USERNAME\', \'' . addslashes($this->request->get('post.db_username')) . '\');' . "\n";
            $output .= 'define(\'DB_PASSWORD\', \'' . addslashes(html_entity_decode($this->request->get('post.db_password'), ENT_QUOTES, 'UTF-8')) . '\');' . "\n";
            $output .= 'define(\'DB_DATABASE\', \'' . addslashes($this->request->get('post.db_database')) . '\');' . "\n";
            $output .= 'define(\'DB_PORT\', \'' . addslashes($this->request->get('post.db_port')) . '\');' . "\n";
            $output .= 'define(\'DB_PREFIX\', \'' . addslashes($this->request->get('post.db_prefix')) . '\');' . "\n";

            $file = fopen(PATH_SHIFT . 'config.php', 'w');

            fwrite($file, $output);

            fclose($file);

            $output  = '<?php' . "\n";
            $output .= "\n";
            $output .= '// HTTP' . "\n";
            $output .= 'define(\'URL_APP\', \'' . HTTP_SHIFT . 'admin/\');' . "\n";
            $output .= 'define(\'URL_SITE\', \'' . HTTP_SHIFT . '\');' . "\n\n";

            $output .= '// HTTPS' . "\n";
            $output .= 'define(\'URL_APP\', \'' . HTTP_SHIFT . 'admin/\');' . "\n";
            $output .= 'define(\'URL_SITE\', \'' . HTTP_SHIFT . '\');' . "\n\n";

            $output .= '// DIR' . "\n";
            $output .= 'define(\'DIR_APPLICATION\', \'' . PATH_SHIFT . 'admin/\');' . "\n";
            $output .= 'define(\'DIR_SYSTEM\', \'' . PATH_SHIFT . 'system/\');' . "\n";
            $output .= 'define(\'DIR_IMAGE\', \'' . PATH_SHIFT . 'image/\');' . "\n";
            $output .= 'define(\'DIR_LANGUAGE\', \'' . PATH_SHIFT . 'admin/language/\');' . "\n";
            $output .= 'define(\'DIR_TEMPLATE\', \'' . PATH_SHIFT . 'admin/view/template/\');' . "\n";
            // $output .= 'define(\'DIR_CONFIG\', \'' . PATH_SHIFT . 'system/config/\');' . "\n";
            $output .= 'define(\'DIR_CACHE\', \'' . PATH_SHIFT . 'system/storage/cache/\');' . "\n";
            // $output .= 'define(\'DIR_LOGS\', \'' . PATH_SHIFT . 'system/storage/logs/\');' . "\n";
            $output .= 'define(\'DIR_UPLOAD\', \'' . PATH_SHIFT . 'system/storage/upload/\');' . "\n";
            $output .= 'define(\'DIR_SITE\', \'' . PATH_SHIFT . 'catalog/\');' . "\n\n";

            $output .= '// DB' . "\n";
            $output .= 'define(\'DB_DRIVER\', \'' . addslashes($this->request->get('post.db_driver')) . '\');' . "\n";
            $output .= 'define(\'DB_HOSTNAME\', \'' . addslashes($this->request->get('post.db_hostname')) . '\');' . "\n";
            $output .= 'define(\'DB_USERNAME\', \'' . addslashes($this->request->get('post.db_username')) . '\');' . "\n";
            $output .= 'define(\'DB_PASSWORD\', \'' . addslashes(html_entity_decode($this->request->get('post.db_password'), ENT_QUOTES, 'UTF-8')) . '\');' . "\n";
            $output .= 'define(\'DB_DATABASE\', \'' . addslashes($this->request->get('post.db_database')) . '\');' . "\n";
            $output .= 'define(\'DB_PORT\', \'' . addslashes($this->request->get('post.db_port')) . '\');' . "\n";
            $output .= 'define(\'DB_PREFIX\', \'' . addslashes($this->request->get('post.db_prefix')) . '\');' . "\n";

            $file = fopen(PATH_SHIFT . 'admin/config.php', 'w');

            fwrite($file, $output);

            fclose($file);

            $this->response->redirect($this->url->link('install/step_4'));
        }

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_step_3'] = $this->language->get('text_step_3');
        $data['text_db_connection'] = $this->language->get('text_db_connection');
        $data['text_db_administration'] = $this->language->get('text_db_administration');
        $data['text_mysqli'] = $this->language->get('text_mysqli');
        $data['text_mpdo'] = $this->language->get('text_mpdo');
        $data['text_pgsql'] = $this->language->get('text_pgsql');

        $data['entry_db_driver'] = $this->language->get('entry_db_driver');
        $data['entry_db_hostname'] = $this->language->get('entry_db_hostname');
        $data['entry_db_username'] = $this->language->get('entry_db_username');
        $data['entry_db_password'] = $this->language->get('entry_db_password');
        $data['entry_db_database'] = $this->language->get('entry_db_database');
        $data['entry_db_port'] = $this->language->get('entry_db_port');
        $data['entry_db_prefix'] = $this->language->get('entry_db_prefix');
        $data['entry_username'] = $this->language->get('entry_username');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_email'] = $this->language->get('entry_email');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_back'] = $this->language->get('button_back');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['db_hostname'])) {
            $data['error_db_hostname'] = $this->error['db_hostname'];
        } else {
            $data['error_db_hostname'] = '';
        }

        if (isset($this->error['db_username'])) {
            $data['error_db_username'] = $this->error['db_username'];
        } else {
            $data['error_db_username'] = '';
        }

        if (isset($this->error['db_database'])) {
            $data['error_db_database'] = $this->error['db_database'];
        } else {
            $data['error_db_database'] = '';
        }

        if (isset($this->error['db_port'])) {
            $data['error_db_port'] = $this->error['db_port'];
        } else {
            $data['error_db_port'] = '';
        }

        if (isset($this->error['db_prefix'])) {
            $data['error_db_prefix'] = $this->error['db_prefix'];
        } else {
            $data['error_db_prefix'] = '';
        }

        if (isset($this->error['username'])) {
            $data['error_username'] = $this->error['username'];
        } else {
            $data['error_username'] = '';
        }

        if (isset($this->error['password'])) {
            $data['error_password'] = $this->error['password'];
        } else {
            $data['error_password'] = '';
        }

        if (isset($this->error['email'])) {
            $data['error_email'] = $this->error['email'];
        } else {
            $data['error_email'] = '';
        }

        $data['action'] = $this->url->link('install/step_3');

        if ($this->request->has('post.db_driver')) {
            $data['db_driver'] = $this->request->get('post.db_driver');
        } else {
            $data['db_driver'] = '';
        }

        if ($this->request->has('post.db_hostname')) {
            $data['db_hostname'] = $this->request->get('post.db_hostname');
        } else {
            $data['db_hostname'] = 'localhost';
        }

        if ($this->request->has('post.db_username')) {
            $data['db_username'] = $this->request->get('post.db_username');
        } else {
            $data['db_username'] = 'root';
        }

        if ($this->request->has('post.db_password')) {
            $data['db_password'] = $this->request->get('post.db_password');
        } else {
            $data['db_password'] = '';
        }

        if ($this->request->has('post.db_database')) {
            $data['db_database'] = $this->request->get('post.db_database');
        } else {
            $data['db_database'] = '';
        }

        if ($this->request->has('post.db_port')) {
            $data['db_port'] = $this->request->get('post.db_port');
        } else {
            $data['db_port'] = 3306;
        }

        if ($this->request->has('post.db_prefix')) {
            $data['db_prefix'] = $this->request->get('post.db_prefix');
        } else {
            $data['db_prefix'] = 'oc_';
        }

        if ($this->request->has('post.username')) {
            $data['username'] = $this->request->get('post.username');
        } else {
            $data['username'] = 'admin';
        }

        if ($this->request->has('post.password')) {
            $data['password'] = $this->request->get('post.password');
        } else {
            $data['password'] = '';
        }

        if ($this->request->has('post.email')) {
            $data['email'] = $this->request->get('post.email');
        } else {
            $data['email'] = '';
        }

        $data['mysqli'] = extension_loaded('mysqli');
        $data['mysql'] = extension_loaded('mysql');
        $data['pdo'] = extension_loaded('pdo');
        $data['pgsql'] = extension_loaded('pgsql');

        $data['back'] = $this->url->link('install/step_2');

        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');

        $this->response->setOutput($this->load->view('install/step_3', $data));
    }

    private function validate()
    {
        if (!$this->request->get('post.db_hostname')) {
            $this->error['db_hostname'] = $this->language->get('error_db_hostname');
        }

        if (!$this->request->get('post.db_username')) {
            $this->error['db_username'] = $this->language->get('error_db_username');
        }

        if (!$this->request->get('post.db_database')) {
            $this->error['db_database'] = $this->language->get('error_db_database');
        }

        if (!$this->request->get('post.db_port')) {
            $this->error['db_port'] = $this->language->get('error_db_port');
        }

        if (preg_match('/[^a-z0-9_]/', $this->request->get('post.db_prefix', ''))) {
            $this->error['db_prefix'] = $this->language->get('error_db_prefix');
        }

        if ($this->request->get('post.db_driver') == 'mysqli') {
            $mysql = @new MySQLi(
                $this->request->get('post.db_hostname'),
                $this->request->get('post.db_username'),
                html_entity_decode($this->request->get('post.db_password'), ENT_QUOTES, 'UTF-8'),
                $this->request->get('post.db_database'),
                $this->request->getInt('post.db_port')
            );

            if ($mysql->connect_error) {
                $this->error['warning'] = $mysql->connect_error;
            } else {
                $mysql->close();
            }
        } elseif ($this->request->get('post.db_driver') == 'mpdo') {
            try {
                new \DB\mPDO(
                    $this->request->get('post.db_hostname'),
                    $this->request->get('post.db_username'),
                    $this->request->get('post.db_password'),
                    $this->request->get('post.db_database'),
                    $this->request->getInt('post.db_port')
                );
            } catch (Exception $e) {
                $this->error['warning'] = $e->getMessage();
            }
        }

        if (!$this->request->get('post.username')) {
            $this->error['username'] = $this->language->get('error_username');
        }

        if (!$this->request->get('post.password')) {
            $this->error['password'] = $this->language->get('error_password');
        }

        if ((utf8_strlen($this->request->get('post.email')) > 96) || !filter_var($this->request->get('post.email'), FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if (!is_writable(PATH_SHIFT . 'config.php')) {
            $this->error['warning'] = $this->language->get('error_config') . PATH_SHIFT . 'config.php!';
        }

        if (!is_writable(PATH_SHIFT . 'admin/config.php')) {
            $this->error['warning'] = $this->language->get('error_config') . PATH_SHIFT . 'admin/config.php!';
        }

        return !$this->error;
    }
}
