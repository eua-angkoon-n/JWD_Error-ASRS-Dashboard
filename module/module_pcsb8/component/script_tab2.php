<script type="text/javascript">
    $('#reservationtime').daterangepicker({
      timePicker: true,
      timePicker24Hour: true, // เลือกให้เป็นรูปแบบ 24 ชั่วโมง
      timePickerIncrement: 15,
      startDate: moment().subtract(7, 'days').startOf('day'), // เริ่มต้นเป็น 7 วันก่อน
      endDate: moment().endOf('day'), // สิ้นสุดที่สูงสุดคือวันพรุ่งนี้
      maxDate:moment().add(1, 'day'),
      locale: {
        format: 'MM/DD/YYYY HH:mm' // ใช้ HH เพื่อระบุรูปแบบ 24 ชั่วโมง
      }
    });

    $('.select2').select2({
        theme: 'bootstrap4'
    })

    $(document).on("click", "#custom-tab2", function (){    
        setTimeout(() => {
            $('#list_table').DataTable().ajax.reload();
        }, "250"); 
    }); 

    $("form#tab2").on("change", "select, input[type='text'], button", function () {
        $('#list_table').DataTable().ajax.reload();
    });

    $(document).ready(function () {
        
        $('#list_table').DataTable({
               "processing": true,
               "serverSide": true,
               "order": [1, 'desc'], //ถ้าโหลดครั้งแรกจะให้เรียงตามคอลัมน์ไหนก็ใส่เลขคอลัมน์ 0,'desc'
               "aoColumnDefs": [{
                       "bSortable": false,
                       "aTargets": [0]
                   }, //คอลัมน์ที่จะไม่ให้ฟังก์ชั่นเรียง
                   {
                       "bSearchable": false,
                       "aTargets": [0, 1, 2, 3, 4]
                   } //คอลัมน์ที่จะไม่ให้เสิร์ช
               ],
               "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json" // กำหนด URL ของไฟล์ภาษาอังกฤษ
                },
               ajax: {
                   beforeSend: function () {
                       //จะให้ทำอะไรก่อนส่งค่าไปหรือไม่
                   },
                   url: 'module/module_pcsb8/function/f-table.php',
                   type: 'POST',
                   data: function (data) {
                       data.formData = $('#tab2').serialize();
                       data.action = 'list';
                   },
                   async: false,
                   cache: false,
                   error: function (xhr, error, code) {
                       console.log(xhr, code);
                   },
               },
               "paging": true,
               "lengthChange": true, //ออฟชั่นแสดงผลต่อหน้า
               "pagingType": "simple_numbers",
               "pageLength": 10,
               "searching": true,
               "ordering": true,
               "info": true,
               "autoWidth": false,
               "scrollX": true,
               // "responsive": true,
           });
   
       });


</script>