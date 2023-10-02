<?php 
require_once __DIR__ . "/../../config/connect_db.inc.php";
require_once __DIR__ . "/../../include/class_crud.inc.php";
require_once __DIR__ . "/../../include/function.inc.php";

Class ErrorLog_WH
{
    private $wh;
    private $date;
    private $arrWH;
    
    public function __construct($wh,$date,$arrWH)
    {
        $this->wh = $wh; 
        $this->date = $date;
        $this->arrWH = $arrWH;
    }

    public function getChart() {
        return $this->createChart();
    }

    public function getErrorLogData(){

        $WH    = $this->wh;
        $DATE  = $this->date;
        $date  = getDateDay($DATE,$start,$end);
        if(!$WH || !$date)
        $getRow = '';
        $con = connect_database();
        $obj = new CRUD($con);
        try {
            $sql  = "SELECT wh, DATE(asrs_error_trans.tran_date_time) AS day, COUNT(*) AS count ";
            $sql .= "FROM asrs_error_trans ";
            $sql .= "WHERE $WH ";
            $sql .= "AND ";
            if($start != $end){
                $sql .= "asrs_error_trans.tran_date_time ";
                $sql .= "BETWEEN '$start' AND '$end' ";
            }
            else {
                $sql .= "date(asrs_error_trans.tran_date_time) ";
                $sql .= "= '$start' ";
            }
            $sql .= "GROUP BY day,wh ";
            $sql .= "ORDER BY day,wh;";
            $fetchRow = $obj->fetchRows($sql);
            
            if(empty($fetchRow))
                return false;
            $col = getDatesBetween($start,$end);
            $countArray = $this->getArrayCount($col,$fetchRow);
           
            return $countArray;
        } catch(Exception $e) {
            return "Caught exception : <b>".$e->getMessage()."</b><br/>";
        } finally {
            $con = null;
        }
    }

    public function getArrayCount($col,$dataCount) {
        $countArray = [];
        foreach ($col as $date) {
            $countArray[$date] = [];
        } 
        // Fill in counts from $fetchArray into $countArray where dates match
        foreach ($dataCount as $item) {
            $date = $item["day"];
            $wh = $item["wh"];
            $count = $item["count"];
            
            if (!isset($countArray[$date][$wh])) {
                $countArray[$date][$wh] = 0;
            }
            
            $countArray[$date][$wh] += $count;
        }
        // Fill in missing "wh" values with counts initialized to 0
        foreach ($countArray as &$dateData) {
            foreach ($dataCount as $item) {
                $wh = $item["wh"];
                if (!isset($dateData[$wh])) {
                    $dateData[$wh] = 0;
                }
            }
        }
        return $countArray;
    }
    
    public function getDataRow($count){
        $wh = $this->arrWH;
        if(!$count || !$wh)
            return false;
        $addRow = "";
        foreach ($count as $key => $countValue){
            $date = formatDateToChart($key);
            $addRow .= "[ ".$date .", ";
            foreach ($wh as $key => $whValue) {
                    if(empty($countValue[strtolower($whValue)]))
                        $addRow .= "0, ";
                    else
                        $addRow .= $countValue[strtolower($whValue)].", ";
            }
            $addRow .= "], ";
        }
        return $addRow;
    }

    public function getDataColumn(){
        $wh = $this->arrWH;
        if(!$wh)
            return false;
        $col = "";
        foreach ($wh as $key => $value){
            $col .= "data.addColumn('number', '$value');";
        }
        return $col;
    }

    public function getColorChart(){
        $ColorBar = Setting::$ColumnBarColor;
        $color = formatColorChart($ColorBar);
        return $color;
    }

    public function createChart(){
        $wh = $this->wh;

        $ChartData = $this->getErrorLogData();
        $row       = $this->getDataRow($ChartData);
        $col       = $this->getDataColumn();

        if(!$ChartData || !$row || !$wh) {
            $today = date('Y-m-d');
            $now = formatDateToChart($today);
            $row = "[$now, 0]";
            $col = "data.addColumn('number', 'No Data');";
        }

        $chartScript = "<script type=\"text/javascript\">
        google.charts.load('current', {packages: ['corechart', 'line']});
        google.charts.setOnLoadCallback(drawColColors);

        function drawColColors() {
                var data = new google.visualization.DataTable();
                    data.addColumn('date', 'Time of Day');
                    $col

                    data.addRows([
                        $row
                    ]);
            
                    var options = {
                        title: 'Error Log',
                        chartArea: { width: '90%', height: '80%' },
                        fontName: 'Arial',
                        fontSize: '14',
                        lineWidth: 2,
                        legend: {position: 'in'},
                        selectionMode: 'multiple',
                        pointSize: 5,
                        hAxis: {
                          format: 'M/d/yy',
                        },
                        vAxis: {
                          gridlines: {color: 'none'},
                          minValue: 0
                        },
                        animation: {
                            duration: 1000,
                            easing: 'in',
                            startup: true
                        },
                        annotations: {
                            textStyle: {
                                fontName: 'Arial',
                                fontSize: 11,
                                color: '#000',
                                auraColor: 'none'
                            }
                        },
                      };
            
                var chart = new google.visualization.LineChart(
                    document.getElementById('Chart1'));
            
                chart.draw(data, options);
            
        };
        </script>";
    
        return $chartScript;
    }
}

Class ErrorLog_WHTotal
{
    private $wh;
    private $date;
    private $whCount;
    private $arrWH;
    public function __construct($wh,$date,$arrWH)
    {
        $this->wh = $wh;
        $this->date = $date;
        $this->arrWH = $arrWH;
    }

    public function getChart() {
        return $this->createChart();
    }

    public function createRow(){
    
        $Data = $this->getErrorLogData();
        if(!$Data)
            return false;
        $row = $this->addRow($Data);
        return $row;
    
    }

    public function getWH(){
      
        $sql  = "SELECT * ";
        $sql .= "FROM asrs_error_wh ";
        $sql .= "WHERE ";
        $sql .= "1=1 ";
        $sql .= "ORDER BY site_name ASC ";
        
        $con = connect_database();
        $obj = new CRUD($con);
        try{
            $wh = $obj->fetchRows($sql);
            $wh_query = "";
            foreach ($wh as $key => $value) {
                $wh_query .= count($wh) > 1 && $key == 0 ? ' ( ' : ' ';
            
                $wh_query .= count($wh) > 1 && $key == 0 ? ' asrs_error_trans.wh = "' .$value['site_name']. '" ' : ' OR asrs_error_trans.wh = "' .$value['site_name']. '" ';
            
                $wh_query .= count($wh) > 1 && array_key_last($wh) == $key ? ') ' : '';
            }
            count($wh) == 1 ? $wh_query = str_replace('OR', '', $wh_query) : $wh_query;
        } finally {
            $con = null;
        }
        return $wh_query;
    }

    public function getErrorLogData(){
        
        $WH = $this->wh;
        $date = $this->date;
        if(!$WH)
            return false;
        $wh = $this->getWH();
        $sql  = "SELECT wh, COUNT(*) as count ";
        $sql .= "FROM asrs_error_trans ";
        $sql .= "WHERE ";
        $sql .= $WH;        
        $sql .= "AND ";
        if($date[1] != $date[0]){
            $sql .= "asrs_error_trans.tran_date_time ";
            $sql .= "BETWEEN '".$date[1]."' AND '".$date[0]."' ";
        }
        else {
            $sql .= "date(asrs_error_trans.tran_date_time) ";
            $sql .= "= '".$date[0]."' ";
        }
        $sql .= "GROUP BY wh; ";

        $con = connect_database();
        $obj = new CRUD($con);
        try{
            $fetchRow = $obj->fetchRows($sql);

            if(empty($fetchRow))
                return false;
            else {
                $this->whCount = count($fetchRow);
                return $fetchRow;
            }
        } catch(Exception $e) {
            return "Caught exception : <b>".$e->getMessage()."</b><br/>";
        } finally {
            $con = null;
        }
    }
    
    public function addRow($data){
        $arrWH = $this->arrWH;
        $addRow = '';
        $ColumnBarColor = Setting::$ColumnBarColor;
        $i = 0;
    
        foreach ($arrWH as $wh) {
            $found = false;
            foreach ($data as $key => $value) {
                $whValue = strtoupper($value['wh']);
                if ($whValue === $wh) {
                    $addRow .= "['" . $whValue . "', " . $value['count'] . ", " . $value['count'] . ", '" . $ColumnBarColor[$i] . "'],";
                    $i++;
                    $found = true;
                    break; // Exit the inner loop when a match is found
                }
            }
            
            if (!$found) {
                // 'wh' value not found in $data, add a row with count = 0
                $addRow .= "['" . strtoupper($wh) . "', 0, 0, '" . $ColumnBarColor[$i] . "'],";
                $i++;
            }
        }
        return $addRow;
    } 

    public function barWidth(){
        $wh = $this->whCount;
        if ($wh < 2)
            return '10%';
        else if ($wh < 5)
            return '30%';
        return '50%';
    }
    
    public function createChart(){
        $row = $this->createRow();
        if(!$row)
            $row = "['No Data', 0, 0, '#EEEEEE']";
    
        $chartScript = "<script type=\"text/javascript\">
        google.charts.load('current', { packages: ['corechart'] });
        google.charts.setOnLoadCallback(function drawChart() {
    
                var data = new google.visualization.arrayToDataTable([
                      ['Warehouse','Error Log', {type: 'number', role: 'annotation'}, { role: 'style' }],
                      $row
                      ]);
            
            
                var options = {
                    title: 'Error Log Total',
                    bar: { groupWidth: '85%' },
                    chartArea: { width: '90%', height: '80%' },
                    fontName: 'Arial',
                    fontSize: '14',
                    bar: {groupWidth: '".$this->barWidth()."' },
                    hAxis: {
                        minValue: 0,
                        format: '0',
                        viewWindow: {
                            min: 0
                        },
                    },
                    legend: { position: 'none' },
                    animation: {
                        duration: 1000,
                        easing: 'in',
                        startup: true
                    },
                    annotations: {
                        textStyle: {
                            fontName: 'Arial',
                            fontSize: 11,
                            color: '#000',
                            auraColor: 'none'
                        }
                    },
                };
            
                var chart = new google.visualization.ColumnChart(
                    document.getElementById('Chart2'));
            
                chart.draw(data, options);
            
        });
        </script>";
    
        return $chartScript;
    }
}

?>