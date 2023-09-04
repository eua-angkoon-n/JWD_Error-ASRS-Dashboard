<?php 
require_once __DIR__ . "/../../config/connect_db.inc.php";
require_once __DIR__ . "/../../include/class_crud.inc.php";

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

        try{
            $con = connect_database();
            $obj = new CRUD($con);

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
            $addRow .= "['" . $value['wh'] . "', " . $value['count'] . ", ". $value['count'] .", '" . $ColumnBarColor[$i] . "'],";
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
                    bar: { groupWidth: '85%' },
                    chartArea: { width: '80%', height: '80%' },
                    fontName: 'Arial',
                    fontSize: '11',
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
                    document.getElementById('errorLogChart'));
            
                chart.draw(data, options);
            
        });
        </script>";
    
        return $chartScript;
    }
}
?>