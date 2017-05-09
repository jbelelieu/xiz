<?php namespace Lib;

class Pagination {

    private $total = 0;

    private $perPage = 50;

    private $totalPages = 1;

    private $rendered;

    private $currentPage = 1;

    private $ordering;

    private $url;


    public function __construct(\Lib\Ordering $ordering)
    {
        $this->ordering = $ordering;
        $this->setPage($ordering->page);
        $this->setPerPage($ordering->display);

        $this->url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->rendered;
    }

    /**
     * @param $url
     *
     * @return mixed
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $url;
    }


    /**
     * @param $page
     *
     * @return $this
     */
    public function setPage($page)
    {
        if (is_numeric($page))
            $this->currentPage = $page;

        return $this;
    }

    /**
     * @param $total
     *
     * @return $this
     */
    public function setTotal($total)
    {
        if (is_numeric($total))
            $this->total = $total;

        return $this;
    }

    /**
     * @param $perPage
     *
     * @return $this
     */
    public function setPerPage($perPage)
    {
        if (is_numeric($perPage))
            $this->perPage = $perPage;

        return $this;
    }

    /**
     *
     */
    public function render()
    {
        $this->totalPages = $temp = ceil($this->total / $this->perPage);

        $lastLink = $this->url($this->totalPages);
        $firstLink = $this->url(1);
        $previousLink = $this->url($this->currentPage - 1);
        $nextPage = $this->url($this->currentPage + 1);

        if ($this->totalPages == 1) {
            $this->rendered .= '<span class="first">| &laquo;</span>';
            $this->rendered .= '<span class="previous">&laquo;</span>';
            $next = '<span class="next">&raquo;</span>';
            $last = '<span class="last">&raquo; |</span>';
        }
        else if ($this->totalPages == $this->currentPage) {
            $this->rendered .= '<span class="first"><a href="' . $firstLink . '">| &laquo;</a></span>';
            $this->rendered .= '<span class="previous"><a href="' . $previousLink . '">&laquo;</a></span>';
            $next = '<span class="next">&raquo;</span>';
            $last = '<span class="last"><a href="' . $lastLink . '">&raquo; |</a></span>';
        }
        else if ($this->currentPage == 1) {
            $this->rendered .= '<span class="first">| &laquo;</span>';
            $this->rendered .= '<span class="previous">&laquo;</span>';
            $next = '<span class="next"><a href="' . $nextPage . '">&raquo;</a></span>';
            $last = '<span class="last"><a href="' . $lastLink . '">&raquo; |</a></span>';
        }
        else {
            $this->rendered .= '<span class="first"><a href="' . $firstLink . '">| &laquo;</a></span>';
            $this->rendered .= '<span class="previous"><a href="' . $previousLink . '">&laquo;</a></span>';
            $next = '<span class="next"><a href="' . $nextPage . '">&raquo;</a></span>';
            $last = '<span class="last"><a href="' . $lastLink . '">&raquo; |</a></span>';
        }

        $up = 0;
        while ($temp > 0) {
            $up++;

            $this->rendered .= '<span';

            if ($this->currentPage == $up) {
                $this->rendered .= ' class="current"';
            }

            $this->rendered .= '>';
            $this->rendered .= '<a href="' . $this->url($up) . '">' . $up . '</a>';
            $this->rendered .= '</span>';

            $temp--;
        }

        $this->rendered .= $next;
        $this->rendered .= $last;

        return $this->rendered;
    }


    /**
     * @param int $page
     *
     * @return string
     */
    private function url($page = 1)
    {
        $qs = 'display=' . $this->ordering->display;
        $qs .= '&order=' . $this->ordering->order;
        $qs .= '&direction=' . $this->ordering->direction;
        $qs .= '&page=' . $page;

        return $this->url . '?' . $qs;
    }

}