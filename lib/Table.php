<?php namespace Lib;

class Table {

    private $table = '';

    private $headings = [];

    private $data = [];

    private $checkboxes = false;

    private $tableClass = '';

    private $tableId = '';


    /**
     * @return string
     */
    public function __toString()
    {
        return $this->table;
    }


    public function setId($id)
    {
        $this->tableId = $id;

        return $this;
    }

    public function setClass($class)
    {
        $this->tableClass = $class;

        return $this;
    }

    /**
     * @param array $headings
     *
     * @return $this
     */
    public function setHeadings(array $headings)
    {
        $this->headings = $headings;

        return $this;
    }


    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }


    /**
     * @param $checkboxes   boolean
     *
     * @return $this
     */
    public function setCheckboxes($checkboxes)
    {
        $this->checkboxes = $checkboxes;

        return $this;
    }


    /**
     *
     */
    public function render()
    {
        $this->table = '<table id="';
        $this->table .= (! empty($this->tableId)) ? $this->tableId : '';
        $this->table .= '" class="';
        $this->table .= (! empty($this->tableClass)) ? $this->tableClass : '_xiz_table';
        $this->table .= '">';
        $this->table .= '<thead>';
        $this->table .= $this->renderHead();
        $this->table .= '</thead>';
        $this->table .= '<tbody>';
        foreach ($this->data as $item) {
            $this->table .= $this->renderRow($item);
        }
        $this->table .= '</tbody>';
        $this->table .= '</table>';

        return $this->table;
    }


    /**
     * @param array $entry
     * @param string $class
     *
     * @return string
     */
    private function renderRow($entry, $class = '')
    {
        $row = '';

        if (is_object($entry))
            $entry = (array)$entry;

        $useId = (! empty($entry['id'])) ? $entry['id'] : uniqid();

        if ($this->checkboxes) {
            $row .= '<td class="_xiz_checkbox_col"><input type="checkbox" name="_xiz_checked[' . $useId . ']" value="1" /></td>';
        }

        foreach ($this->headings as $headingKey => $aHeading) {
            $row .= '<td id="' . $headingKey . '-' . $aHeading . '"';

            if (array_key_exists($headingKey, $entry)) {
                $row .= '>' . $entry[$headingKey];
            } else {
                $row .= ' class="_xiz_empty">';
            }

            $row .= '</td>';
        }

        if (! empty($class)) {
            return '<tr class="' . $class . '"> ' . $row . '</tr>';
        } else {
            return '<tr> ' . $row . '</tr>';
        }
    }


    /**
     * @return string
     */
    private function renderHead()
    {
        $headings = '';

        if (empty($this->headings)) {
            $this->buildHeadings();
        }

        if ($this->checkboxes) {
            $headings .= '<th width="30" class="_xiz_checkbox_col"><input type="checkbox" id="_xiz_check_all" value="1" /></th>';
        }

        foreach ($this->headings as $aKey => $aHeading) {
            $headings .= '<th>' . $aHeading . '</th>';
        }

        return '<tr>' . $headings . '</tr>';
    }


    /**
     * In the event that no headings were submitted
     * we attempt to build them ourselves.
     */
    private function buildHeadings()
    {
        $temp = [];

        if (is_array($this->data['0'])) {
            $temp = array_keys($this->data['0']);
        }
        else if (is_object($this->data['0'])) {
            $temp = array_keys(get_object_vars($this->data['0']));
        }

        foreach ($temp as $item) {
            $this->headings[$item] = $this->cleanName($item);
        }
    }


    /**
     * @param $string
     *
     * @return string
     */
    private function cleanName($string)
    {
        return ucwords(str_replace('_', ' ', preg_replace("/[^a-zA-Z0-9_]+/", "", $string)));
    }

}