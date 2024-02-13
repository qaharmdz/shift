<?php

declare(strict_types=1);

namespace Shift\System\Http;

use Shift\System\Exception;

/**
 * Represents a HTTP response.
 */
class Response {
    protected array $headers = [];
    protected string $output = '';
    protected int $compression = 0;
    protected int $code = 200; // HTTP status code
    protected array $status = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',                        // RFC2518
        103 => 'Early Hints',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',                      // RFC4918
        208 => 'Already Reported',                  // RFC5842
        226 => 'IM Used',                           // RFC3229
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',                // RFC7238
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',                     // RFC2324
        421 => 'Misdirected Request',               // RFC7540
        422 => 'Unprocessable Entity',              // RFC4918
        423 => 'Locked',                            // RFC4918
        424 => 'Failed Dependency',                 // RFC4918
        425 => 'Too Early',                         // RFC-ietf-httpbis-replay-04
        426 => 'Upgrade Required',                  // RFC2817
        428 => 'Precondition Required',             // RFC6585
        429 => 'Too Many Requests',                 // RFC6585
        431 => 'Request Header Fields Too Large',   // RFC6585
        451 => 'Unavailable For Legal Reasons',     // RFC7725
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',           // RFC2295
        507 => 'Insufficient Storage',              // RFC4918
        508 => 'Loop Detected',                     // RFC5842
        510 => 'Not Extended',                      // RFC2774
        511 => 'Network Authentication Required',   // RFC6585
    ];

    public function getInfo(): array
    {
        return [
            'headers'     => $this->headers,
            'code'        => $this->code,
            'status'      => $this->status[$this->code],
            'compression' => $this->compression,
        ];
    }

    /**
     * Set HTTP response status code
     *
     * @param  integer  $code
     */
    public function setCode(int $code)
    {
        if (!isset($this->status[$code])) {
            throw new \InvalidArgumentException(sprintf('Invalid response code "%s".', $code));
        }

        $this->code = $code;
    }

    /**
     * Add response headers.
     *
     * @param  array $headers
     */
    public function addHeaders(array $headers)
    {
        foreach ($headers as $name => $values) {
            $this->setHeader($name, $values);
        }
    }

    /**
     * Set a response header by name.
     *
     * @param  string $name
     * @param  string|int $value
     */
    public function setHeader(string $name, string|int $value)
    {
        $this->headers[$name] = $value;
    }

    /**
     * Remove response header by name.
     *
     * @param  string $name
     * @return $this
     */
    public function removeHeader(string $name): void
    {
        unset($this->headers[$name]);
        header_remove($name);
    }

    /**
     * Get response headers.
     *
     * @return array
     */

    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Set the response output.
     *
     * @param  string   $output
     * @param  integer  $status
     */
    public function setOutput(string $output, int $status = 200)
    {
        $this->setCode($status);
        $this->output = $output;
    }

    /**
     * Set the JSON formatted response output.
     *
     * @param  mixed    $content
     * @param  integer  $status
     */
    public function setOutputJson($content, int $status = 200)
    {
        $this->addHeaders([
            'Content-Type'  => 'application/json',
            'Cache-Control' => 'no-cache, no-store, must-revalidate, post-check=0, pre-check=0, max-age=0, private',
            'Pragma'        => 'no-cache',
            'Expires'       => gmdate('D, d M Y H:i:s T', time() - 3600),
        ]);

        if (!is_array($content)) {
            $content = ['response' => $content];
        }

        $this->setOutput(json_encode($content), $status);
    }

    /**
     * Get the current response output.
     *
     * @return string|null
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Check if response has output.
     *
     * @return bool
     */
    public function hasOutput(): bool
    {
        return $this->output ? true : false;
    }

    /**
     * Set compression level
     *
     * @param  integer  $level
     */
    public function setCompression(int $level)
    {
        $this->compression = min(max($level, 0), 9);
    }

    /**
     * Output compression
     *
     * @param  mixed    $data
     * @param  integer  $level
     */
    public function compress($data, $level = 0)
    {
        if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && str_contains($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
            $encoding = 'gzip';
        }

        if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && str_contains($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip')) {
            $encoding = 'x-gzip';
        }

        if (
            !isset($encoding)
            || ($level < 0 || $level > 9)
            || headers_sent()
            || !extension_loaded('zlib')
            || ini_get('zlib.output_compression')
            || connection_status()
        ) {
            return $data;
        }

        $this->setHeader('Content-Encoding', $encoding);

        return gzencode($data, (int) $level);
    }

    /**
     * Apply HTTP headers and return content.
     *
     * @return  string
     */
    public function send(): string
    {
        if ($output = $this->getOutput()) {
            if ($this->compression) {
                $output = $this->compress($output, $this->compression);
            }

            if (!headers_sent()) {
                header($_SERVER['SERVER_PROTOCOL'] . ' ' . $this->code . ' ' . $this->status[$this->code], true, $this->code);
                header('Date: ' . gmdate('D, d M Y H:i:s T')); // RFC2616 - 14.18 Responses need to have a Date

                foreach ($this->getHeaders() as $name => $value) {
                    header($name . ': ' . $value, true);
                }
            }
        }

        return $output;
    }

    /**
     * Redirects to another URL.
     *
     * 301 Permanently redirect from old url to new url.
     * 302 Forwarding to new url (temporary redirect).
     * 303 In response to a POST, redirect to new url with GET method. Redirect after form submission.
     * 400 Bad request
     * 401 Unauthorized access
     * 403 Forbidden, in response to permission issue
     * 404 Not Found
     * 412 Precondition failed
     * 422 Unprocessable entity, in response to form validation or general error
     * 500 Internal server error
     *
     * @param  string   $url     The URL should be a full URL, with schema etc.
     * @param  integer  $status  The status code (302 by default).
     */
    public function redirect(string $url, int $status = 302)
    {
        header('Location: ' . $url, true, $status);
        $this->terminate();
    }

    /**
     * Download file
     *
     * @param  string $filepath
     * @param  string $filename Downloaded filename
     */
    public function download(string $filepath, string $filename = '')
    {
        $filename = $filename ?: basename(html_entity_decode($filepath, ENT_QUOTES, 'UTF-8'));

        if (is_file($filepath)) {
            header('Pragma: public');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . filesize($filepath));
            header('Content-Transfer-Encoding: binary');

            ob_clean();
            flush();

            readfile($filepath);
        } else {
            throw new Exception\FileNotFoundException(sprintf('File "%s" could not be found.', $filename));
        }

        $this->terminate();
    }

    protected function terminate(string $status = '')
    {
        (PHP_SAPI !== 'cli' || !defined('STDIN')) ? exit($status) : null;
    }
}
