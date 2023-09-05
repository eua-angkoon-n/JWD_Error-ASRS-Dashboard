<?php 
include( __DIR__ . "/include.php" );

$con = connect_database();
$obj = new CRUD($con);
try {
    $fetchSite = $obj -> fetchRows("SELECT * FROM asrs_error_wh WHERE 1=1 ORDER BY site_name ASC");
    $siteSelect = '';
    foreach($fetchSite as $key => $value){
        $siteSelect.= '<li><label><input type="checkbox" name="dropdownWH[]" value="' . $value['site_name'] . '" checked> ' . $value['site_name'] . '</label></li>';
        
    }
} catch( Exception $e ) {     
    echo "Caught exception : <b>".$e->getMessage()."</b><br/>";
} finally {
    $con = null;
}

$currentMonth = date('m');
$previousMonth = date("m", strtotime("-1 months"));
$monthSelect ='';
for ($i = 1; $i <= 12; $i++) {
    $monthSelect.='<li><label><input type="checkbox" name="dropdownMonth[]" value="'.str_pad($i, 2, "0", STR_PAD_LEFT).'" '.(($i==$currentMonth) || ($i==$previousMonth) ? 'checked':'').' > ' . Setting::$arr_newMonthsEN[str_pad($i, 2, "0", STR_PAD_LEFT)] . '</label></li>';
}
$currentYear = date('Y');
$yearSelect = '';
for ($year = 2023; $year <= $currentYear; $year++) {
    $yearSelect.='<li><label><input type="checkbox" name="dropdownYear[]" value="' . $year . '" '.($year==$currentYear ? 'checked':'').'> ' . $year . '</label></li>';
}

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
                                <label>Warehouse:</label>
                                <div class="d-inline">
                                    <button
                                        class="btn btn-default dropdown-toggle col-sm-2 col-md-2 col-xs-12 mr-3 justify-content-between"
                                        type="button" id="dropdownWH" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="true">
                                        Warehouse
                                    </button>
                                    <ul class="dropdown-menu checkbox-menu allow-focus">
                                        <?php echo $siteSelect; ?>
                                    </ul>
                                </div>
                                <label>Month:</label>
                                <div class="d-inline">
                                    <button
                                        class="btn btn-default dropdown-toggle col-sm-4 col-md-3 col-xs-12 mr-3 justify-content-between"
                                        type="button" id="dropdownMonth" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="true">
                                        Month
                                    </button>
                                    <ul class="dropdown-menu checkbox-menu allow-focus">
                                        <?php echo $monthSelect; ?>
                                    </ul>
                                </div>
                                <div class="d-inline">
                                    <label>Year:</label>
                                    <button
                                        class="btn btn-default dropdown-toggle col-sm-2 col-md-2 col-xs-12 mr-3 justify-content-between"
                                        type="button" id="dropdownYear" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="true">
                                        Year
                                    </button>
                                    <ul class="dropdown-menu checkbox-menu allow-focus">
                                        <?php echo $yearSelect; ?>
                                    </ul>
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
            </form>

            <div id="chart_script"></div>

            <div class="row">
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
function updateMonthButtonText() {
    var selectedMonths = [];
    $("input[name='dropdownMonth[]']:checked").each(function () {
        selectedMonths.push($(this).parent().text().trim());
    });
    if (selectedMonths.length > 0) {
        var buttonText = "";
        if (selectedMonths.length === 1) {
            buttonText = selectedMonths[0];
        } else {
            buttonText = selectedMonths[0] + " - " + selectedMonths[selectedMonths.length - 1];
        }
        $("#dropdownMonth").text(buttonText);
    } else {
        $("#dropdownMonth").text("Select Month");
    }
}

function updateYearButtonText() {
    var selectedYears = [];
    $("input[name='dropdownYear[]']:checked").each(function () {
        selectedYears.push($(this).parent().text().trim());
    });
    if (selectedYears.length > 0) {
        var buttonText = "";
        if (selectedYears.length === 1) {
            buttonText = selectedYears[0];
        } else {
            buttonText = selectedYears[0] + " - " + selectedYears[selectedYears.length - 1];
        }
        $("#dropdownYear").text(buttonText);
    } else {
        $("#dropdownYear").text("Select Year");
    }
}

function TitleText() {
    var siteText = $("#site option:selected").text();

    var monthText = " ช่วงเดือน " + $("#dropdownMonth").text();
    if ($("#dropdownMonth").text() == "เลือกเดือน") {
        monthText = "";
    }
    var yearText = " " + $("#dropdownYear").text();
    if ($("#dropdownYear").text() == "เลือกปี") {
        yearText = "";
    }
    if ($("#dropdownYear").text().includes("-")) {
        yearText = " ," + yearText;
    }
    $(".title-text").text(siteText + monthText + yearText);
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
            console.log(data);
            event.stopPropagation();
        },
        error: function (data) {
            console.log(data);
            sweetAlert("ผิดพลาด!", "ไม่สามารถแสดงผลข้อมูลได้", "error");
        }
    });
}

$(".checkbox-menu").on("change", "input[type='checkbox']", function () {
    $(this).closest("li").toggleClass("active", this.checked);
});

$(".checkbox1-menu").on("change", "input[type='checkbox']", function () {
    $(this).closest("li").toggleClass("active", this.checked);
});

$(document).ready(function () {

    var selectedYear = parseInt($("input[name='dropdownYear[]']:checked").val());
    var currentYear = new Date().getFullYear();
    var currentMonth = new Date().getMonth() + 1;

    // Enable or disable month checkboxes based on selected year
    $("input[name='dropdownMonth[]']").prop("disabled", false);

    if (selectedYear < currentYear) {
        $("input[name='dropdownMonth[]']").prop("disabled", false);
    } else if (selectedYear === currentYear) {
        $("input[name='dropdownMonth[]']").each(function () {
            var monthValue = parseInt($(this).val());
            $(this).prop("disabled", monthValue > currentMonth);
        });
    }

    $('.dropdown-menu').on('click', function (e) {
        e.stopPropagation();
    });

    updateMonthButtonText();
    updateYearButtonText();
    SendData();
    TitleText();

});

$(document).on("change", "form", function () {

var selectedYear = parseInt($("input[name='dropdownYear[]']:checked").val());
var currentYear = new Date().getFullYear();
var currentMonth = new Date().getMonth() + 1;
updateMonthButtonText();
updateYearButtonText();
SendData();
TitleText();

// Enable or disable month checkboxes based on selected year
$("input[name='dropdownMonth[]']").prop("disabled", false);

if (selectedYear < currentYear) {
    $("input[name='dropdownMonth[]']").prop("disabled", false);
} else if (selectedYear === currentYear) {
    $("input[name='dropdownMonth[]']").each(function () {
        var monthValue = parseInt($(this).val());
        $(this).prop("disabled", monthValue > currentMonth);
    });
}
});
</script>