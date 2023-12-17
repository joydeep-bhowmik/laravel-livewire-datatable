<?php
namespace JoydeepBhowmik\LivewireDatatable\utils;

class Filter
{
    public $name;
    public $label;
    public $value;
    public $placeholder;
    public $type;
    public $options;
    public $query;
    public $_filter_id;
    public function __construct($name)
    {
        $this->name = $name;
    }

    public function placeholder(string $text)
    {
        $this->placeholder = $text;
        $this->generateFilterId();
        return $this;
    }
    public function label(string $text)
    {
        $this->label = $text;
        $this->generateFilterId();
        return $this;
    }
    public function type(string $text)
    {
        $this->type = $text;
        $this->generateFilterId();
        return $this;
    }
    public function options(array $arr)
    {
        $this->options = $arr;
        $this->generateFilterId();
        return $this;
    }
    public function value($arr)
    {
        $this->value = $arr;
        $this->generateFilterId();
        return $this;
    }
    protected function generateFilterId()
    {
        $this->_filter_id = $this->name . $this->label;
        $this->_filter_id = md5($this->_filter_id);
    }
    public function query($fn)
    {
        $this->query = $fn;
        $this->generateFilterId();
        return $this;
    }
}
