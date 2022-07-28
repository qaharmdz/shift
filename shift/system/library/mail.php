<?php

declare(strict_types=1);

namespace Shift\System\Library;

use PHPMailer\PHPMailer\PHPMailer;

/**
 * PHPMailer wrapper
 *
 * @link https://github.com/PHPMailer/PHPMailer
 */
class Mail
{
    private array $config = [];

    public function setConfig(array $configuration = [])
    {
        $this->config = array_replace_recursive(
            [
                'mail_engine'   => 'mail',
                'smtp_host'     => '',
                'smtp_username' => '',
                'smtp_password' => '',
                'smtp_port'     => 25,
                'smtp_timeout'  => 300,
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

    public function getInstance()
    {
        $mail = new PHPMailer(true);
        $mail->CharSet = PHPMailer::CHARSET_UTF8;

        switch ($this->getConfig('mail_engine')) {
            case 'smtp':
                $mail->isSMTP();

                $mail->Host    = $this->getConfig('smtp_host');
                $mail->Port    = (int)$this->getConfig('smtp_port');
                $mail->Timeout = (int)$this->getConfig('smtp_timeout');

                if ($this->getConfig('smtp_username') && $this->getConfig('smtp_password')) {
                    $mail->SMTPAuth = true;
                    $mail->Username = $this->getConfig('smtp_username');
                    $mail->Password = $this->getConfig('smtp_password');
                }
                break;

            case 'mail':
            default:
                $mail->isMail();
                break;
        }

        return $mail;
    }
}
