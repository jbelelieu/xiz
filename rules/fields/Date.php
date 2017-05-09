<?php namespace Rules\Fields;

use Rules\FieldRulesContract;

class Date extends FieldRulesContract {

    public function get()
    {
        return date('m/d/Y', $this->value);
    }

}