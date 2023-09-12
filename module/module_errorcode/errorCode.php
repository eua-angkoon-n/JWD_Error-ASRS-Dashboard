<?php 
require_once __DIR__ . "/../../config/connect_db.inc.php";
require_once __DIR__ . "/../../include/class_crud.inc.php";
require_once __DIR__ . "/../../include/function.inc.php";

Class ErrorCode
{
    private $wh;
    private $date;
    private $errCode;
    private $dateBetween;
    private $maxView;
    public function __construct($wh,$date,$errCode)
    {
        $this->wh      = ($wh == NULL ? false : $wh); 
        $this->date    = ($date == NULL ? false : $date);
        $this->errCode = ($errCode == NULL ? false : $errCode);
        $this->maxView = 0;
    }

    public function getChart() {
        return $this->createChart();
    }
    public function getErrorCodeData(){
        $WH      = $this->wh;
        $DATE    = $this->date;
        $date    = getDateDay($DATE,$start,$end);
        $col     = getDatesBetween($start,$end);
        $this->dateBetween = $col;
        $errCode = $this->errCode;
        if(!$WH || !$date || !$errCode || !$col)
            return false;
        $con = connect_database();
        $obj = new CRUD($con);
        try{
            foreach ($errCode as $keyerrCode => $valueErrCode){
                $NameErrCode = $valueErrCode;
                $sql  = "SELECT `Error Name`, `Error Code`, DATE(asrs_error_trans.tran_date_time) AS day, COUNT(*) as count ";
                $sql .= "FROM asrs_error_trans ";
                $sql .= "WHERE ";
                $sql .= "asrs_error_trans.wh = '$WH' ";
                $sql .= "AND ";
                $sql .= "(asrs_error_trans.`Error Name` = '".$NameErrCode['Error Name']."' ";
                $sql .= "AND ";
                $sql .= "asrs_error_trans.`Error Code` = '".$NameErrCode['Error Code']."') ";
                $sql .= "AND asrs_error_trans.tran_date_time BETWEEN '$start' AND '$end' ";
                $sql .= "GROUP BY day ";
                $sql .= "ORDER BY day;";

                $fetchRow = $obj->fetchRows($sql);
                $Data[] = $fetchRow;
            }
            $countArray = $this->getRowDataString($col, $Data);
            $this->maxView = $this->findMaxCount($Data);
            return $countArray;
        } catch(Exception $e) {
            return "Caught exception : <b>".$e->getMessage()."</b><br/>";
        } finally {
            $con = null;
        }
    }
    public function getRowDataString($col, $dataCount) {
        $rowData = $this->createRowData($col ,$dataCount);
        $rowStrings = [];
    
        foreach ($rowData as $row) {
            $rowString = '[' . implode(', ', $row) . ']';
            $rowStrings[] = $rowString;
        }
    
        $resultString = implode(",\n", $rowStrings);
    
        return $resultString;
    }

    public function createRowData($col, $dataCount) {
        $rowData = [];
        
        foreach ($col as $date) {
            $row = [formatDateToChart($date)];
            
            foreach ($dataCount as $errorData) {
                $count = 0;
                
                foreach ($errorData as $error) {
                    if ($error['day'] === $date) {
                        $count = $error['count'];
                        break;
                    }
                }
        
                $row[] = $count;
            }
            
            $rowData[] = $row;
        }
        
        return $rowData;
    }
    public function getArrayCount($col,$dataCount) {
        $countArray = [];
        foreach ($col as $date) {
            $countArray[$date] = [];
        } 
        // Fill in counts from $fetchArray into $countArray where dates match
        foreach ($dataCount as $item) {
            $date = $item["day"];
            if ($item["Error Name"] != "")
                $Error_Name = $item["Error Name"];
            else
                $Error_Name = $item["Error Code"];
            $count = $item["count"];
    
            if (!isset($countArray[$date][$Error_Name])) {
                $countArray[$date][$Error_Name] = 0;
            }
    
            $countArray[$date][$Error_Name] += $count;
        }
        // Fill in missing "wh" values with counts initialized to 0
        foreach ($countArray as &$dateData) {
            foreach ($dataCount as $item) {
                if ($item["Error Name"] != "")
                    $Error_Name = $item["Error Name"];
                else
                    $Error_Name = $item["Error Code"];
                if (!isset($dateData[$Error_Name])) {
                    $dateData[$Error_Name] = 0;
                }
            }
        }
        return $countArray;
    }
    public function findMaxCount($dataCount) {
        $maxCount = 0;
    
        foreach ($dataCount as $errorData) {
            foreach ($errorData as $error) {
                if ($error['count'] > $maxCount) {
                    $maxCount = $error['count'] + 5;
                }
            }
        }
        return $maxCount;
    }
    public function getDataColumn(){
        $errCode = $this->errCode;
        if(!$errCode)
            return false;
        $col = "";
        foreach ($errCode as $code => $type){
            if ($type['Error Name'] != "")
                $name = $type['Error Name'];
            else 
                $name = $type['Error Code'];
            $col .= "data.addColumn('number', '$name');\n";
        }
        return $col;
    }
    
    public function createChart(){
        $date = $this->date;

        $row = $this->getErrorCodeData();
        $col = $this->getDataColumn();

        if(!$row || !$col){
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
                        title: 'Error Code/Name',
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
                          maxValue: ".$this->maxView.",
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

Class ErrorCode_Total 
{
    private $wh;
    private $date;
    private $errorCode;

    public function __construct($wh,$date) {
        $this->wh = $wh;
        $this->date = $date;
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
    public function getDateDay(&$start,&$end) {
        $date  = $this->date;
        if (!$date)
            return false;
        foreach ($date as $key => $day) {
            if($key == 1)
                $start = $day;
            else if ($key == 0)
                $end = $day;
        }
        return true;
    }
    public function getErrorLogData(){

        $WH    = $this->wh;
        $DATE  = $this->date;
        $date  = getDateDay($DATE,$start,$end);
        if(!$WH || !$date)
            return false;
        
        $sql  = "SELECT `Error Name`, `Error Code`, COUNT(*) as count ";
        $sql .= "FROM asrs_error_trans ";
        $sql .= "WHERE ";
        $sql .= $WH;        
        $sql .= "AND ";
        $sql .= "asrs_error_trans.tran_date_time ";
        $sql .= "BETWEEN '$start' AND '$end' ";
        $sql .= "GROUP BY `Error Name`, `Error Code` ";
        $sql .= "ORDER BY count DESC ";
        $sql .= "LIMIT 5";

        $con = connect_database();
        $obj = new CRUD($con);
        try{
            $fetchRow = $obj->fetchRows($sql);

            if(empty($fetchRow))
                return false;
            else {
                return $fetchRow;
            }
        } catch(Exception $e) {
            return "Caught exception : <b>".$e->getMessage()."</b><br/>";
        } finally {
            $con = null;
        }
    }

    public function getErrorCode(){
        return $this->errorCode;
    }
    
    public function addRow($data){
        $addRow = '';
        $ColumnBarColor = Setting::$ColumnBarColor;
        $i = 0;
        foreach ($data as $key => $value) {
            $addRow .= "['" . (IsNullOrEmptyString($value['Error Name']) ? $value['Error Code'] : $value['Error Name']) . "', " . $value['count'] . ", ". $value['count'] .", '" . $ColumnBarColor[$i] . "'],";
            $i++;
            $errorCode[] = array(
                'Error Name' => $value['Error Name'],
                'Error Code' => $value['Error Code']); 
        }
        $this->errorCode = $errorCode;
        return $addRow;
    } 
    public function barWidth(){
        $wh = $this->errorCode;
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
                      ['Error Name/Code','Error', {type: 'number', role: 'annotation'}, { role: 'style' }],
                      $row
                      ]);
            
            
                var options = {
                    title: 'Total Error Code/Name',
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