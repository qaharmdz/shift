<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Extension;

use Shift\System\Mvc;

class Installer extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('extension/installer');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->router->url('common/dashboard', 'token=' . $this->session->get('token'))
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->router->url('extension/installer', 'token=' . $this->session->get('token'))
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_upload'] = $this->language->get('text_upload');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['entry_upload'] = $this->language->get('entry_upload');
        $data['entry_overwrite'] = $this->language->get('entry_overwrite');
        $data['entry_progress'] = $this->language->get('entry_progress');

        $data['help_upload'] = $this->language->get('help_upload');

        $data['button_upload'] = $this->language->get('button_upload');
        $data['button_clear'] = $this->language->get('button_clear');
        $data['button_continue'] = $this->language->get('button_continue');

        $data['token'] = $this->session->get('token');

        $directories = glob(PATH_TEMP . 'upload' . DS . 'temp-*', GLOB_ONLYDIR);

        if ($directories) {
            $data['error_warning'] = $this->language->get('error_temporary');
        } else {
            $data['error_warning'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/installer', $data));
    }

    public function upload()
    {
        $this->load->language('extension/installer');

        $json = array();

        // Check user has permission
        if (!$this->user->hasPermission('modify', 'extension/installer')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if (!$json) {
            if (!empty($this->request->get('files.file.name'))) {
                if (substr($this->request->get('files.file.name'), -10) != '.ocmod.zip' && substr($this->request->get('files.file.name'), -10) != '.ocmod.xml') {
                    $json['error'] = $this->language->get('error_filetype');
                }

                if ($this->request->get('files.file.error') != UPLOAD_ERR_OK) {
                    $json['error'] = $this->language->get('error_upload_' . $this->request->get('files.file.error'));
                }
            } else {
                $json['error'] = $this->language->get('error_upload');
            }
        }

        if (!$json) {
            // If no temp directory exists create it
            $path = 'temp-' . $this->secure->token();

            if (!is_dir(PATH_TEMP . 'upload' . DS . $path)) {
                mkdir(PATH_TEMP . 'upload' . DS . $path, 0777);
            }

            // Set the steps required for installation
            $json['step'] = array();
            $json['overwrite'] = array();

            if (strrchr($this->request->get('files.file.name'), '.') == '.xml') {
                $file = PATH_TEMP . 'upload' . DS . $path . '/install.xml';

                // If xml file copy it to the temporary directory
                move_uploaded_file($this->request->get('files.file.tmp_name'), $file);

                if (file_exists($file)) {
                    $json['step'][] = array(
                        'text' => $this->language->get('text_xml'),
                        'url'  => str_replace('&amp;', '&', $this->router->url('extension/installer/xml', 'token=' . $this->session->get('token'))),
                        'path' => $path
                    );

                    // Clear temporary files
                    $json['step'][] = array(
                        'text' => $this->language->get('text_remove'),
                        'url'  => str_replace('&amp;', '&', $this->router->url('extension/installer/remove', 'token=' . $this->session->get('token'))),
                        'path' => $path
                    );
                } else {
                    $json['error'] = $this->language->get('error_file');
                }
            }

            // If zip file copy it to the temp directory
            if (strrchr($this->request->get('files.file.name'), '.') == '.zip') {
                $file = PATH_TEMP . 'upload' . DS . $path . '/upload.zip';

                move_uploaded_file($this->request->get('files.file.tmp_name'), $file);

                if (file_exists($file)) {
                    $zip = zip_open($file);

                    if ($zip) {
                        // Zip
                        $json['step'][] = array(
                            'text' => $this->language->get('text_unzip'),
                            'url'  => str_replace('&amp;', '&', $this->router->url('extension/installer/unzip', 'token=' . $this->session->get('token'))),
                            'path' => $path
                        );

                        // FTP
                        $json['step'][] = array(
                            'text' => $this->language->get('text_ftp'),
                            'url'  => str_replace('&amp;', '&', $this->router->url('extension/installer/ftp', 'token=' . $this->session->get('token'))),
                            'path' => $path
                        );

                        // Send make and array of actions to carry out
                        while ($entry = zip_read($zip)) {
                            $zip_name = zip_entry_name($entry);

                            // SQL
                            if (substr($zip_name, 0, 11) == 'install.sql') {
                                $json['step'][] = array(
                                    'text' => $this->language->get('text_sql'),
                                    'url'  => str_replace('&amp;', '&', $this->router->url('extension/installer/sql', 'token=' . $this->session->get('token'))),
                                    'path' => $path
                                );
                            }

                            // XML
                            if (substr($zip_name, 0, 11) == 'install.xml') {
                                $json['step'][] = array(
                                    'text' => $this->language->get('text_xml'),
                                    'url'  => str_replace('&amp;', '&', $this->router->url('extension/installer/xml', 'token=' . $this->session->get('token'))),
                                    'path' => $path
                                );
                            }

                            // PHP
                            if (substr($zip_name, 0, 11) == 'install.php') {
                                $json['step'][] = array(
                                    'text' => $this->language->get('text_php'),
                                    'url'  => str_replace('&amp;', '&', $this->router->url('extension/installer/php', 'token=' . $this->session->get('token'))),
                                    'path' => $path
                                );
                            }

                            // Compare admin files
                            $file = PATH_APP . substr($zip_name, 13);

                            if (is_file($file) && substr($zip_name, 0, 13) == 'upload/admin/') {
                                $json['overwrite'][] = substr($zip_name, 7);
                            }

                            // Compare catalog files
                            $file = PATH_SITE . substr($zip_name, 15);

                            if (is_file($file) && substr($zip_name, 0, 15) == 'upload/catalog/') {
                                $json['overwrite'][] = substr($zip_name, 7);
                            }

                            // Compare image files
                            $file = PATH_MEDIA . substr($zip_name, 13);

                            if (is_file($file) && substr($zip_name, 0, 13) == 'upload/image/') {
                                $json['overwrite'][] = substr($zip_name, 7);
                            }

                            // Compare system files
                            $file = PATH_SYSTEM . substr($zip_name, 14);

                            if (is_file($file) && substr($zip_name, 0, 14) == 'upload/system/') {
                                $json['overwrite'][] = substr($zip_name, 7);
                            }
                        }

                        // Clear temporary files
                        $json['step'][] = array(
                            'text' => $this->language->get('text_remove'),
                            'url'  => str_replace('&amp;', '&', $this->router->url('extension/installer/remove', 'token=' . $this->session->get('token'))),
                            'path' => $path
                        );

                        zip_close($zip);
                    } else {
                        $json['error'] = $this->language->get('error_unzip');
                    }
                } else {
                    $json['error'] = $this->language->get('error_file');
                }
            }
        }

        $this->response->setOutputJson($json);
    }

    public function unzip()
    {
        $this->load->language('extension/installer');

        $json = array();

        if (!$this->user->hasPermission('modify', 'extension/installer')) {
            $json['error'] = $this->language->get('error_permission');
        }

        // Sanitize the filename
        $file = PATH_TEMP . 'upload' . DS . $this->request->get('post.path', '') . '/upload.zip';

        if (!is_file($file) || substr(str_replace('\\', DS, realpath($file)), 0, strlen(PATH_TEMP . 'upload' . DS)) != PATH_TEMP . 'upload' . DS) {
            $json['error'] = $this->language->get('error_file');
        }

        if (!$json) {
            // Unzip the files
            $zip = new ZipArchive();

            if ($zip->open($file)) {
                $zip->extractTo(PATH_TEMP . 'upload' . DS . $this->request->get('post.path', ''));
                $zip->close();
            } else {
                $json['error'] = $this->language->get('error_unzip');
            }

            // Remove Zip
            unlink($file);
        }

        $this->response->setOutputJson($json);
    }

    public function ftp()
    {
        $this->load->language('extension/installer');

        $json = array();

        if (!$this->user->hasPermission('modify', 'extension/installer')) {
            $json['error'] = $this->language->get('error_permission');
        }

        // Check FTP status
        if (!$this->config->get('system.setting.ftp_status')) {
            $json['error'] = $this->language->get('error_ftp_status');
        }

        $directory = PATH_TEMP . 'upload' . DS . $this->request->get('post.path', '') . '/upload/';

        if (!is_dir($directory) || substr(str_replace('\\', DS, realpath($directory)), 0, strlen(PATH_TEMP . 'upload' . DS)) != PATH_TEMP . 'upload' . DS) {
            $json['error'] = $this->language->get('error_directory');
        }

        if (!$json) {
            // Get a list of files ready to upload
            $files = array();

            $path = array($directory . '*');

            while (count($path) != 0) {
                $next = array_shift($path);

                foreach ((array)glob($next) as $file) {
                    if (is_dir($file)) {
                        $path[] = $file . '/*';
                    }

                    $files[] = $file;
                }
            }

            // Connect to the site via FTP
            $connection = ftp_connect($this->config->get('system.setting.ftp_hostname'), $this->config->get('system.setting.ftp_port'));

            if ($connection) {
                $login = ftp_login($connection, $this->config->get('system.setting.ftp_username'), $this->config->get('system.setting.ftp_password'));

                if ($login) {
                    if ($this->config->get('system.setting.ftp_root')) {
                        $root = ftp_chdir($connection, $this->config->get('system.setting.ftp_root'));
                    } else {
                        $root = ftp_chdir($connection, '/');
                    }

                    if ($root) {
                        foreach ($files as $file) {
                            $destination = substr($file, strlen($directory));

                            // Upload everything in the upload directory
                            // Many people rename their admin folder for security purposes which I believe should be an option during installation just like setting the db prefix.
                            // the following code would allow you to change the name of the following directories and any extensions installed will still go to the right directory.
                            if (substr($destination, 0, 5) == 'admin') {
                                $destination = basename(PATH_APP) . substr($destination, 5);
                            }

                            if (substr($destination, 0, 7) == 'catalog') {
                                $destination = basename(PATH_SITE) . substr($destination, 7);
                            }

                            if (substr($destination, 0, 5) == 'image') {
                                $destination = basename(PATH_MEDIA) . substr($destination, 5);
                            }

                            if (substr($destination, 0, 6) == 'system') {
                                $destination = basename(PATH_SYSTEM) . substr($destination, 6);
                            }

                            if (is_dir($file)) {
                                $lists = ftp_nlist($connection, substr($destination, 0, strrpos($destination, '/')));

                                // Basename all the directories because on some servers they don't return the fulll paths.
                                $list_data = array();

                                foreach ($lists as $list) {
                                    $list_data[] = basename($list);
                                }

                                if (!in_array(basename($destination), $list_data)) {
                                    if (!ftp_mkdir($connection, $destination)) {
                                        $json['error'] = sprintf($this->language->get('error_ftp_directory'), $destination);
                                    }
                                }
                            }

                            if (is_file($file)) {
                                if (!ftp_put($connection, $destination, $file, FTP_BINARY)) {
                                    $json['error'] = sprintf($this->language->get('error_ftp_file'), $file);
                                }
                            }
                        }
                    } else {
                        $json['error'] = sprintf($this->language->get('error_ftp_root'), $root);
                    }
                } else {
                    $json['error'] = sprintf($this->language->get('error_ftp_login'), $this->config->get('system.setting.ftp_username'));
                }

                ftp_close($connection);
            } else {
                $json['error'] = sprintf($this->language->get('error_ftp_connection'), $this->config->get('system.setting.ftp_hostname'), $this->config->get('system.setting.ftp_port'));
            }
        }

        $this->response->setOutputJson($json);
    }

    public function sql()
    {
        $this->load->language('extension/installer');

        $json = array();

        if (!$this->user->hasPermission('modify', 'extension/installer')) {
            $json['error'] = $this->language->get('error_permission');
        }

        $file = PATH_TEMP . 'upload' . DS . $this->request->get('post.path', '') . '/install.sql';

        if (!is_file($file) || substr(str_replace('\\', DS, realpath($file)), 0, strlen(PATH_TEMP . 'upload' . DS)) != PATH_TEMP . 'upload' . DS) {
            $json['error'] = $this->language->get('error_file');
        }

        if (!$json) {
            $lines = file($file);

            if ($lines) {
                try {
                    $sql = '';

                    foreach ($lines as $line) {
                        if ($line && (substr($line, 0, 2) != '--') && (substr($line, 0, 1) != '#')) {
                            $sql .= $line;

                            if (preg_match('/;\s*$/', $line)) {
                                $sql = str_replace(" `oc_", " `" . DB_PREFIX, $sql);

                                $this->db->query($sql);

                                $sql = '';
                            }
                        }
                    }
                } catch(Exception $exception) {
                    $json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
                }
            }
        }

        $this->response->setOutputJson($json);
    }

    public function xml()
    {
        $this->load->language('extension/installer');

        $json = array();

        if (!$this->user->hasPermission('modify', 'extension/installer')) {
            $json['error'] = $this->language->get('error_permission');
        }

        $this->response->setOutputJson($json);
    }

    public function php()
    {
        $this->load->language('extension/installer');

        $json = array();

        if (!$this->user->hasPermission('modify', 'extension/installer')) {
            $json['error'] = $this->language->get('error_permission');
        }

        $file = PATH_TEMP . 'upload' . DS . $this->request->get('post.path', '') . '/install.php';

        if (!is_file($file) || substr(str_replace('\\', DS, realpath($file)), 0, strlen(PATH_TEMP . 'upload' . DS)) != PATH_TEMP . 'upload' . DS) {
            $json['error'] = $this->language->get('error_file');
        }

        if (!$json) {
            try {
                include($file);
            } catch(Exception $exception) {
                $json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
            }
        }

        $this->response->setOutputJson($json);
    }

    public function remove()
    {
        $this->load->language('extension/installer');

        $json = array();

        if (!$this->user->hasPermission('modify', 'extension/installer')) {
            $json['error'] = $this->language->get('error_permission');
        }

        $directory = PATH_TEMP . 'upload' . DS . $this->request->get('post.path', '');

        if (!is_dir($directory) || substr(str_replace('\\', DS, realpath($directory)), 0, strlen(PATH_TEMP . 'upload' . DS)) != PATH_TEMP . 'upload' . DS) {
            $json['error'] = $this->language->get('error_directory');
        }

        if (!$json) {
            // Get a list of files ready to upload
            $files = array();

            $path = array($directory);

            while (count($path) != 0) {
                $next = array_shift($path);

                // We have to use scandir function because glob will not pick up dot files.
                foreach (array_diff(scandir($next), array('.', '..')) as $file) {
                    $file = $next . '/' . $file;

                    if (is_dir($file)) {
                        $path[] = $file;
                    }

                    $files[] = $file;
                }
            }

            rsort($files);

            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);

                } elseif (is_dir($file)) {
                    rmdir($file);
                }
            }

            if (file_exists($directory)) {
                rmdir($directory);
            }

            $json['success'] = $this->language->get('text_success');
        }

        $this->response->setOutputJson($json);
    }

    public function clear()
    {
        $this->load->language('extension/installer');

        $json = array();

        if (!$this->user->hasPermission('modify', 'extension/installer')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if (!$json) {
            $directories = glob(PATH_TEMP . 'upload' . DS . 'temp-*', GLOB_ONLYDIR);

            if ($directories) {
                foreach ($directories as $directory) {
                    // Get a list of files ready to upload
                    $files = array();

                    $path = array($directory);

                    while (count($path) != 0) {
                        $next = array_shift($path);

                        // We have to use scandir function because glob will not pick up dot files.
                        foreach (array_diff(scandir($next), array('.', '..')) as $file) {
                            $file = $next . '/' . $file;

                            if (is_dir($file)) {
                                $path[] = $file;
                            }

                            $files[] = $file;
                        }
                    }

                    rsort($files);

                    foreach ($files as $file) {
                        if (is_file($file)) {
                            unlink($file);
                        } elseif (is_dir($file)) {
                            rmdir($file);
                        }
                    }

                    if (file_exists($directory)) {
                        rmdir($directory);
                    }
                }
            }

            $json['success'] = $this->language->get('text_clear');
        }

        $this->response->setOutputJson($json);
    }
}
