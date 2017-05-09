<?php namespace Controller;

use Lib\View;
use Lib\Reply;
use Lib\Session;
use Lib\Ordering;


class Controller {

    protected $app;

    protected $request;

    protected $view;


    /**
     * @param $app
     * @param $request
     */
    public function __construct($app, $request)
    {
        $this->app = $app;

        $this->request = $request;

        $this->view = new View();

        // With code?
        $code = $this->request->getInput('_xiz_code');
        if (! empty($code)) {
            $msg = $this->app->code($code);
            $this->view->data('_xiz_code', $msg);
        }

        $this->reply = (new Reply())
            ->setErrorCodes($this->app->getCodes());

        $this->session = new Session($this->app->config('app_name'));

        $path = $this->request->getPathRaw();
    }


    /**
     * Show the login page.
     */
    public function showLogin()
    {
        $view = $this->view
            ->setView('login')
            ->macro('token', $this->session->getToken())
            ->render();

        echo $this->reply->setMessage($view)->send();
        exit;
    }


    /**
     * Redirect someone
     *
     * @param $url
     * @param $code
     */
    public function redirect($url, $code = '')
    {
        $redirect = $url;

        if (! empty($code))
            $redirect .= '?_xiz_code=' . $code;

        header('Location: ' . $redirect);
        exit;
    }


    /**
     * @return array
     */
    protected function ordering()
    {
        return (new Ordering())
            ->setDisplay($this->request->getInput('display'))
            ->setPage($this->request->getInput('page'))
            ->setOrder($this->request->getInput('order'))
            ->setDirection($this->request->getInput('direction'));
    }


}