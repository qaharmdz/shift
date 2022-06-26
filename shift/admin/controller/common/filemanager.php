<?php

declare(strict_types=1);

namespace Shift\Admin\Controller\Common;

use Shift\System\Core\Mvc;

class FileManager extends Mvc\Controller
{
    public function index()
    {
        $this->load->language('common/filemanager');

        $filter_name = null;
        if ($this->request->has('query.filter_name')) {
            $filter_name = rtrim(str_replace('*', '', $this->request->get('query.filter_name')), '/');
        }

        // Make sure we have the correct directory
        $directory = DIR_IMAGE . 'catalog';
        if ($this->request->has('query.directory')) {
            $directory = rtrim(DIR_IMAGE . 'catalog/' . str_replace('*', '', $this->request->get('query.directory')), '/');
        }

        if ($this->request->has('query.page')) {
            $page = $this->request->get('query.page');
        } else {
            $page = 1;
        }

        $directories = array();
        $files = array();

        $data['images'] = array();

        $this->load->model('tool/image');

        if (substr(str_replace('\\', DS, realpath($directory . '/' . $filter_name)), 0, strlen(DIR_IMAGE . 'catalog')) == DIR_IMAGE . 'catalog') {
            // Get directories
            $directories = glob($directory . '/' . $filter_name . '*', GLOB_ONLYDIR);

            if (!$directories) {
                $directories = array();
            }

            // Get files
            $files = glob($directory . '/' . $filter_name . '*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}', GLOB_BRACE);

            if (!$files) {
                $files = array();
            }
        }

        // Merge directories and files
        $images = array_merge($directories, $files);

        // Get total number of files and directories
        $image_total = count($images);

        // Split the array based on current page number and max number of items per page of 10
        $images = array_splice($images, ($page - 1) * 16, 16);

        foreach ($images as $image) {
            $name = str_split(basename($image), 14);

            if (is_dir($image)) {
                $url = '';

                if ($this->request->has('query.target')) {
                    $url .= '&target=' . $this->request->get('query.target');
                }

                if ($this->request->has('query.thumb')) {
                    $url .= '&thumb=' . $this->request->get('query.thumb');
                }

                $data['images'][] = array(
                    'thumb' => '',
                    'name'  => implode(' ', $name),
                    'type'  => 'directory',
                    'path'  => utf8_substr($image, utf8_strlen(DIR_IMAGE)),
                    'href'  => $this->url->link('common/filemanager', 'token=' . $this->session->get('token') . '&directory=' . urlencode(utf8_substr($image, utf8_strlen(DIR_IMAGE . 'catalog/'))) . $url, true)
                );
            } elseif (is_file($image)) {
                $data['images'][] = array(
                    'thumb' => $this->model_tool_image->resize(utf8_substr($image, utf8_strlen(DIR_IMAGE)), 100, 100),
                    'name'  => implode(' ', $name),
                    'type'  => 'image',
                    'path'  => utf8_substr($image, utf8_strlen(DIR_IMAGE)),
                    'href'  => $this->config->get('env.url_site') . 'image/' . utf8_substr($image, utf8_strlen(DIR_IMAGE))
                );
            }
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['entry_search'] = $this->language->get('entry_search');
        $data['entry_folder'] = $this->language->get('entry_folder');

        $data['button_parent'] = $this->language->get('button_parent');
        $data['button_refresh'] = $this->language->get('button_refresh');
        $data['button_upload'] = $this->language->get('button_upload');
        $data['button_folder'] = $this->language->get('button_folder');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_search'] = $this->language->get('button_search');

        $data['token'] = $this->session->get('token');

        if ($this->request->has('query.directory')) {
            $data['directory'] = urlencode($this->request->get('query.directory'));
        } else {
            $data['directory'] = '';
        }

        if ($this->request->has('query.filter_name')) {
            $data['filter_name'] = $this->request->get('query.filter_name');
        } else {
            $data['filter_name'] = '';
        }

        // Return the target ID for the file manager to set the value
        if ($this->request->has('query.target')) {
            $data['target'] = $this->request->get('query.target');
        } else {
            $data['target'] = '';
        }

        // Return the thumbnail for the file manager to show a thumbnail
        if ($this->request->has('query.thumb')) {
            $data['thumb'] = $this->request->get('query.thumb');
        } else {
            $data['thumb'] = '';
        }

        // Parent
        $url = '';

        if ($this->request->has('query.directory')) {
            $pos = strrpos($this->request->get('query.directory'), '/');

            if ($pos) {
                $url .= '&directory=' . urlencode(substr($this->request->get('query.directory'), 0, $pos));
            }
        }

        if ($this->request->has('query.target')) {
            $url .= '&target=' . $this->request->get('query.target');
        }

        if ($this->request->has('query.thumb')) {
            $url .= '&thumb=' . $this->request->get('query.thumb');
        }

        $data['parent'] = $this->url->link('common/filemanager', 'token=' . $this->session->get('token') . $url, true);

        // Refresh
        $url = '';

        if ($this->request->has('query.directory')) {
            $url .= '&directory=' . urlencode($this->request->get('query.directory'));
        }

        if ($this->request->has('query.target')) {
            $url .= '&target=' . $this->request->get('query.target');
        }

        if ($this->request->has('query.thumb')) {
            $url .= '&thumb=' . $this->request->get('query.thumb');
        }

        $data['refresh'] = $this->url->link('common/filemanager', 'token=' . $this->session->get('token') . $url, true);

        $url = '';

        if ($this->request->has('query.directory')) {
            $url .= '&directory=' . urlencode(html_entity_decode($this->request->get('query.directory'), ENT_QUOTES, 'UTF-8'));
        }

        if ($this->request->has('query.filter_name')) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get('query.filter_name'), ENT_QUOTES, 'UTF-8'));
        }

        if ($this->request->has('query.target')) {
            $url .= '&target=' . $this->request->get('query.target');
        }

        if ($this->request->has('query.thumb')) {
            $url .= '&thumb=' . $this->request->get('query.thumb');
        }

        $pagination = new \Pagination();
        $pagination->total = $image_total;
        $pagination->page = $page;
        $pagination->limit = 16;
        $pagination->url = $this->url->link('common/filemanager', 'token=' . $this->session->get('token') . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $this->response->setOutput($this->load->view('common/filemanager', $data));
    }

    public function upload()
    {
        $this->load->language('common/filemanager');

        $json = array();

        // Check user has permission
        if (!$this->user->hasPermission('modify', 'common/filemanager')) {
            $json['error'] = $this->language->get('error_permission');
        }

        // Make sure we have the correct directory
        if ($this->request->has('query.directory')) {
            $directory = rtrim(DIR_IMAGE . 'catalog/' . $this->request->get('query.directory'), '/');
        } else {
            $directory = DIR_IMAGE . 'catalog';
        }

        // Check its a directory
        if (!is_dir($directory) || substr(str_replace('\\', DS, realpath($directory)), 0, strlen(DIR_IMAGE . 'catalog')) != DIR_IMAGE . 'catalog') {
            $json['error'] = $this->language->get('error_directory');
        }

        if (!$json) {
            // Check if multiple files are uploaded or just one
            $files = array();

            if (!empty($this->request->get('files.file.name')) && is_array($this->request->get('files.file.name'))) {
                foreach (array_keys($this->request->getArray('files.file.name', [])) as $key) {
                    $files[] = array(
                        'name'     => $this->request->get('files.file.name.' . $key),
                        'type'     => $this->request->get('files.file.type.' . $key),
                        'tmp_name' => $this->request->get('files.file.tmp_name.' . $key),
                        'error'    => $this->request->get('files.file.error.' . $key),
                        'size'     => $this->request->get('files.file.size.' . $key)
                    );
                }
            }

            foreach ($files as $file) {
                if (is_file($file['tmp_name'])) {
                    // Sanitize the filename
                    $filename = basename(html_entity_decode($file['name'], ENT_QUOTES, 'UTF-8'));

                    // Validate the filename length
                    if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 255)) {
                        $json['error'] = $this->language->get('error_filename');
                    }

                    // Allowed file extension types
                    $allowed = array(
                        'jpg',
                        'jpeg',
                        'gif',
                        'png'
                    );

                    if (!in_array(utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1)), $allowed)) {
                        $json['error'] = $this->language->get('error_filetype');
                    }

                    // Allowed file mime types
                    $allowed = array(
                        'image/jpeg',
                        'image/pjpeg',
                        'image/png',
                        'image/x-png',
                        'image/gif'
                    );

                    if (!in_array($file['type'], $allowed)) {
                        $json['error'] = $this->language->get('error_filetype');
                    }

                    // Return any upload error
                    if ($file['error'] != UPLOAD_ERR_OK) {
                        $json['error'] = $this->language->get('error_upload_' . $file['error']);
                    }
                } else {
                    $json['error'] = $this->language->get('error_upload');
                }

                if (!$json) {
                    move_uploaded_file($file['tmp_name'], $directory . '/' . $filename);
                }
            }
        }

        if (!$json) {
            $json['success'] = $this->language->get('text_uploaded');
        }

        $this->response->setOutputJson($json);
    }

    public function folder()
    {
        $this->load->language('common/filemanager');

        $json = array();

        // Check user has permission
        if (!$this->user->hasPermission('modify', 'common/filemanager')) {
            $json['error'] = $this->language->get('error_permission');
        }

        // Make sure we have the correct directory
        if ($this->request->has('query.directory')) {
            $directory = rtrim(DIR_IMAGE . 'catalog/' . $this->request->get('query.directory'), '/');
        } else {
            $directory = DIR_IMAGE . 'catalog';
        }

        // Check its a directory
        if (!is_dir($directory) || substr(str_replace('\\', DS, realpath($directory)), 0, strlen(DIR_IMAGE . 'catalog')) != DIR_IMAGE . 'catalog') {
            $json['error'] = $this->language->get('error_directory');
        }

        if ($this->request->is('POST')) {
            // Sanitize the folder name
            $folder = basename(html_entity_decode($this->request->get('post.folder'), ENT_QUOTES, 'UTF-8'));

            // Validate the filename length
            if ((utf8_strlen($folder) < 3) || (utf8_strlen($folder) > 128)) {
                $json['error'] = $this->language->get('error_folder');
            }

            // Check if directory already exists or not
            if (is_dir($directory . '/' . $folder)) {
                $json['error'] = $this->language->get('error_exists');
            }
        }

        if (!isset($json['error'])) {
            mkdir($directory . '/' . $folder, 0777);
            chmod($directory . '/' . $folder, 0777);

            @touch($directory . '/' . $folder . '/' . 'index.html');

            $json['success'] = $this->language->get('text_directory');
        }

        $this->response->setOutputJson($json);
    }

    public function delete()
    {
        $this->load->language('common/filemanager');

        $json = array();

        // Check user has permission
        if (!$this->user->hasPermission('modify', 'common/filemanager')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if ($this->request->has('post.path')) {
            $paths = $this->request->get('post.path');
        } else {
            $paths = array();
        }

        // Loop through each path to run validations
        foreach ($paths as $path) {
            // Check path exsists
            if ($path == DIR_IMAGE . 'catalog' || substr(str_replace('\\', DS, realpath(DIR_IMAGE . $path)), 0, strlen(DIR_IMAGE . 'catalog')) != DIR_IMAGE . 'catalog') {
                $json['error'] = $this->language->get('error_delete');

                break;
            }
        }

        if (!$json) {
            // Loop through each path
            foreach ($paths as $path) {
                $path = rtrim(DIR_IMAGE . $path, '/');

                // If path is just a file delete it
                if (is_file($path)) {
                    unlink($path);

                // If path is a directory beging deleting each file and sub folder
                } elseif (is_dir($path)) {
                    $files = array();

                    // Make path into an array
                    $path = array($path . '*');

                    // While the path array is still populated keep looping through
                    while (count($path) != 0) {
                        $next = array_shift($path);

                        foreach (glob($next) as $file) {
                            // If directory add to path array
                            if (is_dir($file)) {
                                $path[] = $file . '/*';
                            }

                            // Add the file to the files to be deleted array
                            $files[] = $file;
                        }
                    }

                    // Reverse sort the file array
                    rsort($files);

                    foreach ($files as $file) {
                        // If file just delete
                        if (is_file($file)) {
                            unlink($file);

                        // If directory use the remove directory function
                        } elseif (is_dir($file)) {
                            rmdir($file);
                        }
                    }
                }
            }

            $json['success'] = $this->language->get('text_delete');
        }

        $this->response->setOutputJson($json);
    }
}
