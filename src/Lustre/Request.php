<?php

namespace Lustre;

/**
 * The HTTP request with basic request properties
*/

class Request
{
    /**
     * @var array Query string parameters
     */
    public $query;

    /**
     * @var array Post parameters
     */
    public $data;

    /**
     * @var string URL being requested
     */
    public $url;

    /**
     * @var string request path
     */
    public $path;

    /**
     * @var string Parent subdirectory of the URL
     */
    public $base;

    /**
     * @var string Request method (GET, POST, PUT, DELETE)
     */
    public $method;

    /**
     * @var string Content type
     */
    public $type;

    /**
     * Request Constructor.
     *
     * @param array $params Request information
     */
    public function __construct($params = array()) {

        if (count($params) == 0) {
            $params = [
                'query'    => $_GET,
                'data'     => $_POST,
                'url'      => $_SERVER['REQUEST_URI'],
                'base'     => $_SERVER['SCRIPT_NAME'],
                'method'   => self::getHttpMethod()
            ];
        }
        
        $this->init($params);
    }

    /**
     * @param array $properties
     */
    public function init($properties = []) {
        foreach ($properties as $key => $value) {
            $this->$key = $value;
        }

        // Get the requested URL without the base directory
        if ($this->base != '/' && strlen($this->base) > 0 && strpos($this->url, $this->base) === 0) {
            $this->url = substr($this->url, strlen($this->base));
        }
        
        $this->path = self::getPathInfo();
        $this->type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : "";

        // Default url
        if (empty($this->url)) {
            $this->url = '/';
        } 
        // Merge URL query parameters with $_GET
        else {
            $params = array();

            // Parse a URL and return its components
            $args = parse_url($this->url);
            if (isset($args['query'])) {
                parse_str($args['query'], $params);
            }
            array_merge($_GET, $params);

            $this->query = $_GET;
        }

        // Check for JSON input
        if (strpos($this->type, 'application/json') === 0) {
            $body = $this->getRequestBody();
            
            if ($body != '') {
                $data = json_decode($body, true);
                if ($data != null) {
                    $this->data = $data;
                }
            }
        }
    }

    /**
     * Get the HTTP request method
     * @return string
     */
    public static function getHttpMethod() {
        $method = ($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';

        if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
            $method = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];
        }
        elseif (isset($_REQUEST['_method'])) {
            $method = $_REQUEST['_method'];
        }

        return strtoupper($method);
    }

    /**
     * Get the current HTTP path info.
     *
     * @return string
     */
    public function getPathInfo()
    {
        return $this->path = parse_url($_SERVER['REQUEST_URI'])['path'];
    }

    /**
     * Parse query parameters from a URL.
     *
     * @param string $url URL string
     * @return array Query parameters
     */
    public static function parseQuery($url) {
        $params = array();

        $args = parse_url($url);
        if (isset($args['query'])) {
            parse_str($args['query'], $params);
        }

        return $params;
    }

    /**
     * Gets request body.
     *
     * @return string Raw HTTP request body
     */
    public static function getRequestBody() {
        static $body;

        if (!is_null($body)) {
            return $body;
        }

        $method = self::getHttpMethod();

        if ($method == 'POST' || $method == 'PUT' || $method == 'PATCH') {
            $body = file_get_contents('php://input');
        }

        return $body;
    }

}