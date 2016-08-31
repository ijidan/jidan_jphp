<?php

namespace Jphp\Http;

class Request {
    const HEADER_CLIENT_IP = 'client_ip';
    const HEADER_CLIENT_HOST = 'client_host';
    const HEADER_CLIENT_PROTO = 'client_proto';
    const HEADER_CLIENT_PORT = 'client_port';
    
    const METHOD_HEAD = 'HEAD';
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';
    const METHOD_PURGE = 'PURGE';
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_TRACE = 'TRACE';
    const METHOD_CONNECT = 'CONNECT';
    
    protected static $trustedProxies = array();
    
    /**
     * @var string[]
     */
    protected static $trustedHostPatterns = array();
    
    /**
     * @var string[]
     */
    protected static $trustedHosts = array();
    
    /**
     * Names for headers that can be trusted when
     * using trusted proxies.
     * The default names are non-standard, but widely used
     * by popular reverse proxies (like Apache mod_proxy or Amazon EC2).
     */
    protected static $trustedHeaders = array(
        self::HEADER_CLIENT_IP    => 'X_FORWARDED_FOR',
        self::HEADER_CLIENT_HOST  => 'X_FORWARDED_HOST',
        self::HEADER_CLIENT_PROTO => 'X_FORWARDED_PROTO',
        self::HEADER_CLIENT_PORT  => 'X_FORWARDED_PORT',
    );
    
    protected static $httpMethodParameterOverride = false;
    
    /**
     * Custom parameters.
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     * @api
     */
    public $attributes;
    /**
     * $_POST
     * @var
     */
    public $request;
    /**
     * $_GET
     * @var
     */
    public $query;
    
    /**
     * $_SERVER
     * @var
     */
    public $server;
    /**
     * $_FILES
     * @var
     */
    public $files;
    /**
     * $_COOKIE
     * @var
     */
    public $cookies;
    
    /**
     * Headers (taken from the $_SERVER).
     */
    public $headers;
    
    /**
     * @var string
     */
    protected $content;
    
    /**
     * @var array
     */
    protected $languages;
    
    /**
     * @var array
     */
    protected $charsets;
    
    /**
     * @var array
     */
    protected $encodings;
    
    /**
     * @var array
     */
    protected $acceptableContentTypes;
    
    /**
     * @var string
     */
    protected $pathInfo;
    
    /**
     * @var string
     */
    protected $requestUri;
    
    /**
     * @var string
     */
    protected $baseUrl;
    
    /**
     * @var string
     */
    protected $basePath;
    
    /**
     * @var string
     */
    protected $method;
    
    /**
     * @var string
     */
    protected $format;
    
    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;
    
    /**
     * @var string
     */
    protected $locale;
    
    /**
     * @var string
     */
    protected $defaultLocale = 'en';
    
    /**
     * @var array
     */
    protected static $formats;
    
    protected static $requestFactory;
    
    /**
     * Constructor.
     * @param array $query The GET parameters
     * @param array $request The POST parameters
     * @param array $attributes The request attributes (parameters parsed from the PATH_INFO, ...)
     * @param array $cookies The COOKIE parameters
     * @param array $files The FILES parameters
     * @param array $server The SERVER parameters
     * @param string $content The raw body data
     * @api
     */
    public function __construct(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        $this->initialize($query, $request, $attributes, $cookies, $files, $server, $content);
    }
    
    /**
     * Sets the parameters for this request.
     * This method also re-initializes all properties.
     * @param array $query The GET parameters
     * @param array $request The POST parameters
     * @param array $attributes The request attributes (parameters parsed from the PATH_INFO, ...)
     * @param array $cookies The COOKIE parameters
     * @param array $files The FILES parameters
     * @param array $server The SERVER parameters
     * @param string $content The raw body data
     * @api
     */
    public function initialize(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        $this->request = $request;
        $this->query = $query;
        $this->attributes = $attributes;
        $this->cookies = $cookies;
        $this->files = $files;
        $this->server = $server;
        $this->headers = array();
        
        $this->content = $content;
        $this->languages = null;
        $this->charsets = null;
        $this->encodings = null;
        $this->acceptableContentTypes = null;
        $this->pathInfo = null;
        $this->requestUri = null;
        $this->baseUrl = null;
        $this->basePath = null;
        $this->method = null;
        $this->format = null;
    }
    
    /**
     * @return array|Request|mixed
     */
    public static function createFromGlobals()
    {
        // With the php's bug #66606, the php's built-in web server
        // stores the Content-Type and Content-Length header values in
        // HTTP_CONTENT_TYPE and HTTP_CONTENT_LENGTH fields.
        $server = $_SERVER;
        if ('cli-server' === php_sapi_name()) {
            if (array_key_exists('HTTP_CONTENT_LENGTH', $_SERVER)) {
                $server['CONTENT_LENGTH'] = $_SERVER['HTTP_CONTENT_LENGTH'];
            }
            if (array_key_exists('HTTP_CONTENT_TYPE', $_SERVER)) {
                $server['CONTENT_TYPE'] = $_SERVER['HTTP_CONTENT_TYPE'];
            }
        }
        $request = self::createRequestFromFactory($_GET, $_POST, array(), $_COOKIE, $_FILES, $server);
        return $request;
    }
    
    /**
     * @param array $query
     * @param array $request
     * @param array $attributes
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param null $content
     * @return array|mixed|static
     */
    private static function createRequestFromFactory(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        if (self::$requestFactory) {
            $request = call_user_func(self::$requestFactory, $query, $request, $attributes, $cookies, $files, $server, $content);
            
            if (!$request instanceof Request) {
                throw new \LogicException('The Request factory must return an instance of Symfony\Component\HttpFoundation\Request.');
            }
            
            return $request;
        }
        return new static($query, $request, $attributes, $cookies, $files, $server, $content);
    }
}
