<?php namespace Lib;

class Ordering {

    public $display = 50;

    public $page = 1;

    public $order = 'created_at';

    public $direction = 'DESC';


    public function __construct()
    {
        if (! empty($_GET['display']))
            $this->setDisplay($_GET['display']);

        if (! empty($_GET['page']))
            $this->setPage($_GET['page']);

        if (! empty($_GET['direction']))
            $this->setDirection($_GET['direction']);

        if (! empty($_GET['order']))
            $this->setOrder($_GET['order']);
    }


    /**
     * @param $display
     *
     * @return $this
     */
    public function setDisplay($display)
    {
        if (! empty($display) && is_numeric($display) && $display > 0)
            $this->display = $display;

        return $this;
    }


    /**
     * @param $page
     *
     * @return $this
     */
    public function setPage($page)
    {
        if (! empty($page) && is_numeric($page) && $page > 0)
            $this->page = $page;

        return $this;
    }


    /**
     * @param $order
     *
     * @return $this
     */
    public function setOrder($order)
    {
        if (! empty($order))
            $this->order = $order;

        return $this;
    }


    /**
     * @param $direction
     *
     * @return $this
     */
    public function setDirection($direction)
    {
        switch (strtoupper($direction)) {
            case 'ASC':
                $this->direction = 'ASC';
                break;
            default:
                $this->direction = 'DESC';
        }

        return $this;
    }
}