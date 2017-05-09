<?php namespace Lib;

/**
 * Class Session
 */
class Session {

    private $length = 3600;

    private $rememberLength = 604800;

    private $remember = false;


    /**
     * @param string $sessionName
     */
    public function __construct($sessionName = '')
    {
        if (! empty($sessionName)) {
            session_name($sessionName);
        }

        session_start();

        if (empty($_SESSION['token'])) {
            $this->setToken();
        }
    }


    /**
     * @param $len
     *
     * @return $this
     */
    public function setSessionLength($len)
    {
        $this->length = (is_numeric($len)) ? $len : 604800;

        return $this;
    }


    /**
     * @param $len
     *
     * @return $this
     */
    public function setSessionRememberLength($len)
    {
        $this->rememberLength = (is_numeric($len)) ? $len : 3600;

        return $this;
    }


    /**
     * @return null
     */
    public function getLastLocation()
    {
        return (! empty($_SESSION['last_location'])) ? $_SESSION['last_location'] : null;
    }


    /**
     * @param $path
     *
     * @return $this
     */
    public function setLastLocation($path)
    {
        $_SESSION['last_location'] = $path;

        return $this;
    }


    /**
     * Sets the session length to the "rememberLength" variable.
     *
     * @return $this
     */
    public function remember()
    {
        $this->remember = true;

        $_SESSION['remember'] = true;

        return $this;
    }


    /**
     * Get the security token associated with this session.
     *
     * @return null
     */
    public function getToken()
    {
        return (! empty($_SESSION['token'])) ? $_SESSION['token'] : null;
    }


    /**
     * Set the token for this session.
     *
     * @return $this
     */
    public function setToken($forceToken = '')
    {
        $_SESSION['token'] = (! empty($forceToken)) ? $forceToken : uniqid(true);

        return $this;
    }


    /**
     * Get the ID of the session.
     *
     * @return string
     */
    public function getId()
    {
        return session_id();
    }


    /**
     * @param $lang
     *
     * @return $this
     */
    public function setLanguage($lang)
    {
        $_SESSION['lang'] = $lang;

        return $this;
    }


    /**
     * Get the user ID associated with this session.
     *
     * @return null
     */
    public function getUser()
    {
        return (! empty($_SESSION['user'])) ? $_SESSION['user'] : null;
    }


    /**
     * Get the IP associated with this session.
     *
     * @return null
     */
    public function getIp()
    {
        return (! empty($_SESSION['ip'])) ? $_SESSION['ip'] : null;
    }


    /**
     * Get all elements of the session.
     *
     * @return mixed
     */
    public function getSession()
    {
        return $_SESSION;
    }


    /**
     * Get a session element.
     *
     * @param $key
     *
     * @return null
     */
    public function get($key)
    {
        return (! empty($_SESSION[$key])) ? $_SESSION[$key] : null;
    }


    /**
     * Set a session element.
     *
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;

        return $this;
    }


    /**
     * Check whether a session is valid, and in the process
     * either renew it or clear any existing elements of
     * potentially lingering sessions.
     *
     * @return bool
     */
    public function check()
    {
        if (! empty($_SESSION['logged_in'])) {

            if (! empty($_SESSION['last'])) {
                $length = (! empty($_SESSION['remember'])) ? $this->rememberLength : $this->length;

                $difference = time() - $_SESSION['last'];

                if ($difference >= $length) {
                    $this->end();
                } else {
                    $this->renew();
                }
            } else {
                $this->end();
            }

            return true;
        } else {
            return false;
        }
    }


    /**
     * Renews an existing session.
     *
     * @return $this
     */
    public function renew()
    {
        $_SESSION['logged_in'] = true;
        $_SESSION['last'] = time();

        return $this;
    }


    /**
     * @param string $userId
     *
     * @return $this
     */
    public function start($userId = '')
    {
        $_SESSION['logged_in'] = true;
        $_SESSION['started'] = time();
        $_SESSION['last'] = time();
        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['user'] = (! empty($userId)) ? $userId : null;

        return $this;
    }


    /**
     * @return $this
     */
    public function end()
    {
        $_SESSION['logged_in'] = false;
        $_SESSION['started'] = '';
        $_SESSION['last'] = '';

        return $this;
    }

}