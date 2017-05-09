<?php namespace Rules\Fields;

use Rules\FieldRulesContract;

class ProfilePicture extends FieldRulesContract {

    public function get()
    {
        return $this->value;
    }

}