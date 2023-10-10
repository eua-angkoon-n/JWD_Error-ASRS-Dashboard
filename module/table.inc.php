<?php 
include( __DIR__ . "/include.php" );
require_once __DIR__ . "/dashboard.class.php";

$SelectBar    = new DashBoard($action);
$siteSelect   = $SelectBar->getSiteSelect();
$machine      = $SelectBar->getMachine();
$CodeNMachine = $SelectBar->getErrorNameNCode();
?>

<section class="content">
    <div class="card">
        <div class="card-header">
            <h6 class="display-8 d-inline-block font-weight-bold"><i class="fas fa-table"></i>
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

                <div class="row d-flex">

                    <div class="form-group col-md-2 col-sm-12">
                        <label>Warehouse:</label>
                        <select class="form-control col-sm-12 col-md-12 col-xs-12" name="dropdownWH" id="dropdownWH"
                            style="width:100%; font-size:0.85rem;" required="" onchange="getWH(this)">
                            <option value='All'>All</option>
                            <?php echo $siteSelect ?>
                        </select>
                    </div>

                    <div class="form-group col-md-2 col-sm-12">
                        <label>Machine:</label> &nbsp;
                        <select class="form-control col-sm-12 col-md-12 col-xs-12" data-placeholder="Select Machine"
                            id="machine" name="machine" onchange="getCode(this)">
                            <?php echo $machine ?>
                        </select>
                    </div>

                    <div class="form-group col-md-4 col-sm-12">
                        <label>Error Name/Code:</label> &nbsp;
                        <select class="form-control col-sm-12 col-md-12 col-xs-12" data-placeholder="Select Machine"
                            id="NameCode" name="NameCode">
                            <?php echo $CodeNMachine ?>
                        </select>
                    </div>

                </div>

                <div class="row d-flex align-middle">

                    <div class="form-group col-md-3 col-sm-12">
                        <label>Date:</label>
                        <button type="button" class="btn btn-default form-control col-sm-12 col-md-12 col-xs-12"
                            id="date" name="date">
                            <i class="far fa-calendar-alt"></i>
                            Last 30 Days
                            <i class="fas fa-caret-down"></i>
                        </button>
                    </div>
    
                    <div class="form-group col-md-2 col-sm-12 ">
                        <button type="button"
                            class="btn btn-block btn-outline-success btn-showData col-sm-12 col-md-12 col-xs-12">Search</button>
                    </div>

                </div>

                <div class="row pt-3 p-2">
                <div class="col-sm-12 p-0 m-0">
                    <table id="errorTable" class="table table-bordered table-hover dataTable dtr-inline display nowrap"
                        style="width:1000px">
                        <thead>
                            <tr class="bg-light">
                                <th scope="col" class="sorting_disabled">No</th>
                                <th scope="col">Warehouse</th>
                                <th scope="col">Date</th>
                                <th scope="col">Control WCS</th>
                                <th scope="col">Control CELL</th>
                                <th scope="col">Machine</th>
                                <th scope="col">Position</th>
                                <th scope="col">Transport Data Total</th>
                                <th scope="col">Error Code</th>
                                <th scope="col">Error Name</th>
                                <th scope="col">Transfer Equipment</th>
                                <th scope="col">Cycle</th>
                                <th scope="col">Destination</th>
                                <th scope="col">Final Destination Location</th>
                                <th scope="col">Load Size Info (Height)</th>
                                <th scope="col">Load Size Info (Width)</th>
                                <th scope="col">Load Size Info (Length)</th>
                                <th scope="col">Load Size Info (Other)</th>
                                <th scope="col">Weight</th>
                                <th scope="col">Barcode Data</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div><!-- /.row -->

                <input type="hidden" id="selectedDateRange" name="selectedDateRange" value="">
            </form>

        </div>
    </div>
</section>

<script type="text/javascript">
$('.select2bs4').select2({
  theme: 'bootstrap4'
})

function getWH(wh) {

var wh = wh.value;

$.ajax({
    url: "module/ajax_action.php",
    type: "POST",
    data: {
        "data": wh,
        "action": "Machine",
        "box" : "Machine"
    },
    success: function (data) {
        var jsonData = JSON.parse(data);
        $("#machine").html(jsonData.machine);
        $("#NameCode").html(jsonData.code);
        // console.log(data);
    },
    error: function (data) {
        console.log(data);
        sweetAlert("ผิดพลาด!", "ไม่สามารถแสดงผลข้อมูลได้", "error");
    }
});
}

function getCode(mc) {

var dropdownWHValue = document.getElementById("dropdownWH").value;
var mc = mc.value;

$.ajax({
    url: "module/ajax_action.php",
    type: "POST",
    data: {
        "data": dropdownWHValue,
        "action": "Machine",
        "machine":mc,
        "box" : "Code"
    },
    success: function (data) {
        var jsonData = JSON.parse(data);
        $("#NameCode").html(jsonData.code);
        // console.log(data);
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
            $('#reportrange span').html(start.format('d/M/YY') + ' - ' + end.format('d/M/YY'))
            if (label === 'Custom Range') {
            $('#date').html('<i class="far fa-calendar-alt"></i> ' + start.format('d/M/YY') + ' - ' + end.format('d/M/YY') + ' <i class="fas fa-caret-down"></i>');
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
     
    });
    // Listen for changes in the form elements (warehouse select, etc.)
    $("form#needs-validation").on("change", "select, input[type='checkbox'], button", function () {
    
    });

    $('.dropdown-menu').on('click', function (e) {
        e.stopPropagation();
    });

    
});

$('#errorTable').DataTable({
    "scrollX": true,
    "processing": true,
    "serverSide": true,
    "order": [0, 'desc'], //ถ้าโหลดครั้งแรกจะให้เรียงตามคอลัมน์ไหนก็ใส่เลขคอลัมน์ 0,'desc'
    "aoColumnDefs": [{
            "bSortable": false,
            "aTargets": [0]
        }, //คอลัมน์ที่จะไม่ให้ฟังก์ชั่นเรียง
        {
            "bSearchable": false,
            "aTargets": [0,2,3,4,5,6,7,10,11,12,13,14,15,16,17,18,19]
        } //คอลัมน์ที่จะไม่ให้เสริท
    ],
    ajax: {
        beforeSend: function () {
            //จะให้ทำอะไรก่อนส่งค่าไปหรือไม่
        },
        url: 'module/module_errorDetails/datatable_processing.php',
        type: 'POST',
        data: function (data) {
            data.formData = $('#needs-validation').serialize();
        },
        error: function (xhr, error, code) {
            console.log(xhr, code);
        },
        async: false,
        cache: false,
    },
    "lengthMenu": [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, "All"]
    ],
    "language": {
        "decimal":        "",
        "emptyTable":     "No data available in table",
        "info":           "Showing _START_ to _END_ of _TOTAL_ entries",
        "infoEmpty":      "Showing 0 to 0 of 0 entries",
        "infoFiltered":   "(filtered from _MAX_ total entries)",
        "infoPostFix":    "",
        "thousands":      ",",
        "lengthMenu":     "Show _MENU_ entries",
        "loadingRecords": "Loading...",
        "processing":     "",
        "search":         "Search:",
        "zeroRecords":    "No matching records found",
        "paginate": {
            "first":      "First",
            "last":       "Last",
            "next":       "Next",
            "previous":   "Previous"
        },
        "aria": {
            "sortAscending":  ": activate to sort column ascending",
            "sortDescending": ": activate to sort column descending"
        }
    },
    "paging": true,
    "lengthChange": true, //ออฟชั่นแสดงผลต่อหน้า
    "pagingType": "simple_numbers",
    "pageLength": 10,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": true,
    //"responsive": true,
    "buttons": ["excel", "colvis"]
}).buttons().container().appendTo('#errorTable_wrapper .col-md-6:eq(0)');

$('input[type=search]').attr('placeholder', 'Warehouse, Name/Code');

$(document).on("click", ".btn-showData", function (event) {
    $('#errorTable').DataTable().ajax.reload();
});
</script>