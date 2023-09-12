<?php 
require_once __DIR__ . "/../../config/connect_db.inc.php";
require_once __DIR__ . "/../../include/class_crud.inc.php";
require_once __DIR__ . "/../../include/function.inc.php";

Class ErrorMachine_Compare
{
    private $wh;
    private $date;
    private $errCode;
    public function __construct($wh,$date,$errCode)
    {
        $this->wh = ($wh == NULL ? false : $wh); 
        $this->date = ($date == NULL ? false : $date);
        $this->errCode = ($errCode == NULL ? false : $errCode);
    }

    public function getChart() {
        return $this->createChart();
    }
    public function getErrorCodeData(){
        $WH = $this->wh;
        $date = $this->date;
        $errCode =  $this->errCode;
        if(!$WH || !$date || !$errCode)
            return false;
        $getRow = '';
        $con = connect_database();
        $obj = new CRUD($con);
        try{
            foreach ($errCode as $keyerrCode => $valueErrCode){
                $query = array();
                $NameErrCode = $valueErrCode;
                foreach ($date as $keyDate => $valueDate){
                    $sql  = "SELECT `Machine`, COUNT(*) as count ";
                    $sql .= "FROM asrs_error_trans ";
                    $sql .= "WHERE ";
                    $sql .= "asrs_error_trans.wh = '$WH' ";
                    $sql .= "AND ";
                    $sql .= "asrs_error_trans.`Machine` = '".$NameErrCode['Machine']."' ";
                    $sql .= "AND ";
                    $sql .= "DATE_FORMAT(asrs_error_trans.tran_date_time, '%Y-%m') = '$valueDate' ";
    
                    $query[] = $sql;
                }
                $count = array();
                foreach ($query as $key => $SQL){
                    $fetchRow = $obj->customSelect($SQL);
                    if(empty($fetchRow['count'])){
                        $count[] = 0;
                    } else {
                        $count[] = $fetchRow['count']; 
                    }
                }
                $getRow .= $this->addRow($NameErrCode, $count);
            }
            return $getRow;
        } catch(Exception $e) {
            return "Caught exception : <b>".$e->getMessage()."</b><br/>";
        } finally {
            $con = null;
        }
    }
    
    public function addRow($Name, $count){
        $rowName = $Name['Machine'];
        $addRow  = "[ ' " . $rowName . " ' , ";
        foreach ($count as $key => $countValue){
            $addRow .= " $countValue,  $countValue, ";
        }
        $addRow .= "],";
        return $addRow;
    }

    public function getColorChart(){
        $ColorBar = Setting::$ColumnBarColor;
        $color = formatColorChart($ColorBar);
        return $color;
    }
    public function getDataColumn($date,$row){
        if(!$date || !$row)
            return "data.addColumn('number', 'No Data');";
        $col = "";
        foreach ($date as $key => $value){
            $dateTime = new DateTime($value);
            $formattedDate = $dateTime->format("M.Y");
            $col .= "data.addColumn('number', '$formattedDate');";
            $col .= "data.addColumn({type: 'number', role: 'annotation'});";
        }
        return $col;
    }
    
    public function createChart(){
        $date = $this->date;

        $row = $this->getErrorCodeData();
        $col = $this->getDataColumn($date,$row);
        $color = $this->getColorChart();

        if(!$row)
            $row = "['No Data', 0]";

        $chartScript = "<script type=\"text/javascript\">
        google.charts.load('current', {packages: ['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawColColors);

        function drawColColors() {
                var data = new google.visualization.DataTable();
                    data.addColumn('string', 'Machine');
                    $col

                    data.addRows([
                        $row
                    ]);
            
                var options = {
                    title: 'Monthly Top 7 Error Machine',
                    bar: { groupWidth: '85%' },
                    chartArea: { width: '90%', height: '80%' },
                    colors: $color,
                    annotations: {
                        alwaysOutside: true,
                        textStyle: {
                          fontSize: 14,
                          color: '#000',
                          auraColor: 'none'
                        }
                      },
                    fontName: 'Arial',
                    fontSize: '14',
                    bars: 'horizontal', // Required for Material Bar Charts. vertical
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
            
        };
        </script>";
    
        return $chartScript;
    }
}

Class ErrorMachine_Total
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

        $sql  = "SELECT `Machine`, COUNT(*) as count ";
        $sql .= "FROM asrs_error_trans ";
        $sql .= "WHERE ";
        $sql .= $WH;        
        $sql .= "AND ";
        $sql .= "asrs_error_trans.tran_date_time ";
        $sql .= "BETWEEN '$start' AND '$end' ";
        $sql .= "GROUP BY `Machine` ";
        $sql .= "ORDER BY count DESC ";
        $sql .= "LIMIT 10";
        
        $con  = connect_database();
        $obj  = new CRUD($con);
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
            $addRow .= "['" . $value['Machine'] . "', " . $value['count'] . ", ". $value['count'] .", '" . $ColumnBarColor[$i] . "'],";
            $i++;
            $errorCode[] = array(
                'Machine' => $value['Machine']
            ); 
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
                      ['Error Machine','Error', {type: 'number', role: 'annotation'}, { role: 'style' }],
                      $row
                      ]);
            
            
                var options = {
                    title: 'Total Error Machine',
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
                    document.getElementById('Chart1'));
            
                chart.draw(data, options);
            
        });
        </script>";
    
        return $chartScript;
    }
}
?>