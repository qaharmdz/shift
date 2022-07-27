<?php

declare(strict_types=1);

namespace Shift\System\Library;

use PHPMailer\PHPMailer\PHPMailer;

/**
 * Mail library.
 *
 * @link https://github.com/PHPMailer/PHPMailer
 */
class Mail extends PHPMailer
{
    /**
     * @param bool $exceptions
     */
    public function __construct($exceptions = true)
    {
        parent::__construct($exceptions);
    }

    /**
     * Quick mail setup.
     *
     * @param  array  $param
     */
    public function quickSetup(array $param): void
    {
        switch ($param['engine']) {
            case 'smtp':
                $this->isSMTP();
                $param = array_merge([
                    'smtp_host'     => '',
                    'smtp_username' => '',
                    'smtp_password' => '',
                    'smtp_port'     => 25,
                    'smtp_timeout'  => 300,
                ], $param);

                $this->Host    = $param['smtp_host'];
                $this->Port    = (int)$param['smtp_port'];
                $this->Timeout = (int)$param['smtp_timeout'];

                if ($param['smtp_username'] && $param['smtp_password']) {
                    $this->SMTPAuth = true;
                    $this->Username = $param['smtp_username'];
                    $this->Password = $param['smtp_password'];
                }
                break;

            case 'mail':
            default:
                $this->isMail();
                break;
        }

        $this->CharSet = PHPMailer::CHARSET_UTF8;
    }

    /**
     * Clear all mail settings.
     */
    public function clearAll(): void
    {
        $this->clearCustomHeaders();
        $this->clearAllRecipients();
        $this->clearReplyTos();
        $this->clearAttachments();
    }
}
