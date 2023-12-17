<?php
namespace JoydeepBhowmik\LivewireDatatable\utils;

class Field
{
    public $table;
    public $name;
    public $label;
    public $value;
    public $searchable;
    public $sortable;
    public $as;
    public $_field_id;

    public function __construct($name)
    {
        $this->name = $name;
    }
    public function label(string $text)
    {
        $this->label = $text;
        $this->generateFieldId();
        return $this;
    }
    public function value($fn)
    {
        $this->value = $fn;
        $this->generateFieldId();
        return $this;
    }
    public function searchable($state = true)
    {
        $this->searchable = $state;
        $this->generateFieldId();
        return $this;
    }
    public function sortable($state = true)
    {
        $this->sortable = $state;
        $this->generateFieldId();
        return $this;
    }
    public function table(string $text)
    {
        $this->table = $text;
        $this->generateFieldId();
        return $this;
    }
    public function as (string $text)
    {
        $this->as = $text;
        $this->generateFieldId();
        return $this;
    }
    protected function generateFieldId()
    {
        $this->_field_id = $this->name . $this->label . $this->as . $this->table;
        $this->_field_id = md5($this->_field_id);
    }
    public function get()
    {
        return $this->field;
    }
}
