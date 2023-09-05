<?php 
require_once __DIR__ . "/../../config/connect_db.inc.php";
require_once __DIR__ . "/../../include/class_crud.inc.php";
require_once __DIR__ . "/../../include/function.inc.php";

Class ErrorLog_WH 
{
    private $wh;
    private $month;
    private $year;

    public function __construct($wh,$month,$year)
    {
        $this->wh = $wh;
        $this->month = $month;
        $this->year = $year;
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

    public function getErrorLogData(){

        $WH = $this->wh;
        $Month = $this->month;
        $Year = $this->year;
        if(!$WH || !$Month || !$Year)
            return false;
        $sql  = "SELECT wh, COUNT(*) as count ";
        $sql .= "FROM asrs_error_trans ";
        $sql .= "WHERE ";
        $sql .= $WH;        
        $sql .= "AND ";
        $sql .= $Month;
        $sql .= "AND ";
        $sql .= $Year;
        $sql .= "GROUP BY wh; ";

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
    
    public function addRow($data){
        
        $addRow = '';
        $ColumnBarColor = Setting::$ColumnBarColor;
        $i = 0;
        foreach ($data as $key => $value) {
            $addRow .= "['" . strtoupper($value['wh']) . "', " . $value['count'] . ", ". $value['count'] .", '" . $ColumnBarColor[$i] . "'],";
            $i++;
        }
        return $addRow;
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
                    document.getElementById('Chart1'));
            
                chart.draw(data, options);
            
        });
        </script>";
    
        return $chartScript;
    }
}

Class ErrorLog_WHCompare
{
    private $wh;
    private $date;

    public function __construct($wh,$date)
    {
        $this->wh = ($wh == NULL ? false : $wh); 
        $this->date = ($date == NULL ? false : $date);
    }

    public function getChart() {
        return $this->createChart();
    }

    public function getErrorLogData(){

        $WH = $this->wh;
        $date = $this->date;

        if(!$WH || !$date)
            return false;
        $getRow = '';
        $con = connect_database();
        $obj = new CRUD($con);
        try{
            foreach ($WH as $keyWH => $valueWH){
                $query = array();
                $NameWH = $valueWH;
                foreach ($date as $keyDate => $valueDate){
                    $sql  = "SELECT COUNT(*) as count ";
                    $sql .= "FROM asrs_error_trans ";
                    $sql .= "WHERE ";
                    $sql .= "asrs_error_trans.wh = '$NameWH' ";
                    $sql .= "AND ";
                    $sql .= "DATE_FORMAT(asrs_error_trans.tran_date_time, '%Y-%m') = '$valueDate' ";
                    $sql .= "GROUP BY wh;";
    
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
                $getRow .= $this->addRow($NameWH, $count);
            }
            return $getRow;
        } catch(Exception $e) {
            return "Caught exception : <b>".$e->getMessage()."</b><br/>";
        } finally {
            $con = null;
        }
    }
    
    public function addRow($Name, $count){
        $addRow  = "[ ' " . strtoupper($Name) . " ' , ";
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
    public function getDataColumn($date){
        if(!$date)
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

        $col = $this->getDataColumn($date);
        $row = $this->getErrorLogData();
        $color = $this->getColorChart();

        if(!$row)
            $row = "['No Data', 0]";

        $chartScript = "<script type=\"text/javascript\">
        google.charts.load('current', {packages: ['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawColColors);

        function drawColColors() {
                var data = new google.visualization.DataTable();
                    data.addColumn('string', 'Warehouse');
                    $col

                    data.addRows([
                        $row
                    ]);
            
                var options = {
                    title: 'Error Log Compare',
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
?>