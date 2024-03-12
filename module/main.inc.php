<?php 
include( __DIR__ . "/include.php" );
require_once __DIR__ . "/dashboard.class.php";

$card     = new mainBoard();
$thisCard = $card -> getCard(); 
?>

<section class="content">
    <div class="card">
        <div class="card-header">
            <h6 class="display-8 d-inline-block font-weight-bold"><i class="fas fa-chalkboard"></i>
                <?PHP echo $title_act; ?>
            </h6>
            <div class="card-tools">
                <ol class="breadcrumb float-sm-right pt-1 pb-1 m-0">
                    <li class="breadcrumb-item"><a href="./">Home</a></li>
                    <li class="breadcrumb-item active">
                        <?PHP echo $breadcrumb_txt; ?>
                    </li>
                </ol>
            </div>
        </div>

        <div class="card-body">
            <form id="needs-validation" class="addform " name="addform" method="POST" enctype="multipart/form-data"
                autocomplete="off" novalidate="">

                <div class=" p-0">
                    <div class="card-body p-1">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-xs-12 ml-1">
                                <!-- <label>Date:</label> &nbsp;
                                <div class="d-inline">
                                    <button type="button" class="btn btn-default" id="date" name="date">
                                        <i class="far fa-calendar-alt"></i>
                                        Last 30 Days
                                        <i class="fas fa-caret-down"></i>
                                    </button>
                                </div> -->
                                <h2><i class="fas fa-exclamation-circle"></i><strong> <?php echo Setting::$arr_mouthEN[date('n')-1] ?> Error Log  </strong></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="selectedDateRange" name="selectedDateRange" value="">
            </form>

            <div class="row">
                <?php echo $thisCard; ?>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-12 order-2 order-md-1">
                    <div id="BarChart" class="BarChartMain"></div>
                </div>
                <div class="col-md-8 col-sm-12 order-1 order-md-2">
                    <div id="ColumnChart" class="ColChartMain"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
// $('.select2bs4').select2({
//   theme: 'bootstrap4'
// })
function SendData() {
    var frmData = $("form#needs-validation").serialize();
    var action = "<?php echo $action ?>";

    $.ajax({
        url: "module/ajax_action.php",
        type: "POST",
        data: {
            "data": frmData,
            "action": action
        },
        success: function (data) {
            var jsonData = JSON.parse(data);
            var CardData = jsonData.Card;
            var ChartData = jsonData.Chart;
            var BarData = jsonData.Bar;
            // console.log(CardData);
            // console.log(BarData[0]['Error Code']);
            for (var key in CardData) {
                if (CardData.hasOwnProperty(key)) {
                    // Get the value for the current key
                    var value = CardData[key];
                    // Update the corresponding HTML element with the same ID
                    $("#" + key).text(value);
                }
            }
            // console.log(BarData);
            google.charts.setOnLoadCallback(function () {
                drawColumnChart(ChartData);
                drawBarChart(BarData);
            });
            event.stopPropagation();
        },
        error: function (data) {
            console.log(data);
            sweetAlert("ผิดพลาด!", "ไม่สามารถแสดงผลข้อมูลได้", "error");
        }
    });
}

function drawBarChart(arrData) {
    var dataArray = [
        ['Error Name', 'Total', {
            type: 'string',
            role: 'annotation'
        }, {
            role: 'style'
        }]
    ];
    var colorArray = <?php echo json_encode(Setting::$PACAChart); ?>;
    var max = 0;
    let name = "";
    for (var i = 0; i < arrData.length; i++) {
        if(arrData[i].Error_Name == ""){
            name = arrData[i].Error_Code;
        } else {
            name = arrData[i].Error_Name;
        }
        dataArray.push([
            name, 
            parseInt(arrData[i].Total),
            name + " - " + arrData[i].Total,
            colorArray[arrData[i].type]]);

            if (parseInt(arrData[i].Total) > max) {
                max = parseInt(arrData[i].Total);
            }
    }
    console.log(dataArray);
    var data = google.visualization.arrayToDataTable(dataArray);

    var options = {
        title: '<?php echo Setting::$arr_mouthEN[date('n')-1] ?> Top Error',
        titleTextStyle: {
            fontName: 'Arial',
            fontSize: 22,
        },
        legend: {
            position: 'none'
        },
        bar: {
            groupWidth: '85%'
        },
        chartArea: {
            width: '100%',
            height: '85%'
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
        hAxis: {
            viewWindow: {
                min: 0, // Minimum value for the V-axis
                max: max
            },
            minValue: 0,
        },
        vAxis: {
            textPosition: 'none', // Hide the names of the bars on the X-axis

        },

    };
    var data = google.visualization.arrayToDataTable(dataArray);

    var chart = new google.visualization.BarChart(document.getElementById('BarChart'));
    chart.draw(data, options);
}

function drawColumnChart(ChartData) {

    var chartDataArray = [['Month', 
    'Crane', {type: 'number',role: 'annotation'}, {role: 'style'},
    'Conveyor', {type: 'number',role: 'annotation'}, {role: 'style'},
    'STV', {type: 'number',role: 'annotation'}, {role: 'style'}]];

    var colorArray = <?php echo json_encode(Setting::$PACAChart); ?> ;
    // console.log(ChartData);
    if (!ChartData) {
        chartDataArray.push(["No Error Log", 0, 0, colorArray[i],0, 0, colorArray[i],0, 0, colorArray[i]]);
        var max = 90;
    } else {
        // var max = 0
        for (var i = 0; i < ChartData.length; i++) {
            var row = ChartData[i];
            // if (parseInt(row.TotalValue) > max) {
                // var max = parseInt(row.TotalValue);
            // }
            var date = new Date(row.Year, row.Month - 1); // Month is 0-based in JavaScript
            chartDataArray.push([date, 
                parseInt(row.crane), parseInt(row.crane), colorArray['crane'],
                parseInt(row.conveyor), parseInt(row.conveyor), colorArray['conveyor'],
                parseInt(row.stv), parseInt(row.stv), colorArray['stv']
            ]);
        }
    }
    
    var options = {
        title: 'Error Log Overview',
        titleTextStyle: {
            fontName: 'Arial',
            fontSize: 22, // Set the desired font size for the title
        },
        bar: {
            groupWidth: '85%'
        },
        chartArea: {
            width: '90%',
            height: '85%'
        },
        fontName: 'Arial',
        fontSize: '14',
        hAxis: {
            format: 'MMM yyyy',
        },
        vAxis: {
            viewWindow: {
                min: 0, // Minimum value for the V-axis
                max: max + 10, // Maximum value for the V-axis
            },
        },
        legend: {
            position: 'in',
            alignment:'end'
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
    // console.log(chartDataArray);

    var data = google.visualization.arrayToDataTable(chartDataArray);

    var chart = new google.visualization.ColumnChart(
        document.getElementById('ColumnChart'));

    chart.draw(data, options);
}

$('#date').daterangepicker({
        ranges: {
            //   'Today'       : [moment(), moment()],
            //   'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate: moment()
    },
    function (start, end, label) {
        $('#selectedDateRange').val(end.format('YYYY-MM-DD') + '||//' + start.format('YYYY-MM-DD'));
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
        if (label === 'Custom Range') {
            $('#date').html('<i class="far fa-calendar-alt"></i> ' + start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY') + ' <i class="fas fa-caret-down"></i>');
        } else {
            $('#date').html('<i class="far fa-calendar-alt"></i> ' + label + ' <i class="fas fa-caret-down"></i>');
        }
    }
)

$(document).ready(function () {
    google.charts.load('current', {
        packages: ['corechart', 'bar']
    });
    SendData();
    // var startDate = moment().subtract(29, 'days');
    // var endDate = moment();
    // $('#selectedDateRange').val(endDate.format('YYYY-MM-DD') + '||//' + startDate.format('YYYY-MM-DD'));

    // $('#date').on('apply.daterangepicker', function (event, picker) {
    //     SendData(); // Trigger SendData when the date range changes
    // });
  
    // $("form#needs-validation").on("change", "select, input[type='checkbox'], button", function () {
    //     SendData();
    // });
});
</script>