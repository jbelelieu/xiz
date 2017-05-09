<?php namespace Lib;

/**
 * Class Db
 *
 * Builds and executes SQL-based database functionality.
 */
class Db {

    private $connection;

    private $sth;

    protected $query;

    protected $errors = [];

    protected $where = [];

    protected $table = '';

    protected $join = '';

    protected $bindings = [];

    protected $select = [];

    protected $limit = 0;

    protected $limitStart = 0;

    protected $order = '';

    protected $dir = '';

    protected $groupBy = '';

    protected $fetchMode = 'object';

    protected $results;

    protected $error = false;

    protected $errorDetails = '';

    protected $adjective = 'SELECT';

    protected $built = false;


    /**
     * @param \PDO $connection
     */
    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }


    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }


    public function setQuery($query)
    {
        $this->query = $query;

        $this->built = true;

        return $this;
    }


    /**
     * @return array
     */
    public function getBindings()
    {
        return $this->bindings;
    }

    public function from($table)
    {
        return $this->into($table);
    }

    public function into($table)
    {
        $this->table = $table;

        return $this;
    }

    public function groupBy($groupBy)
    {
        $this->groupBy = $groupBy;

        return $this;
    }


    public function setTable($table)
    {
        return $this->into($table);
    }


    /**
     * @param string $format
     *
     * @return mixed|string
     */
    public function getResults($format = 'object')
    {
        switch ($format) {
            case 'json':
                return json_encode($this->results);
            case 'array':
                return json_decode(json_encode($this->results), true);
            default:
                return $this->results;
        }
    }


    /**
     *
     */
    public function clearState()
    {

    }


    /**
     * @param $mode
     *
     * @return $this
     */
    /*
    public function setFetchMode($mode)
    {
        switch (strtolower($mode)) {
            case 'array':
                $this->fetchMode = \PDO::FETCH_ARRAY;
                break;
            default:
                $this->fetchMode = \PDO::FETCH_ASSOC;
                break;
        }

        return $this;
    }
    */


    /**
     * @param $field
     * @param string $direction
     *
     * @return $this
     */
    public function orderBy($field, $direction = 'DESC')
    {
        $exp = explode('.', $field);

        if (sizeof($exp) == 1 && ! empty($this->table)) {
            $this->order = $this->table . '.' . $field;
        } else {
            $this->order = $field;
        }

        switch (strtoupper($direction)) {
            case 'ASC':
                $this->dir = 'ASC';
                break;
            DEFAULT:
                $this->dir = 'DESC';
        }

        return $this;
    }


    /**
     * @param $total
     * @param int $start
     *
     * @return $this
     */
    public function limit($total, $start = 0, $page = '')
    {
        if (is_numeric($total) && $total > 0) {
            $this->limit = $total;
        }

        if (is_numeric($start) && $start > 0) {
            $this->limitStart = $start;
        } else if (! empty($page) && is_numeric($page) && $page > 0) {
            $this->limitStart = ($total * $page) - $total;
        }

        return $this;
    }


    /**
     * @param $table
     * @param $externalKey
     * @param $otherTable
     * @param $internalKey
     * @param string $prefix
     *
     * @return $this
     */
    public function join($table, $externalKey, $otherTable, $internalKey, $prefix = '')
    {
        if (! empty($prefix)) {
            $this->join .= ' ' . $prefix;
        }

        $this->join .= ' JOIN ' . $table . ' ON ' . $table . '.' . $externalKey . '=' . $otherTable . '.' . $internalKey;

        return $this;
    }

    public function leftJoin($table, $externalKey, $otherTable, $internalKey)
    {
        return $this->join($table, $externalKey, $otherTable, $internalKey, 'LEFT');
    }

    public function rightJoin($table, $externalKey, $otherTable, $internalKey)
    {
        return $this->join($table, $externalKey, $otherTable, $internalKey, 'RIGHT');
    }

    public function innerJoin($table, $externalKey, $otherTable, $internalKey)
    {
        return $this->join($table, $externalKey, $otherTable, $internalKey, 'INNER');
    }

    public function outerJoin($table, $externalKey, $otherTable, $internalKey)
    {
        return $this->join($table, $externalKey, $otherTable, $internalKey, 'OUTER');
    }


    /**
     * @param $rules
     * @param string $innerInclusion
     * @param string $outerInclusion
     *
     * @return $this
     */
    public function whereGroup($rules, $innerInclusion = 'OR', $outerInclusion = 'AND')
    {
        $uid = uniqid();

        $this->where['_g_' . $uid] = [
            $rules, $innerInclusion, $outerInclusion
        ];

        return $this;
    }


    /**
     * @param $key
     * @param $eq
     * @param $value
     *
     * @return $this
     */
    public function where($key, $eq, $value)
    {
        $this->where[$key][] = [
            '-', $eq, $value,
        ];

        return $this;
    }


    /**
     * @param $key
     * @param $eq
     * @param $value
     *
     * @return $this
     */
    public function andWhere($key, $eq, $value)
    {
        $this->where[$key][] = [
            'AND', $eq, $value,
        ];

        return $this;
    }


    /**
     * @param $key
     * @param $eq
     * @param $value
     *
     * @return $this
     */
    public function orWhere($key, $eq, $value)
    {
        $this->where[$key][] = [
            'OR', $eq, $value,
        ];

        return $this;
    }


    /**
     * @param $key
     * @param array $values
     * @param string $type
     *
     * @return $this
     */
    public function whereIn($key, array $values, $type = 'AND')
    {
        $this->where[$key][] = [
            'IN', $values, $type,
        ];

        return $this;
    }


    /**
     * @return string
     */
    public function buildWhere()
    {
        $where = '';

        foreach ($this->where as $item => $value) {

            if (substr($item, 0, 3) == '_g_') {

                $where .= ' ' . $value['2'] . ' (';

                $innerWhere = '';
                foreach ($value['0'] as $aThing) {
                    $innerWhere .= ' ' . $value['1'] . ' ' . $this->buildItemName($aThing['0']) . ' ' . $aThing['1'] . " " . $this->bind($aThing['0'], $aThing['2']);
                }

                if ($value['1'] == 'OR') {
                    $innerWhere = substr($innerWhere, 3);
                } else {
                    $innerWhere = substr($innerWhere, 4);
                }

                $where .= $innerWhere . ')';

            } else {

                if (sizeof($value) > 1) {
                    $where .= '(';
                }

                foreach ($value as $options) {

                    if ($options['0'] == '-') {
                        $where .= $this->buildItemName($item) . ' ' . $options['1'] . ' ' . $this->bind($item, $options['2']);
                    }
                    else if ($options['0'] == 'IN') {
                        $where .= ' ' . $this->buildItemName($options['2']) . ' IN (' . $this->buildWhereIn($options['1'], $item) . ')';
                    }
                    else {
                        $where .= ' ' . $options['0'] . ' ' . $this->buildItemName($item) . $options['1'] . $this->bind($item, $options['2']);
                    }
                }

                if (sizeof($value) > 1) {
                    $where .= ')';
                }

            }
        }

        if (empty($where)) {
            $where = '1';
        }

        $where = ltrim($where, ' AND ');
        $where = ltrim($where, ' OR ');

        return $where;
    }


    /**
     * @param $item
     *
     * @return string
     */
    protected function buildItemName($item)
    {
        $exp = explode('.', $item);

        if (sizeof($exp) == 1) {
            return $this->table . '.' . $item;
        } else {
            return $item;
        }
    }


    /**
     * @param array $options
     * @param $item
     *
     * @return string
     */
    protected function buildWhereIn(array $options, $item)
    {
        $whereIn = '';

        foreach ($options as $option) {
            $whereIn .= ",'" . $this->bind($item, $option) . "'";
        }

        return trim($whereIn, ',');
    }


    /**
     * @param $key
     * @param $value
     *
     * @return string
     */
    public function bind($key, $value)
    {
        $id = ':' . uniqid(true);

        $this->bindings[$id] = $value;

        return $id;
    }


    /**
     * @return string
     */
    public function buildOrder()
    {
        if (! empty($this->order)) {
            return ' ORDER BY ' . $this->order . ' ' . $this->dir;
        }

        return '';
    }


    /**
     * @return string
     */
    protected function buildLimit()
    {
        $limit = '';

        if ($this->limit > 0) {
            $limit .= ' LIMIT ' . $this->limit;
        }

        if ($this->limitStart > 0) {
            $limit .= ',' . $this->limitStart;
        }

        return $limit;
    }


    /**
     * @param $group
     *
     * @return $this
     */
    protected function buildGroupBy()
    {
        if (! empty($this->groupBy)) {
            return ' GROUP BY ' . $this->groupBy;
        } else {
            return '';
        }
    }



    /**
     * @return $this
     */
    public function build()
    {
        if ($this->built) return $this;

        $this->query .= $this->table;

        $this->query .= $this->join;

        $this->query .= ' WHERE ' . $this->buildWhere();

        $this->query .= $this->buildGroupBy();

        $this->query .= $this->buildOrder();

        $this->query .= $this->buildLimit();

        $this->built = true;

        return $this;
    }


    /**
     *
     */
    public function run()
    {
        try {
            $this->sth = $this->connection->prepare($this->query);

            if (! empty($this->bindings)) {
                foreach ($this->bindings as $key => $value) {
                    switch ($value) {
                        case is_int($value):
                            $param = \PDO::PARAM_INT;
                            break;
                        case is_bool($value):
                            $param = \PDO::PARAM_BOOL;
                            break;
                        case is_null($value):
                            $param = \PDO::PARAM_NULL;
                            break;
                        case is_string($value):
                            $param = \PDO::PARAM_STR;
                            break;
                        default:
                            $param = false;
                    }

                    $this->sth->bindValue($key, $value, $param);
                }
            }

            $this->sth->execute();
        } catch (PDOException $e) {
            $this->error = true;
            $this->errorDetails = $e->getMessage();
        }
    }


    /**
     * @return mixed
     */
    public function fetch()
    {
        $this->build();

        $this->run();

        // \PDO::FETCH_CLASS
        $this->results = $this->sth->fetchAll(\PDO::FETCH_CLASS);

        if (sizeof($this->results) == 1)
            $this->results = $this->results['0'];

        return $this;
    }


    /**
     * @param $keys
     *
     * @return $this
     */
    public function select($keys)
    {
        if (is_array($keys)) {
            foreach ($keys as $item) {
                if (! in_array($item, $this->select)) {
                    $this->select[] = $item;
                }
            }
        } else {
            if (! in_array($keys, $this->select)) {
                $this->select[] = $keys;
            }
        }

        $this->query = $this->buildSelect() . ' FROM ';

        return $this;
    }


    /**
     * @return string
     */
    protected function buildSelect()
    {
        $select = '';

        foreach ($this->select as $item) {
            $exp = explode('.', $item);

            if (sizeof($exp) == 1) {
                if (empty($this->table)) {
                    $select = $item;
                } else {
                    $select = $this->table . '.' . $item;
                }
            } else {
                $select .= $item;
            }

            $select .= ", ";
        }

        if (empty($select)) {
            if (empty($this->table)) {
                $select = '*';
            } else {
                $select = $this->table . '.*';
            }
        }

        return 'SELECT ' . trim($select, ', ');
    }


    /**
     * @param array $data
     *
     * @return $this
     */
    public function insert(array $data)
    {
        $insertKeys = '';
        $insertValues = '';
        foreach ($data as $key => $value) {
            $insertKeys .= " ,`" . $key . "`";
            $insertValues .= " ," . $this->bind($key, $value);
        }

        $this->query = "INSERT INTO " . $this->table . " (";
        $this->query .= ltrim($insertKeys, ' ,');
        $this->query .= " ) VALUES (";
        $this->query .= ltrim($insertValues, ' ,');
        $this->query .= ")";

        $this->run();

        return $this;
    }


    /**
     * @return $this
     */
    public function update()
    {
        $this->query = 'UPDATE ';

        return $this;
    }


    /**
     * @return $this
     */
    public function delete()
    {
        $this->query = 'DELETE FROM ';

        return $this;
    }

}