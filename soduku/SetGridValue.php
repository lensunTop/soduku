<?php
/**
 * 设置格子的值
 */
class SetGridValue
{

    private $_squareNumber;
    private $_numbers;
    private $_tableCount;
    private $_orderOfSetGrid;
    public function __construct($squareNumber)
    {
        $this->_squareNumber = $squareNumber;
        $this->_setNumbers();
        $this->_setTableCount();
    }

    public function _setNumbers()
    {
        $this->_numbers = range(1, $this->_squareNumber * $this->_squareNumber);
    }

    public function _setTableCount()
    {
        $this->_tableCount = $this->_squareNumber * $this->_squareNumber;
    }
    /**
     * 通过数值获取出现的可能值
     * @param  integer $value [description]
     * @return array        [description]
     */
    private function _getPossibleValuesBy($value)
    {
        return array_diff($this->_numbers, [$value]);
    }

    /**
     * 设置不可能出现的数值
     * @param integer $table 第几个表格
     * @param integer $row   第几行
     * @param integer $col   第几列
     * @param integer $value 数值
     */
    private function _setImpossible($table = 0, $row = 0, $col = 0, $value)
    {
        if (isset($this->_tablesData[$table][$row][$col]['value'])) {
            $this->_tablesData[$table][$row][$col]['impossible'] = [];
            return;
        }
        $this->_tablesData[$table][$row][$col]['impossible'][] = $value;

        $this->_tablesData[$table][$row][$col]['impossible'] =
            array_unique($this->_tablesData[$table][$row][$col]['impossible']);

        //再次设置可能值
        if (isset($this->_tablesData[$table][$row][$col]['possible'])) {
            $this->_tablesData[$table][$row][$col]['possible'] =
                array_diff($this->_tablesData[$table][$row][$col]['possible'],
                $this->_tablesData[$table][$row][$col]['impossible']);
        }
    }

    /**
     * 设置可能值
     * @param integer $table 第几个表格
     * @param integer $row   第几行
     * @param integer $col   第几列
     * @param integer $value 数值
     */
    private function _setPossible($table = 0, $row = 0, $col = 0, $values)
    {
        if (isset($this->_tablesData[$table][$row][$col]['value'])) {
            $this->_tablesData[$table][$row][$col]['possible'] = [];
            return;
        }
        if (isset($this->_tablesData[$table][$row][$col]['impossible'])) {
            $values = array_diff($values,
                $this->_tablesData[$table][$row][$col]['impossible']);
        }

        if (isset($this->_tablesData[$table][$row][$col]['possible'])) {
            $this->_tablesData[$table][$row][$col]['possible'] =
                array_unique(array_merge($this->_tablesData[$table][$row][$col]['possible'], $values));
        } else {
            $this->_tablesData[$table][$row][$col]['possible'] = $values;
        }
        if (!isset($this->_tablesData[$table][$row][$col]['value'])
            && !$this->_tablesData[$table][$row][$col]['possible']) {
            throw new Exception("table:$table,row:$row,col:$col 数值或者可能数值不可以为空", 1);
        }
    }

    /**
     * 设置行表格数值
     * @param integer $table 第几个表格
     * @param integer $row   第几行
     * @param integer $col   第几列
     * @param integer $value 数值
     */
    private function _setRowTableValue($table = 0, $row = 0, $col = 0, $value)
    {
        $startTable = $table - $table % $this->_squareNumber;
        $possible   = $this->_getPossibleValuesBy($value);
        for ($i = 0; $i < $this->_squareNumber; $i++) {
            $_table = $i + $startTable;
            for ($j = 0; $j < $this->_squareNumber; $j++) {
                $_col = $j;
                if ($_table != $table || $_col != $col) {
                    $this->_setImpossible($_table, $row, $_col, $value);
                    $this->_setPossible($_table, $row, $_col, $possible);
                }
            }
        }
    }

    /**
     * 设置列表格数值
     * @param integer $table 第几个表格
     * @param integer $row   第几行
     * @param integer $col   第几列
     * @param integer $value 数值
     */
    private function _setColTableValue($table = 0, $row = 0, $col = 0, $value)
    {

        $startTable = $table % $this->_squareNumber;
        $possible   = $this->_getPossibleValuesBy($value);
        for ($_table = $startTable; $_table < $this->_tableCount; $_table += $this->_squareNumber) {
            for ($_row = 0; $_row < $this->_squareNumber; $_row++) {
                if ($_table != $table || $_row != $row) {
                    $this->_setImpossible($_table, $_row, $col, $value);
                    $this->_setPossible($_table, $_row, $col, $possible);
                }
            }
        }
    }

    /**
     * 设置方块表格数值
     * @param integer $table 第几个表格
     * @param integer $row   第几行
     * @param integer $col   第几列
     * @param integer $value 数值
     */
    private function _setSquareTableValue($table = 0, $row = 0, $col = 0, $value)
    {
        $possible = $this->_getPossibleValuesBy($value);
        for ($_row = 0; $_row < $this->_squareNumber; $_row++) {
            for ($_col = 0; $_col < $this->_squareNumber; $_col++) {
                if ($_row != $row && $_col != $col) {
                    $this->_setImpossible($table, $_row, $_col, $value);
                    $this->_setPossible($table, $_row, $_col, $possible);
                }
            }
        }
    }

    public function set($tablesData, $table = 0, $row = 0, $col = 0, $value)
    {
        $this->_tablesData = $tablesData;
        if (isset($this->_tablesData[$table][$row][$col]['impossible'])) {
            if (in_array($value, $this->_tablesData[$table][$row][$col]['impossible'], true)) {
                throw new Exception("table:$table,row:$row,col:$col can't set $value", 1);
                return false;
            }
        }
        if (!in_array($value, $this->_numbers, true)) {
            throw new Exception("the $value does't in numbers values[" . implode(',', $this->_numbers) . "]", 1);
            return false;
        }
        $this->_allPossible                                  = [];
        $this->_tablesData[$table][$row][$col]['value']      = $value;
        $this->_tablesData[$table][$row][$col]['possible']   = [];
        $this->_tablesData[$table][$row][$col]['impossible'] = [];
        //$this->_tablesData[$table][$row][$col]['order']      = $this->_orderOfSetGrid++;
        $this->_setRowTableValue($table, $row, $col, $value);
        $this->_setColTableValue($table, $row, $col, $value);
        $this->_setSquareTableValue($table, $row, $col, $value);
        return $this->_tablesData;
    }
}
