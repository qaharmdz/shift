<?php

declare(strict_types=1);

namespace Shift\System\Core;

class Logger
{
    private array $config = [];

    public function __construct(array $config = [])
    {
        $this->setConfig($config);

        $logfile = $this->config['path'] . $this->config['logfile'];
        if (!is_file($logfile)) {
            file_put_contents($logfile, '');
        }
    }

    public function setConfig(array $configuration = [])
    {
        $this->config = array_replace_recursive(
            [
                'path'    => PATH_TEMP . 'logs/',
                'logfile' => 'log-' . date('Y-m') . '.log',
                'display' => false
            ],
            $this->config,
            $configuration
        );
    }

    public function getConfig($key = null, $default = null)
    {
        if (!$key) {
            return $this->config;
        }

        return $this->config[$key] ?? $default;
    }

    public function write($message, string $level = 'Debug', array $context = [], string $logfile = '')
    {
        $context = $context ?: $this->contextInfo();
        $message = sprintf('%s | %s ' . PHP_EOL . '    | %s ' . PHP_EOL . '    | %s', date('Y-m-d H:i:s e'), $level, print_r($message, true), json_encode($context)) . PHP_EOL;
        $output  = $this->config['path'] . ($logfile ?: $this->config['logfile']);

        $handle = fopen($output, 'a');
        fwrite($handle, $message);
        fclose($handle);
    }

    public function clear(string $logfile = '')
    {
        $output  = $this->config['path'] . ($logfile ?: $this->config['logfile']);
        $handle = fopen($output, 'w+');
        fclose($handle);
    }

    public function errorHandler($errno, $errstr, $errfile, $errline, $errcode = 0)
    {
        $level = $this->errorLevel($errno);

        $this->write($errstr . ' in ' . $errfile . ' on line ' . $errline, $level, $this->contextInfo(), $this->config['logfile']);

        if ($this->config['display'] && !$this->isAjax()) {
            echo '<div style="font-size:14px; color:#444; line-height:1.5em; padding:10px 15px; margin:15px; background:#fee8e6; border-left:4px solid #db180a;">
                <h3 style="font-size:1.6em; color:#c00; font-weight:600; margin:0 0 5px;">' . ucfirst($level) . '</h3>'
                . ($errcode ? '<p style="margin:0;"><b style="width:100px; display:inline-block;">Error Code</b> : ' . $errcode . '</p>' : '')
                . '<p style="margin:0;"><b style="width:100px; display:inline-block;">Message</b> : ' . str_replace(PATH_SHIFT, '', $errstr) . '</p>'
                . '<p style="margin:0;"><b style="width:100px; display:inline-block;">File</b> : ' . str_replace(PATH_SHIFT, '', $errfile) . '</p>'
                . '<p style="margin:0;"><b style="width:100px; display:inline-block;">Line</b> : ' . $errline . '</p>'
            . '</div>';
        }
    }

    public function exceptionHandler(\Throwable $e)
    {
        $this->errorHandler($e, $e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode());
    }

    /**
     * Catch fatal error.
     */
    public function shutdownHandler($error = [])
    {
        $error = $error ?: error_get_last();

        if (!empty($error) && $error['type'] === E_ERROR) {
            $this->errorHandler($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }

    protected function errorLevel($errno): string
    {
        if ($errno instanceof \Throwable) {
            return get_class($errno);
        }

        switch ($errno) {
            case E_ERROR:
            case E_USER_ERROR:
                return 'Fatal Error';
            case E_WARNING:
            case E_USER_WARNING:
                return 'Warning';
            case E_NOTICE:
            case E_USER_NOTICE:
                return 'Notice';
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                return 'Deprecated';
            default:
                return 'Error';
        }
    }

    protected function contextInfo()
    {
        return [
            'method'     => $_SERVER['REQUEST_METHOD'],
            'url'        => $_SERVER['PROTOCOL'] . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
            'ip_address' => $_SERVER['REMOTE_ADDR'],
        ];
    }

    protected function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
