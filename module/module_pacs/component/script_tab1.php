<script type="text/javascript">
$(document).ready(function () {

    date();
    getDashboard();
});

$('#daterange-btn').on('apply.daterangepicker', function (event, picker) {
    getDashboard();
});

$('#interval-btn').on('apply.daterangepicker', function (event, picker) {
    getDashboard();
});


$("form#needs-validation").on("change", "select, input[type='checkbox'], button", function () {
    getDashboard();
});

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

function date() {
    var startDate = <?php  if($viewMode){ ?>  moment().subtract(2, 'days') <?php }else{?> moment().subtract(7, 'days') <?php } ?>;
    var endDate   = <?php  if($viewMode){ ?>  moment().subtract(2, 'days') <?php }else{?> moment() <?php } ?>;
    var Intervals = <?php  if($viewMode){ ?>  moment().subtract(1, 'hours') <?php }else{?> moment().subtract(1, 'days') <?php } ?>;
    $('#daterange-btn').daterangepicker({
            ranges: {
                //   'Today'       : [moment(), moment()],
                //   'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: startDate,
            endDate: endDate,
            maxDate: moment()
        },
        function (start, end, label) {
            $('#selectedDateRange').val(end.format('YYYY-MM-DD') + '||//' + start.format('YYYY-MM-DD'));
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
            if (label === 'Custom Range') {
                $('#daterange-btn').html('<i class="far fa-calendar-alt"></i> ' + start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY') + ' <i class="fas fa-caret-down"></i>');
            } else {
                $('#daterange-btn').html('<i class="far fa-calendar-alt"></i> ' + label + ' <i class="fas fa-caret-down"></i>');
            }
        })

    $('#interval-btn').daterangepicker({
        ranges: {
            'Hourly values': [moment().subtract(1, 'hours'), moment()],
            'Daily values': [moment().subtract(1, 'days'), moment()],
            'Weekly values': [moment().subtract(1, 'weeks'), moment()],
            'Monthly values': [moment().subtract(1, 'months'), moment()],
            'Yearly values': [moment().subtract(1, 'years'), moment()]
        },
        startDate: Intervals, // Default: Last 7 Days
        endDate: moment(),
        maxDate: moment(),
        showCustomRangeLabel: false
    }, function (start, end, label) {
        let interval;
        switch (label) {
            case 'Hourly values':
                interval = 'hour';
                break;
            case 'Daily values':
                interval = 'day';
                break;
            case 'Weekly values':
                interval = 'week';
                break;
            case 'Monthly values':
                interval = 'month';
                break;
            case 'Yearly values':
                interval = 'year';
                break;
            default:
                interval = 'custom';
                break;
        }

        $('#selectedDateRange').val(interval);
        $('#interval').val(interval);
        $('#reportrange span').html(new Date(start).toLocaleDateString() + ' - ' + new Date(end).toLocaleDateString());

        $('#interval-btn').html('<i class="far fa-clock"></i> ' + label + ' <i class="fas fa-caret-down"></i>');

    });


}


function getDashboard() {

    var dateSelect = $('#daterange-btn').data('daterangepicker').startDate.format('YYYY-MM-DD') + '||//' +
        $('#daterange-btn').data('daterangepicker').endDate.format('YYYY-MM-DD');
    var intervalSelect = <?php if($viewMode){ ?>  "hour" <?php }else{?> $('#interval').val() <?php } ?>;

    $.ajax({
        url: "module/module_pacs/function/f-ajax.php",
        type: "POST",
        data: {
            "date": dateSelect,
            "interval": intervalSelect,
            "action": 'getDashboard'
        },
        beforeSend: function () {},
        success: function (data) {
            var js = JSON.parse(data);
            // console.log(js.interval_chart);
            // return false;

            $('#err_total').html(js.err_total);
            $('#err_crane').html(js.err_crane);
            $('#err_conveyor').html(js.err_conveyor);
            $('#err_stv').html(js.err_stv);

            google.charts.load('current', {
                packages: ['corechart', 'bar']
            });
            google.charts.setOnLoadCallback(function () {
                drawBar(js.err_chart);
                drawColumn(js.interval_chart);
            });
            // google.charts.setOnLoadCallback(drawColumn);

            $('#last_tran_date').html(js.last_tran['tran_date_time']);
            $('#last_tran_machine').html("Machine : " + js.last_tran['Machine']);
            $('#last_update_date').html(js.last_update['date']);
            $('#last_update_name').html("By : " + js.last_update['name']);
        }
    });
}

function drawBar(arrData) {

    var dataArray = [
        ['Error', 'Total', {
            type: 'string',
            role: 'annotation'
        }, {
            role: 'style'
        }]
    ];

    var colorArray = <?php echo json_encode(Setting::$PACAChart); ?>;
    var max = 0;
    for (var i = 0; i < arrData.length; i++) {
        dataArray.push([arrData[i].Error_Name, parseInt(arrData[i].total), arrData[i].Error_Name + " - " + arrData[i].total, arrData[i].color]);
        if (parseInt(arrData[i].total) > max) {
            max = parseInt(arrData[i].total);
        }
    }

    var data = google.visualization.arrayToDataTable(dataArray);

    var options = {
        title: 'Total Error Log',
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
            width: '95%',
            height: '95%'
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

        }
    };

    var chart = new google.visualization.BarChart(document.getElementById('chart_div'));

    chart.draw(data, options);
}

function drawColumn(arrData) {

    var interval = arrData[0]['interval'];
    var max = 0;
    var date;
    var data = new google.visualization.DataTable();

    switch(interval){
        case 'hour':
            data.addColumn('string', 'Time');
            data.addColumn('number', 'Crane');
            data.addColumn({type: 'number', role: 'annotation'});
            data.addColumn({type: 'string', role: 'style'});
            data.addColumn('number', 'Conveyor');
            data.addColumn({type: 'number', role: 'annotation'});
            data.addColumn({type: 'string', role: 'style'});
            data.addColumn('number', 'STV');
            data.addColumn({type: 'number', role: 'annotation'});
            data.addColumn({type: 'string', role: 'style'});
            arrData.forEach(function(item) {
                date = formatHourDate(item.date);
                data.addRow([
                    ''+date, item.crane, item.crane, item.color1,
                    item.conveyor, item.conveyor, item.color2,
                    item.stv, item.stv, item.color3,
            ]);
                if(item.crane > max){
                    max = item.crane;
                }
                if(item.conveyor > max){
                    max = item.conveyor;
                }
                if(item.stv > max){
                    max = item.stv;
                }
            });
        break;
        case 'day':
            data.addColumn('datetime', 'Date');
            data.addColumn('number', 'Crane');
            data.addColumn({type: 'number', role: 'annotation'});
            data.addColumn({type: 'string', role: 'style'});
            data.addColumn('number', 'Conveyor');
            data.addColumn({type: 'number', role: 'annotation'});
            data.addColumn({type: 'string', role: 'style'});
            data.addColumn('number', 'STV');
            data.addColumn({type: 'number', role: 'annotation'});
            data.addColumn({type: 'string', role: 'style'});
            arrData.forEach(function(item) {
                date = new Date(item.date);
                fDate = formatDate(item.date, interval);
                data.addRow([
                    {v:date, f: fDate}, 
                    item.crane, item.crane, item.color1,
                    item.conveyor, item.conveyor, item.color2,
                    item.stv, item.stv, item.color3,
                ]);
                if(item.crane > max){
                    max = item.crane;
                }
                if(item.conveyor > max){
                    max = item.conveyor;
                }
                if(item.stv > max){
                    max = item.stv;
                }
            });
        break;
        case 'week':
            data.addColumn('string', 'Week');
            data.addColumn('number', 'Crane');
            data.addColumn({type: 'number', role: 'annotation'});
            data.addColumn({type: 'string', role: 'style'});
            data.addColumn('number', 'Conveyor');
            data.addColumn({type: 'number', role: 'annotation'});
            data.addColumn({type: 'string', role: 'style'});
            data.addColumn('number', 'STV');
            data.addColumn({type: 'number', role: 'annotation'});
            data.addColumn({type: 'string', role: 'style'});

            arrData.forEach(function(item) {
                var weekInfo = formatDateToWeek(item.date);

                data.addRow([
                    weekInfo, 
                    item.crane, item.crane, item.color1,
                    item.conveyor, item.conveyor, item.color2,
                    item.stv, item.stv, item.color3,
                ]);
                if(item.crane > max){
                    max = item.crane;
                }
                if(item.conveyor > max){
                    max = item.conveyor;
                }
                if(item.stv > max){
                    max = item.stv;
                }
            });
            break;
        case 'month':
            data.addColumn('string', 'Month');
            data.addColumn('number', 'Crane');
            data.addColumn({type: 'number', role: 'annotation'});
            data.addColumn({type: 'string', role: 'style'});
            data.addColumn('number', 'Conveyor');
            data.addColumn({type: 'number', role: 'annotation'});
            data.addColumn({type: 'string', role: 'style'});
            data.addColumn('number', 'STV');
            data.addColumn({type: 'number', role: 'annotation'});
            data.addColumn({type: 'string', role: 'style'});
            arrData.forEach(function(item) {
                fDate = formatDate(item.date, interval);
                data.addRow([
                    fDate, 
                    item.crane, item.crane, item.color1,
                    item.conveyor, item.conveyor, item.color2,
                    item.stv, item.stv, item.color3,
                ]);
                if(item.crane > max){
                    max = item.crane;
                }
                if(item.conveyor > max){
                    max = item.conveyor;
                }
                if(item.stv > max){
                    max = item.stv;
                }
            });
            break;
        case 'year':
            data.addColumn('string', 'Year');
            data.addColumn('number', 'Crane');
            data.addColumn({type: 'number', role: 'annotation'});
            data.addColumn({type: 'string', role: 'style'});
            data.addColumn('number', 'Conveyor');
            data.addColumn({type: 'number', role: 'annotation'});
            data.addColumn({type: 'string', role: 'style'});
            data.addColumn('number', 'STV');
            data.addColumn({type: 'number', role: 'annotation'});
            data.addColumn({type: 'string', role: 'style'});
            arrData.forEach(function(item) {
                data.addRow([
                    ""+item.date+"", 
                    item.crane, item.crane, item.color1,
                    item.conveyor, item.conveyor, item.color2,
                    item.stv, item.stv, item.color3,
                ]);
                if(item.crane > max){
                    max = item.crane;
                }
                if(item.conveyor > max){
                    max = item.conveyor;
                }
                if(item.stv > max){
                    max = item.stv;
                }
            });
            break;
        default:
        data.addColumn('string', 'No Data');
            data.addColumn('number', 'Error Log');
            data.addRow(["No Data", 0]);
            max = 100;
            break;
    }

    var options = {
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
            format: 'd MMM yyyy',
        },
        vAxis: {
            viewWindow: {
                min: 0, // Minimum value for the V-axis
                max: max +10, // Maximum value for the V-axis
            },
        },
        legend: {
            position: 'none'
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

    var chart = new google.visualization.ColumnChart(
        document.getElementById('column_div'));

    chart.draw(data, options);
}

function formatDateToWeek(dateString) {
    var split = dateString.split("-");
    var year = split[0];
    var month = split[1] - 1;
    var date = new Date(year, month, 1);
    var engMonth = date.toLocaleString('en-US', { month: 'short' });
    var week = split[2];
    var rangeDate;

    if (week == 1) {
        rangeDate = '1-7';
    } else if (week == 2) {
        rangeDate = '8-14';
    } else if (week == 3) {
        rangeDate = '15-21';
    } else if (week == 4) {
        rangeDate = '22-28';
    } else {
        var lastDayOfMonth = new Date(year, month + 1, 0).getDate();
        rangeDate = '29-' + lastDayOfMonth;
    }

    return rangeDate + " " + engMonth + " " + year;
}

function formatDate(dateString, type) {

    var split = dateString.split(" ");
    var fDate = split[0].split("-");
    var year = fDate[0];
    var month = fDate[1];
    var dates = new Date(year, month, 0);
    var engMonth = dates.toLocaleString('en-US', { month: 'short' });
    var date = fDate[2];

    switch(type){
        case 'day':
            var r = date + " " + engMonth + " " + year;
            break;
        case 'month':
            var r = engMonth + " " + year;
            break;
        case 'year':
            var r = year;
            break;
    }
    

    return r;
}

function formatHourDate(dateStr){
    var dateObj = new Date(dateStr);

    // ดึงข้อมูลวันที่และเวลา
    var day = dateObj.getDate();
    var monthIndex = dateObj.getMonth();
    var year = dateObj.getFullYear();
    var hours = dateObj.getHours();
    var minutes = dateObj.getMinutes();

    // กำหนดรูปแบบของเดือน
    var monthNames = [
      'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
    ];

    // จัดรูปแบบวันที่ตามที่ต้องการ
    var formattedDate = ('0' + day).slice(-2) + '/' + monthNames[monthIndex] + ' ' + ('0' + hours).slice(-2) + '.' + ('0' + minutes).slice(-2);
    return formattedDate;
}
</script>