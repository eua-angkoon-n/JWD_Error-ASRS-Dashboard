<?php 
include( __DIR__ . "/include.php" );
require_once __DIR__ . "/dashboard.class.php";

$SelectBar = new DashBoard($action);
$siteSelect = $SelectBar->getSiteSelect();
$machine = $SelectBar->getMachine();
$nameCode = $SelectBar->getErrorNameNCode();
?>

<style>
/* Default styling for the date button */
.date {
  display: inline-block; /* Display the date button as inline-block by default */
}

/* Media query for screens with a maximum width of 768px */
@media (max-width: 768px) {
  .date {
    display: block; /* Change the display to block for smaller screens, effectively removing d-inline */
  }
}
</style>

<section class="content">
    <div class="card">
        <div class="card-header">
            <h6 class="display-8 d-inline-block font-weight-bold"><i class="fas fa-chart-bar"></i>
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

                <div class="card p-0">
                    <div class="card-body p-1">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-xs-12 ml-1">
                                <?php if ($action != "DashBoard") { ?>
                                <label>Warehouse:</label> &nbsp;
                                <?php } if ($action == "errorLog") { ?>
                                    <div class="d-inline">
                                        <button
                                            class="btn btn-default dropdown-toggle col-sm-2 col-md-2 col-xs-12 mr-3 justify-content-between"
                                            type="button" id="dropdownWH" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="true">
                                            Warehouse
                                        </button>
                                    <ul class="dropdown-menu checkbox-menu allow-focus">
                                        <?php echo $siteSelect ?>
                                    </ul>
                                </div>
                                <?php } else if ($action != "DashBoard"){ ?>
                                    <select class="custom-select col-sm-1 col-md-1 col-xs-12 mr-3" name="dropdownWH"
                                    id="dropdownWH" style="width:100%; font-size:0.85rem;" required="" onchange="getValue(this)">
                                        <?php echo $siteSelect ?>
                                    </select>
                                
                                <?php } if ($action == "errorMachine") { ?>
                                    <label>Machine:</label> &nbsp;
                                    <div class="d-inline">
                                        <select class="custom-select col-sm-1 col-md-1 col-xs-12 mr-3"data-placeholder="Select Machine" id="machine" name="machine">
                                           <?php echo $machine ?>
                                        </select> 
                                    </div>
                                                   
                                <?php } if ($action == "errorCode") { ?>
                                    <label>Name or Code:</label> &nbsp;
                                    <div class="d-inline">
                                        <select class="custom-select col-sm-1 col-md-1 col-xs-12 mr-3"data-placeholder="Select Machine" id="nameCode" name="nameCode">
                                           <?php echo $nameCode ?>
                                        </select> 
                                    </div>
                                <?php } ?> 

                                <label>Date:</label> &nbsp;
                                <div class="date mr-2">
                                    <button type="button" class="btn btn-default" id="date" name="date">
                                        <i class="far fa-calendar-alt"></i>
                                        Last 30 Days
                                        <i class="fas fa-caret-down"></i>
                                    </button>
                                </div>
                                <button
                                    class="btn btn-sm btn-secondary buttons-excel buttons-html5 btn-export mt-1 mb-1"
                                    type="button">
                                    <i class="fas fa-download"></i> Export PNG
                                </button>

                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="selectedDateRange" name="selectedDateRange" value="">
            </form>

            <div id="chart_script"></div>

            <div class="row" id="dashboard-content">
                <div class="col p-0 pt-3">
                <div class="ChartSize" id="Chart1"></div>
                <div class="ChartSize" id="Chart2"></div>
                </div>
                <!-- /.col -->
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
$('.select2bs4').select2({
  theme: 'bootstrap4'
})

function getValue(wh) {

var wh = wh.value;

$.ajax({
    url: "module/ajax_action.php",
    type: "POST",
    data: {
        "data": wh,
        "action": "Machine",
        "box": "NotTable"
    },
    success: function (data) {
        var jsonData = JSON.parse(data);
        $("#machine").html(jsonData.machine);
        $("#nameCode").html(jsonData.code);
        SendData();
        // console.log(data);
    },
    error: function (data) {
        console.log(data);
        sweetAlert("ผิดพลาด!", "ไม่สามารถแสดงผลข้อมูลได้", "error");
    }
});
}

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
            $("#chart_script").html(data);
            // console.log(data);
            event.stopPropagation();
        },
        error: function (data) {
            console.log(data);
            sweetAlert("ผิดพลาด!", "ไม่สามารถแสดงผลข้อมูลได้", "error");
        }
    });
}

$('#date').daterangepicker(
          {
            ranges   : {
            //   'Today'       : [moment(), moment()],
            //   'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
              'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
              'Last 30 Days': [moment().subtract(29, 'days'), moment()],
              'This Month'  : [moment().startOf('month'), moment().endOf('month')],
              'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate  : moment()
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

$(document).on("click", ".btn-export", function (event) {

var dashboardElement = document.getElementById("dashboard-content");

dashboardElement.classList.add("no-box-shadow");
// สร้างองค์ประกอบแคนวาส
var canvas = document.createElement("canvas");
canvas.width = dashboardElement.offsetWidth;
canvas.height = dashboardElement.offsetHeight;


// แสดงผลลัพธ์ขององค์ประกอบ "dashboard" บนแคนวาส
html2canvas(dashboardElement, {
    background: '#ffffff',
    scale: 2
}).then(function (canvas) {
    // แปลงแคนวาสเป็นรูปภาพ PNG
    var imageData = canvas.toDataURL("image/png");

    // สร้างองค์ประกอบลิงก์ชั่วคราว
    var link = document.createElement("a");
    link.href = imageData;
    link.download = Date.now() + ".png";
    link.target = "_blank";

    // เรียกใช้การดาวน์โหลด
    link.click();
});
});

$(document).ready(function () {
    var startDate = moment().subtract(29, 'days');
    var endDate   = moment();
    $('#selectedDateRange').val(endDate.format('YYYY-MM-DD') + '||//' + startDate.format('YYYY-MM-DD'));

     $('#date').on('apply.daterangepicker', function (event, picker) {
        SendData(); // Trigger SendData when the date range changes
    });
    // Listen for changes in the form elements (warehouse select, etc.)
    $("form#needs-validation").on("change", "select, input[type='checkbox'], button", function () {
        SendData(); // Trigger SendData when form elements change
    });

    $('.dropdown-menu').on('click', function (e) {
        e.stopPropagation();
    });

    SendData();
    
});
</script>