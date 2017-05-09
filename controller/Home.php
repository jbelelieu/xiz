<?php namespace Controller;

class Home extends Controller {

    /**
     * Renders the landing page.
     */
    public function home()
    {
        $view = $this->view->setView('home')->render();

        echo $this->reply->setMessage($view)->send();
        exit;
    }

}