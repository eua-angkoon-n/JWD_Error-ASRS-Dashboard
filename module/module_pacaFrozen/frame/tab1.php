
<form id="needs-validation" class="addform " name="addform" method="POST" enctype="multipart/form-data"
                autocomplete="off" novalidate="">
<div class="row">
    <!-- <div class="col-12"> -->
        <!-- Date and time range -->
        <div class="form-group mr-1">
            <div class="input-group">
                <button type="button" class="btn btn-default float-right" id="daterange-btn">
                    <i class="far fa-calendar-alt"></i> Last 7 Days
                    <i class="fas fa-caret-down"></i>
                </button>
            </div>
        </div>
        <div class="form-group mr-1">
            <div class="input-group">
                <button type="button" class="btn btn-default float-right" id="interval-btn">
                    <i class="far fa-clock"></i> Interval
                    <i class="fas fa-caret-down"></i>
                </button>
            </div>
        </div>

        <div class="form-group">
            <div class="input-group">
                <button class="btn btn-outline-secondary btn-export" type="button">
                    <i class="fas fa-download"></i> Export PNG
                </button>
            </div>
        </div>
        <input type="hidden" value="day" id="interval" />
        <!-- /.form group -->
    <!-- </div> -->
</div>

<div class="row">
    <div class="col-sm-12 col-md-9 mb-1" id="dashboard-content">
        <div class="row pt-1 pb-1">
            <div class="col-sm-12 col-md-4">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 id="err_log">0</h3>

                        <p>Error Log</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-4">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3 id="err_crane">0</h3>

                        <p>Crane Error Log</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-truck-loading"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-4">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3 id="err_conveyor">0</h3>

                        <p>Conveyor Error Log</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-pallet"></i> 
                    </div> 
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div id="column_div" style="height:500px"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div id="chart_div" style="height:500px"></div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-3">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-xs-12  d-flex align-items-stretch" style="height: 250px">
                <div id="map_canvas" class="w-100 h-100"></div>
            </div>
        </div>
        <div class="row mt-2 pt-1">
        <div class="col-12">
            <div class="info-box bg-gradient-primary">
              <span class="info-box-icon"><i class="far fa-clock"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Last Tran Error Date Time</span>
                <span class="info-box-number" id="last_tran_date">-</span>

                <div class="progress">
                  <div class="progress-bar" style="width: 70%"></div>
                </div>
                <span class="progress-description" id="last_tran_machine">
                    -
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </div>
        <div class="row">
        <div class="col-12">
            <div class="info-box bg-gradient-primary">
              <span class="info-box-icon"><i class="fas fa-pencil-alt"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Last Data Update</span>
                <span class="info-box-number" id="last_update_date">-</span>

                <div class="progress">
                  <div class="progress-bar" style="width: 70%"></div>
                </div>
                <span class="progress-description" id="last_update_name">
                    -
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </div>
    </div>
</div>

</form>

<?php 
include __DIR__ . '/../component/script_tab1.php';
?>