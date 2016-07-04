<?php
/**
 * 数独
 */
class Soduku
{
    /**
     *
     * @var integer
     */
    private $_squareNumber = 3;

    /**
     * 表格总数
     * @var [type]
     */
    private $_tableCount;

    /**
     * 表格数据，用来存储数独每个格子的数据
     * @var [type]
     */
    private $_tablesData;

    /**
     * 数独出现的数字
     * 根据@$_squareNumber的平方取范围值
     * @var [type]
     */
    private $_numbers;

    /**
     * 所有可能出现的数值
     * @var array
     */
    private $_allPossible = [];

    /**
     * 没有设置值的格子总数
     * @var integer
     */
    private $_unsetCount = 0;

    /**
     * 检查标志
     * @var boolean
     */
    private $_checkFinish = false;

    /**
     * 尝试数据
     * @var array
     */
    private $_tryData = [];

    /**
     * 尝试深度
     * @var integer
     */
    private $_tryLevel = 0;

    /**
     * 设置方格的顺序
     * @var integer
     */
    private $_orderOfSetGrid = 0;

    public $printer;

    /**
     * 设置每个表格的方格数
     * $value>=2
     * @param integer $value [description]
     */
    private function _setSquareNumber($value = 3)
    {
        $this->_squareNumber = $value;
    }

    /**
     * 设置表格总数
     */
    private function _setTabelCount()
    {
        $this->_tableCount = $this->_squareNumber * $this->_squareNumber;
    }

    /**
     * 设置数字
     */
    private function _setNumbers()
    {
        $this->_numbers = range(1, $this->_tableCount);
    }

    /**
     * 初始化所有表格
     * @return [type] [description]
     */
    public function initAllTables()
    {
        for ($table = 0; $table < $this->_tableCount; $table++) {
            for ($row = 0; $row < $this->_squareNumber; $row++) {
                for ($col = 0; $col < $this->_squareNumber; $col++) {
                    $this->_tablesData[$table][$row][$col]['value']      = null;
                    $this->_tablesData[$table][$row][$col]['possible']   = $this->_numbers;
                    $this->_tablesData[$table][$row][$col]['impossible'] = [];
                }
            }
        }
    }

    /**
     * 初始化数据
     * @param  integer $squareNumber 方格数
     * @return [type]                [description]
     */
    public function init($squareNumber = 3)
    {
        $this->_setSquareNumber($squareNumber);
        $this->_setTabelCount();
        $this->_setNumbers();
        $this->initAllTables();
        $this->printer = new PrintTables();
        $this->setter  = new SetGridValue($squareNumber);
    }

    public function __construct($squareNumber = 3)
    {
        $this->init($squareNumber);
    }

    public function setValue($table = 0, $row = 0, $col = 0, $value)
    {
        $this->_tablesData = $this->setter->set($this->_tablesData, $table, $row, $col, $value);
    }
    /**
     * 设置所有可能值的数组
     * @param integer $table 第几个表格
     * @param integer $row   第几行
     * @param integer $col   第几列
     */
    private function _setAllPossibleValues($table, $row, $col)
    {
        foreach ($this->_tablesData[$table][$row][$col]['possible'] as $key => $value) {
            if (isset($this->_allPossibleValues[$value])) {
                $this->_allPossibleValues[$value]['times']++;
            } else {
                $this->_allPossibleValues[$value]['times'] = 1;
                $this->_allPossibleValues[$value]['table'] = $table;
                $this->_allPossibleValues[$value]['col']   = $col;
                $this->_allPossibleValues[$value]['row']   = $row;
            }
        }
    }

    /**
     * 设置唯一值
     */
    private function _setUniqueValue()
    {
        foreach ($this->_allPossibleValues as $key => $value) {
            if ($value['times'] == 1) {
                $this->setValue($value['table'], $value['row'], $value['col'], $key);
            }
        }
    }

    private function _findRow($table = 0, $row = 0, $col = 0)
    {
        $startTable = $table - $table % $this->_squareNumber;

        $this->_allPossibleValues = [];
        for ($i = 0; $i < $this->_squareNumber; $i++) {
            $_table = $i + $startTable;
            for ($_col = 0; $_col < $this->_squareNumber; $_col++) {
                if (!isset($this->_tablesData[$_table][$row][$_col]['value'])
                    || !$this->_tablesData[$_table][$row][$_col]) {
                    $this->_setAllPossibleValues($_table, $row, $_col);
                }
            }
        }
        $this->_setUniqueValue();
        return true;
    }

    private function _findCol($table, $row, $col)
    {
        $startTable = $table % $this->_squareNumber;

        $this->_allPossibleValues = [];
        for ($_table = $startTable; $_table < $this->_tableCount; $_table += $this->_squareNumber) {
            for ($_row = 0; $_row < $this->_squareNumber; $_row++) {
                if (!isset($this->_tablesData[$_table][$_row][$col]['value'])
                    || !$this->_tablesData[$_table][$_row][$col]) {
                    $this->_setAllPossibleValues($_table, $_row, $col);
                }
            }
        }
        $this->_setUniqueValue();
        return true;
    }

    private function _findSquare($table, $row, $col)
    {
        for ($_row = 0; $_row < $this->_squareNumber; $_row++) {
            for ($_col = 0; $_col < $this->_squareNumber; $_col++) {
                if (!isset($this->_tablesData[$table][$_row][$_col]['value'])
                    || !$this->_tablesData[$table][$_row][$_col]) {
                    $this->_setAllPossibleValues($table, $_row, $_col);
                }
            }
        }
        $this->_setUniqueValue();
        return true;
    }

    /**
     * 查找唯一可能值的格子
     * @return [type] [description]
     */
    public function findOnlyOnePossibleNumber()
    {
        for ($table = 0; $table < $this->_tableCount; $table++) {
            for ($row = 0; $row < $this->_squareNumber; $row++) {
                for ($col = 0; $col < $this->_squareNumber; $col++) {
                    $tmp = $this->_tablesData[$table][$row][$col]['possible'];
                    if (count($tmp) == 1) {
                        $this->setValue($table, $row, $col, current($tmp));
                    }
                }
            }
        }
    }

    /**
     * 获取没有设置的格子数
     * @return [type] [description]
     */
    private function _getUnsetCount()
    {
        $unsetCount = 0;
        for ($table = 0; $table < $this->_tableCount; $table++) {
            for ($row = 0; $row < $this->_squareNumber; $row++) {
                for ($col = 0; $col < $this->_squareNumber; $col++) {
                    if (!isset($this->_tablesData[$table][$row][$col]['value'])
                        || !$this->_tablesData[$table][$row][$col]['value']) {
                        $unsetCount++;
                    }
                }
            }
        }
        return $unsetCount;
    }

    /**
     * 是否完成
     * @return boolean [description]
     */
    private function _isFinish()
    {
        $unsetCount = $this->_getUnsetCount();
        if ($unsetCount == 0) {
            return true;
        }
        if (!$this->_checkFinish) {
            if ($this->_unsetCount == 0) {
                $this->_unsetCount = $unsetCount;
                return false;
            } else {
                if ($this->_unsetCount == $unsetCount) {
                    $this->_checkFinish = true;
                    return false;
                } else {
                    $this->_unsetCount = $unsetCount;
                    return false;
                }
            }
        } else {
            if ($this->_unsetCount == $unsetCount) {
                $this->_checkFinish = false;
                $this->_unsetCount  = 0;
                return true;
            } else {
                $this->_checkFinish = false;
            }
        }
    }

    /**
     * 查找唯一值
     * 唯一出现的值和唯一可能值
     * @return [type] [description]
     */
    public function findUniqueNumber()
    {
        for ($table = 0; $table < $this->_tableCount; $table++) {
            for ($row = 0; $row < $this->_squareNumber; $row++) {
                for ($col = 0; $col < $this->_squareNumber; $col++) {
                    if (!isset($this->_tablesData[$table][$row][$col]['value'])
                        || !$this->_tablesData[$table][$row][$col]['value']) {
                        $this->_findRow($table, $row, $col);
                        $this->_findCol($table, $row, $col);
                        $this->_findSquare($table, $row, $col);
                    }
                }
            }
        }
        $this->findOnlyOnePossibleNumber();
        if (!$this->_isFinish()) {
            $this->findUniqueNumber();
        }
        return true;
    }

    public function getOnlyTwoPossibleValuesGrid()
    {
        $this->_onlyTwoPossibleValuesGrid = [];
        for ($table = 0; $table < $this->_tableCount; $table++) {
            for ($row = 0; $row < $this->_squareNumber; $row++) {
                for ($col = 0; $col < $this->_squareNumber; $col++) {
                    $tmp = $this->_tablesData[$table][$row][$col]['possible'];
                    if (count($tmp) == 2) {
                        return [$tmp, $table, $row, $col];
                    }
                }
            }
        }
    }

    public function isPossibleValueEmpty()
    {
        for ($table = 0; $table < $this->_tableCount; $table++) {
            for ($row = 0; $row < $this->_squareNumber; $row++) {
                for ($col = 0; $col < $this->_squareNumber; $col++) {
                    if (!$this->_tablesData[$table][$row][$col]['value']
                        && !$this->_tablesData[$table][$row][$col]['possible']) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function tryPossible($table, $row, $col, $value)
    {
        try {
            //echo "before try======table:$table,row:$row,col:$col,value:$value<br>";
            //$this->printer->showTables($this->_squareNumber, $this->_tablesData);
            $this->setValue($table, $row, $col, $value);
            $this->findUniqueNumber();
            //echo "after try======table:$table,row:$row,col:$col,value:$value<br>";
            //$this->printer->showTables($this->_squareNumber, $this->_tablesData);
        } catch (Exception $e) {
            throw new Exception("the table:$table,row:$row,col:$col can't set $value", 1);
            return false;
        }

        if ($this->_getUnsetCount() != 0) {
            if ($this->isPossibleValueEmpty()) {
                $this->_tryLevel--;
                $this->_tablesData = $this->_tryData[$this->_tryLevel]['tablesData'];
                $this->tryPossible(
                    $this->_tryData[$this->_tryLevel]['table'],
                    $this->_tryData[$this->_tryLevel]['row'],
                    $this->_tryData[$this->_tryLevel]['col'],
                    next($this->_tryData[$this->_tryLevel]['possible'])
                );
            } else {
                $this->_tryLevel++;
                $this->_tryData[$this->_tryLevel]['tablesData'] = $this->_tablesData;
                list($possible, $table, $row, $col)             = $this->getOnlyTwoPossibleValuesGrid();
                if (empty($possible)) {
                    throw new Exception("Error Processing Request", 1);
                }
                $this->_tryData[$this->_tryLevel]['possible'] = $possible;
                $this->_tryData[$this->_tryLevel]['table']    = $table;
                $this->_tryData[$this->_tryLevel]['row']      = $row;
                $this->_tryData[$this->_tryLevel]['col']      = $col;
                $this->tryPossible($table, $row, $col, current($possible));
            }
            return false;
        } else {
            return true;
        }
    }

    public function tryRun()
    {
        $this->_tryData[$this->_tryLevel]['tablesData'] = $this->_tablesData;
        list($possible, $table, $row, $col)             = $this->getOnlyTwoPossibleValuesGrid();
        $this->_tryData[$this->_tryLevel]['possible']   = $possible;
        $this->_tryData[$this->_tryLevel]['table']      = $table;
        $this->_tryData[$this->_tryLevel]['row']        = $row;
        $this->_tryData[$this->_tryLevel]['col']        = $col;
        try {
            $this->tryPossible($table, $row, $col, current($possible));
        } catch (Exception $e) {
            //echo $e->getMessage();
            $this->tryPossible($table, $row, $col, next($possible));
        }
    }

    public function run()
    {
        echo "初始化:";
        $this->printer->showTables($this->_squareNumber, $this->_tablesData);
        $this->findUniqueNumber();
        $this->tryRun();
        echo "解答后:";
        $this->printer->showTables($this->_squareNumber, $this->_tablesData);
    }
}