<?php
namespace JoydeepBhowmik\LivewireDatatable\utils;

class Button
{
    public $name;
    public $text;
    public $action;
    public $confirm;
    public $_button_id;
    public function __construct($name)
    {
        $this->name = $name;
    }
    public function text(string $text)
    {
        $this->text = $text;
        return $this->generateButtonId();
    }
    public function action(string $text)
    {
        $this->action = $text;
        return $this->generateButtonId();
    }
    public function confirm(string $text)
    {
        $this->confirm = $text;
        return $this->generateButtonId();
    }
    protected function generateButtonId()
    {
        $this->_button_id = $this->name . $this->text;
        $this->_button_id = md5($this->_button_id);
        return $this;
    }
}
