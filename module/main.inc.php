<?php 
include( __DIR__ . "/include.php" );
require_once __DIR__ . "/dashboard.class.php";

$card     = new mainBoard();
$thisCard = $card -> getCard(); 
$test = $card -> getLastModificationTimesByUniqueName();
// echo '<pre>';
// print_r($test);
// echo '</pre>';
// exit;
?>

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
                                <label>Date:</label> &nbsp;
                                <div class="d-inline">
                                    <button type="button" class="btn btn-default" id="date" name="date">
                                        <i class="far fa-calendar-alt"></i>
                                        Last 30 Days
                                        <i class="fas fa-caret-down"></i>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="selectedDateRange" name="selectedDateRange" value="">
            </form>          

            <div class="row">
                <?php echo $thisCard; ?>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
$('.select2bs4').select2({
  theme: 'bootstrap4'
})

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

            for (var key in jsonData) {
                if (jsonData.hasOwnProperty(key)) {
                    // Get the value for the current key
                    var value = jsonData[key];

                    // Update the corresponding HTML element with the same ID
                    $("#" + key).text(value);
                }
            }
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