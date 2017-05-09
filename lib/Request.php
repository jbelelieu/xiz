<?php namespace lib;

class Request {

    private $headers;

    private $input;

    private $inputRaw;

    private $method;

    private $path;

    private $pathRaw;

    private $ajax = false;


    /**
     *
     */
    public function __construct()
    {
        $this->headers = getallheaders();

        $this->method = (! empty($_SERVER['REQUEST_METHOD'])) ? $_SERVER['REQUEST_METHOD'] : '';

        $reqUri = (! empty($_SERVER['REQUEST_URI'])) ? explode('?', $_SERVER['REQUEST_URI']) : [];

        if (! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $this->ajax = true;
        } else {
            if (! empty($this->input['_xiz_ajax'])) {
                $this->ajax = true;
            }
        }

        $this->pathRaw = (! empty($reqUri['0'])) ? trim($reqUri['0'],'/') : '';

        $this->path = explode('/', $this->pathRaw);

        $this->input = $_REQUEST;

        $this->inputRaw = file_get_contents('php://input');
    }

    /**
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return bool
     */
    public function isAjax()
    {
        return $this->ajax;
    }

    /**
     * @return mixed
     */
    public function getHeader($key)
    {
        return $this->headers[$key];
    }

    /**
     * @return mixed
     */
    public function getInputs()
    {
        return $this->input;
    }

    /**
     * @param $data
     * @param string $value
     */
    public function setInput($data, $value = '')
    {
        if (is_array($data)) {
            $this->input = $data;
        } else {
            $this->input[$data] = $value;
        }
    }


    /**
     * @return mixed
     */
    public function getInput($key)
    {
        if (array_key_exists($key, $this->input))
            return $this->input[$key];

        return null;
    }

    /**
     * @return mixed
     */
    public function getInputRaw()
    {
        return $this->inputRaw;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getPathRaw()
    {
        return $this->pathRaw;
    }

}