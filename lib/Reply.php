<?php namespace Lib;

class Reply {

    /**
     * @var bool
     */
    public $error = false;

    private $errorCodes = [];

    /**
     * @var string  Status code.
     */
    public $code = '100';

    /**
     * @var string  Status update.
     */
    public $message = '';

    public $debug = [];

    public $notifications = [];
    public $profile = null;
    public $device = null;
    public $source = null;

    /**
     * @var Object  Payload of data.
     */
    public $data;

    /**
     * @var Object  Session data.
     */
    public $session;

    /**
     * @var Object  List of features and status.
     */
    public $appStatus;

    public $requestTime;

    private $format = 'html';

    private $redirect;


    /**
     *
     */
    public function __construct()
    {

    }


    /**
     * @param $codes
     *
     * @return $this
     */
    public function setErrorCodes($codes)
    {
        $this->errorCodes = $codes;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $this->send();
    }


    /**
     * @param $url
     *
     * @return $this
     */
    public function setRedirect($url)
    {
        $this->redirect = $url;

        return $this;
    }

    /**
     * @param $session
     *
     * @return $this
     */
    public function setSession($session)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * @param $type
     * @param $total
     */
    public function setNotification($type, $total)
    {
        $this->notifications[$type] = $total;
    }


    /**
     * @param $profile
     *
     * @return $this
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;

        return $this;
    }


    /**
     * @return string
     */
    public function send()
    {
        $this->requestTime = microtime(true) - BOOTSTRAP_START_TIME;

        if (! empty($this->redirect)) {
            if (! empty($this->data)) {
                if (is_array($this->data) || is_object($this->data)) {
                    $qs = http_build_query($this->data);
                } else {
                    $qs = $this->data;
                }
            } else {
                $qs = '';
            }

            header('Location: ' . $this->redirect . '?' . $qs);
            exit;
        } else {
            switch ($this->format) {
                case 'json':
                    header('Content-Type: application/json');
                    return json_encode($this);
                default:
                    return $this->message;
            }
        }
    }


    /**
     * @param $format
     *
     * @return $this
     */
    public function setFormat($format)
    {
        switch ($format) {
            case 'json':
                $this->format = $format;
                break;
        }

        return $this;
    }


    /**
     * @param $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        if ($code >= 900) {
            $this->error = true;
        }

        $message = $this->errorCodes[$code];

        $this->setMessage($message);

        return $this;
    }


    /**
     * @param $source
     *
     * @return $this
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }


    /**
     * @param $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }


    /**
     * @param $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }


}