<?php 
require_once __DIR__ . "/../../config/connect_db.inc.php";
require_once __DIR__ . "/../../include/class_crud.inc.php";
require_once __DIR__ . "/../../include/function.inc.php";

Class ErrorMachine
{
    private $wh;
    private $date;
    private $machine;
    private $ComboChartData;
    private $maxValue;
    public function __construct($wh,$date,$machine)
    {
        $this->wh = ($wh == NULL ? false : $wh); 
        $this->date = ($date == NULL ? false : $date);
        $this->machine = ($machine == NULL ? false : $machine);
    }
    public function getChart() {
        return $this->createChart();
    }
    public function getErrorCodeData(){
        $WH = $this->wh;
        $DATE = $this->date;
        $machine =  $this->machine;
        $date  = getDateDay($DATE,$start,$end);
        if(!$WH || !$date || !$machine)
            return false;
        $getRow = '';
        $sql  = "SELECT Machine, DATE(tran_date_time) AS transaction_date, `Error Code`, `Error Name`, COUNT(*) AS Count ";
        $sql .= "FROM asrs_error_trans ";
        $sql .= "WHERE ";
        $sql .= $WH;
        if ($machine != 'All'){
            $sql .= "AND ";
            $sql .= "asrs_error_trans.Machine = '$machine' ";
        }
        $sql .= "AND ";
        $sql .= "asrs_error_trans.tran_date_time ";
        $sql .= "BETWEEN '$start' AND '$end' ";
        $sql .= "GROUP BY "; 
        $sql .= "Machine, transaction_date, `Error Code`, `Error Name`;";

        try{
            $con = connect_database();
            $obj = new CRUD($con);
            $fetch = $obj->fetchRows($sql);
            
            $ComboChartData = $this->generateComboChartData($fetch,$start,$end);
            $this->maxValue = $this->findMostValue($ComboChartData);
            return $ComboChartData ;
        } catch(Exception $e) {
            return "Caught exception : <b>".$e->getMessage()."</b><br/>";
        } finally {
            $con = null;
        }
    }

    public function findMostValue($data){
        $maxTotal = PHP_INT_MIN; // Initialize with the minimum possible value

        // Find the index of the "Total" column
        $totalColumnIndex = array_search('Total', $data[0]);
    
        if ($totalColumnIndex !== false) {
            // Iterate through the data, starting from the second row
            for ($i = 1; $i < count($data); $i++) {
                $total = $data[$i][$totalColumnIndex];
                if (is_numeric($total) && $total > $maxTotal) {
                    $maxTotal = $total;
                }
            }
        } else {
           return false;
        }
        $maxTotal = ceil($maxTotal) + 5;
        return $maxTotal;
    }

    public function generateComboChartData($originalData, $startDate, $endDate) {
        // Create an associative array to store data for each date and error name.
        $comboData = array();

        // Convert the start and end date strings to DateTime objects.
        $startDateObj = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);
    
        // Create an array for all dates within the specified range.
        $currentDate = clone $startDateObj;
        $endDatePlusOne = clone $endDateObj;
        $endDatePlusOne->modify('+1 day');
    
        while ($currentDate < $endDatePlusOne) {
            $date = $currentDate->format('Y-m-d');
    
            if (!isset($comboData[$date])) {
                $comboData[$date] = array('timeofday' => formatDateToChart($date), 'Total' => 0);
            }
    
            $currentDate->modify('+1 day');
        }
    
        // Create an array for the header row.
        $header = ['timeofday'];
    
        // Find unique error names to create columns for each error.
        $errorNames = [];
        foreach ($originalData as $entry) {
            $errorName = $entry['Error Name'];
            if (!empty($errorName) && !in_array($errorName, $errorNames)) {
                $errorNames[] = $errorName;
                $header[] = $errorName;
            }
        }
    
        // Add the 'Total' column to the header.
        $header[] = 'Total';
    
        // Loop through the original data to populate the associative array.
        foreach ($originalData as $entry) {
            $date = $entry['transaction_date'];
            $errorName = $entry['Error Name'];
            $count = (int)$entry['Count'];
    
            $entryDateObj = new DateTime($date);
    
            // Check if the date is within the specified range.
            if ($entryDateObj >= $startDateObj && $entryDateObj <= $endDateObj) {
                $comboData[$date][$errorName] = $count;
                $comboData[$date]['Total'] += $count;
            }
        }
    
        // Create the final data array.
        $comboChartData = [$header];
    
        // Loop through the associative array and create data rows.
        foreach ($comboData as $dateData) {
            $rowData = [$dateData['timeofday']];
    
            foreach ($errorNames as $errorName) {
                $rowData[] = isset($dateData[$errorName]) ? $dateData[$errorName] : 0;
            }
    
            $rowData[] = $dateData['Total'];
    
            $comboChartData[] = $rowData;
        }
    
        return $comboChartData;
    }
    
    public function getNumberOfColumns($comboChartData) {
        if (empty($comboChartData) || !is_array($comboChartData)) {
            return 0; // Return 0 if the input is empty or not an array.
        }
    
        // Get the first row (header) and count the number of elements.
        $header = reset($comboChartData);
        $count  = count($header) - 2;
        return $count;
    }

    public function getColorChart(){
        $ColorBar = Setting::$ColumnBarColor;
        $color = formatColorChart($ColorBar);
        return $color;
    }
    
    public function createChart(){
        $machine =  $this->machine;
        $row = $this->getErrorCodeData();
        $line = $this->getNumberOfColumns($row);
        $rowData = "[";
        foreach($row as $key => $value){
            if($key == 0) {
                $rowData .= "[ ";
                foreach ($value as $dataV) {
                    $rowData .= "'". $dataV . "',"; 
                }
                $rowData .= "], ";
            } else {
                $rowData .= "[ ";
                foreach ($value as $dataV) {
                    $rowData .= $dataV . ","; 
                }
                $rowData .= "], ";
            }
        }
        $rowData .= "]";

        if(!$row)
            $row = "['No Data', 0]";

        $chartScript = "
        <script type=\"text/javascript\">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawVisualization);
  
        function drawVisualization() {
         
          var data = google.visualization.arrayToDataTable($rowData);

          data.setColumnProperty(0, 'type', 'timeofday');
        

          var options = {
            title : 'Error Machine: $machine',
            chartArea: { width: '90%', height: '80%' },
            seriesType: 'bars',
            series: {
                ".$line.": {
                    type: 'line',
                    color : 'blue'
                },
              
            },
            pointSize: 5,
            isStacked: true,
            hAxis: {
                format: 'd/MM.yy',
                ticks: data.getDistinctValues(0),
            },
            vAxis: {
                viewWindow: {
                  min: 0, // Minimum value for the V-axis
                  max: $this->maxValue, // Maximum value for the V-axis
                },
                format: '0',
              },
            legend: {
                position: 'in'
            }
          };
  
          var chart = new google.visualization.ComboChart(document.getElementById('Chart1'));
          chart.draw(data, options);
        }
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
    public function getAnimation(){
        return "animation: {
            duration: 1000,
            easing: 'in',
            startup: true
        },";
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
                    title: 'Top 10 Error Machine',
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