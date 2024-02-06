<?php include (__DIR__ . "/../function/f-tab2.php"); ?>
<form id="tab2" class="addform " name="addform" method="POST" enctype="multipart/form-data" autocomplete="off" novalidate="">
<div class="row">

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Date:</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                </div>
                <input type="text" class="form-control float-right" id="reservationtime" name="reservationtime">
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Machine:</label>
            <select class="select2" multiple="multiple" data-placeholder="Select Machine" style="width: 100%;" id="machine" name="machine[]">
              <?php echo $machine; ?>
            </select>
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Error Name:</label>
            <select class="select2" multiple="multiple" data-placeholder="Select a Error Name" style="width: 100%;" id="errorName" name="errorName[]">
            <?php echo $errName; ?>
            </select>
        </div>
    </div>

</div>
</form>
<div class="row">

    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="col-12 p-1">
                <div class=" p-0 m-0 w-100 d-flex flex-column">
                    <div class="row p-0 m-0">
                        <div class="offset-md-0 col-md-12 offset-md-0 w-100 p-1">

                            <div class="row">
                                <div class="col-sm-12 p-0 m-0">

                                    <table id="list_table"
                                        class="table table-bordered table-hover dataTable dtr-inline nowrap">
                                        <thead>
                                            <tr class="bg-light text-center">
                                                <th class="sorting_disabled" style="width:2%">No</th>
                                                <th scope="col" style="width:2%">Tran Date</th>
                                                <th scope="col" style="width:4%">Machine</th>
                                                <th scope="col" style="width:5%">Error Name</th>
                                                <th scope="col" style="width:2%">Control WCS</th>
                                                <th scope="col" style="width:2%">Transfer Equipment</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>

                                </div>
                            </div>

                        </div>
                        <!--card-->
                    </div>

                </div>
                <!--row-->
        </div>
        <!--container-->

        

    </div>
</div>

<?php 
include __DIR__ . '/../component/script_tab2.php';
?>