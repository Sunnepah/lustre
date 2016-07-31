<?php

namespace Lustre;

use \InvalidArgumentException;

/**
 * Response
 *
 * This class represents an HTTP response. It manages
 * the response status, headers, and body
 */
class Response
{
    /**
     * Status code
     *
     * @var int
     */
    protected $status = 200;

    /**
     * Status text
     *
     * @var string
     */
    protected $statusText = '';

    /**
     * Status codes and status texts pairs
     * The status texts are included, may be in the future it would be better to add the
     * status code text to the response
     *
     * @var array
     */
    protected static $messages = [
        //Informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        //Successful 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',
        //Redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        //Client Error 4xx
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
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        //Server Error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    ];

    /**
     * Response constructor.
     *
     * Create new HTTP response.
     *
     * @param $content
     * @param int $status
     * @param array $headers
     */
    public function __construct($content = null, $status = 200, $headers = array()) {
        $this->status = $this->validateStatus($status);
        http_response_code($this->getStatusCode());

        $this->headers = $headers ? $headers : "";
        $this->body = $content ? $content : "";
    }

    /**
     * Returns the response in json format
     *
     * @return string
     */
    public function json() {

        return  \GuzzleHttp\json_encode($this->body);
    }

    /**
     * Gets the response status code.
     *
     * @return int Status code.
     */
    public function getStatusCode() {
        return $this->status;
    }

    /**
     * Check if HTTP status code defined.
     *
     * @param  int $status HTTP status code.
     * @return int
     * @throws \InvalidArgumentException If an invalid HTTP status code is provided.
     */
    protected function validateStatus($status) {
        if (!array_key_exists($status, self::$messages)) {
            throw new \InvalidArgumentException('Invalid HTTP status code');
        }

        return $status;
    }
}