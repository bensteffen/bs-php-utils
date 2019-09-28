<?php

class Array2Table {
    protected $array;
    protected $header;

    public function __construct($array = null) {
        if ($array) {
            $this->setArray($array);
        }
    }

    public function setArray($array) {
        if (count($array) === 0) {
            throw(new Exception('Array2Table.setArray: count of $array must be > 0.'));
        }
        $this->array = $array;
        $this->header = array_keys($array[0]);
        foreach($array as $row) {
            if (array_keys($row) !== $this->header) {
                throw(new Exception('Array2Table.setArray: All items in array must have the same key set.'));
            }
        }
    }

    public function toAscii() {

    }

    public function toHtml() {
        $template = ''
        . '<table">'
        .   '<thead>'
        .     '%s'
        .   '</thead>'
        .   '<tbody>'
        .     '%s'
        .   '</tbody>'
        . '<table>';
        $headerString = $this->createHtmlRow($this->header);
        $bodyString = '';
        foreach($this->array as $row) {
            $bodyString .= $this->createHtmlRow(array_values($row));
        }
        return sprintf($template, $headerString, $bodyString);
    }

    protected function createHtmlRow($values) {
        $td = array_map(function($val) {
            return '<td>'.htmlentities($val).'</td>';
        }, $values);
        return '<tr>'.implode('',$td).'</tr>';
    }

    protected function getColumnWidth($columnIndex) {
        
    }
}