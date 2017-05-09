<?php namespace Rules;

abstract class FieldRulesContract {

    protected $value;


    public function set($value)
    {
        $this->value = $value;

        return $this;
    }


    abstract public function get();

}