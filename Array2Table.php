<?php

class Array2Table {
    protected $array;
    protected $header;
    protected $columnWidths;

    public function __construct($array = null) {
        if ($array) {
            $this->setArray($array);
        }
    }

    public function setArray($array) {
        if (count($array) === 0) {
            throw(new Exception('Array2Table.setArray: count of $array must be > 0.'));
        }
        foreach ($array as $i => $row) {
            foreach ($row as $name => $value) {
                if (is_array($value)) {
                    $value = json_encode($value);
                }
                $value = str_replace("\n", '\n', $value);
                $row[$name] = $value;
            }
            $array[$i] = $row;
        }
        $this->array = $array;
        $this->header = array_keys($array[0]);
        foreach($array as $row) {
            if (array_keys($row) !== $this->header) {
                throw(new Exception('Array2Table.setArray: All items in array must have the same key set.'));
            }
        }
        $this->columnWidths = null;
    }

    public function toAscii() {
        $headerRow = $this->createAsciiRow($this->header);
        $rowLength = strlen($headerRow);
        $rows = [];
        array_push($rows, str_repeat('=', $rowLength));
        array_push($rows, $headerRow);
        array_push($rows, str_repeat('-', $rowLength));
        foreach ($this->array as $row) {
            array_push($rows, $this->createAsciiRow(array_values($row)));
        }
        array_push($rows, str_repeat('=', $rowLength));
        return implode("\n", $rows);
    }

    protected function createAsciiRow($row) {
        $widths = $this->getColumnWidths();
        $rowString = '';
        /**
         * TODO wrap too long entries
         */
        // $lines = [];
        // foreach ($row as $j => $val) {
        //     $val = wordwrap($val, 25, "\n");
        //     array_push($lines, explode("\n", $val));
        // }
        // $lineCount = max(array_map(function($l) { return count($l); }, $lines));
        // foreach ($lines as $j => $l) {
        //     while (count($l) < $lineCount) {
        //         array_push($l, '');
        //     }
        //     $lines[$j] = $l;
        // }
        foreach ($row as $j => $val) {
            $rowString .= $val . str_repeat(' ', $widths[$j] - strlen($val) + 4);
        }
        return $rowString;
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

    protected function getColumnWidths() {
        if (!$this->columnWidths) {
            $widths = [];
            foreach ($this->header as $h) {
                $column = array_merge([$h], array_column($this->array, $h));
                $lengths = array_map(function($val) {
                    return strlen($val);
                }, $column);
                array_push($widths, max($lengths));
            }
            $this->columnWidths = $widths;
        }
        return $this->columnWidths;
    }
}
