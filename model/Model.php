<?php namespace Model;

use Lib\Db;

class Model {

    protected $connection;

    protected $db;

    protected $table;

    protected $ordering;


    public function __construct(\PDO $connection)
    {
        $this->db = new Db($connection);
    }


    /**
     * Set the ordering, limits, etc. based on an
     * Ordering object.
     *
     * @param \Lib\Ordering $ordering
     *
     * @return $this
     */
    public function setOrdering(\Lib\Ordering $ordering)
    {
        $this->ordering = $ordering;

        return $this;
    }


    /**
     * Get a single entry within a table.
     *
     * @param $id
     * @param string $idKey
     *
     * @return mixed
     */
    public function get($id, $idKey = 'id')
    {
        return $this->db
            ->select('*')
            ->from($this->table)
            ->where($idKey, '=', $id)
            ->fetch()
            ->getResults();
    }


    /**
     * Get a list of users in a table, matching input ordering.
     *
     * @return mixed
     */
    public function getList()
    {
        return $this->db
            ->select('*')
            ->from($this->table)
            ->orderBy($this->ordering->order, $this->ordering->direction)
            ->limit($this->ordering->display, '', $this->ordering->page)
            ->fetch()
            ->getResults();
    }


    /**
     * Total results
     *
     * @return mixed
     */
    public function total()
    {
        $total = $this->db
            ->select('COUNT(*) AS total')
            ->from($this->table)
            ->fetch()
            ->getResults();

        return $total->total;
    }

}