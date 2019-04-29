<?php

namespace App\Service\Ssr;

/**
 * Server side rendering parser
 */
class SsrParser
{
    public $variableName;
    public $variableClass;
    public $page;
    public $args;

    public function __construct($variableName, $page, $args = [])
    {
        $this->variableName = $variableName;
        $this->page = $page;
        $this->args = $this->parseArgs($args);
        // variable to CamelCase
        $this->variableClass = __NAMESPACE__ . '\\Variables\\' . str_replace('-', '', ucwords($variableName, '-'));
    }

    /**
     * Узнает ли SSR переменную?
     */
    public function exists()
    {
        return class_exists($this->variableClass);
    }

    public function parse()
    {
        $variable = new $this->variableClass($this->variableName, $this->page, $this->args);
        return $variable->parse();
    }

    /**
     * преобразовать массив со значениями переменной типа
     * [
     *   'title=Видео работ',
     *   'ids=16,18,26,19,17,28,33,37,9,1,3,4,5,6,7,8,31,11,10,14,12,13',
     * ] в нормальный массив
     */
    private function parseArgs($args)
    {
        $items = [];
        foreach($args as $arg) {
            list($name, $value) = explode('=', $arg);
            $items[$name] = $value;
        }
        return (object)$items;
    }
}
