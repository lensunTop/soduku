<?php
/**
 * 打印表格
 */
class PrintTables
{
    private $_squareNumber;
    private $_tablesData;

    public function printHtmlTable($start, $end)
    {
        for ($row = 0; $row < $this->_squareNumber; $row++) {
            echo "<tr>";
            for ($table = $start; $table < $end; $table++) {
                for ($col = 0; $col < $this->_squareNumber; $col++) {
                    echo "<td>(";
                    $val = $this->_tablesData[$table][$row][$col]['value'];
                    if ($val) {
                        echo "<span style='color:red;'>$val</span>";
                    } else {
                        $po = implode(',', $this->_tablesData[$table][$row][$col]['possible']);
                        //echo ":" . $po;
                    }
                    echo ")</td>";
                }
            }
            echo "</tr>";
        }
    }

    public function showTables($squareNumber, $tablesData)
    {
        $this->_squareNumber = $squareNumber;
        $this->_tablesData   = $tablesData;
        for ($i = 0; $i < $this->_squareNumber; $i++) {
            echo "<table>";
            $this->printHtmlTable($i * $this->_squareNumber, $this->_squareNumber * ($i + 1));
            echo "</table>";
        }
    }
}
