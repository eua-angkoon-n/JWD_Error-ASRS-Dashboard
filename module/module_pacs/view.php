<?php 
require_once __DIR__ . "/../../component/link.inc.php";
require_once __DIR__ . "/../../component/style.inc.php";
require_once __DIR__ . "/../../component/script.inc.php";
?>

<section class="content">
    <div class="card">
        <div class="card-header">
            <h6 class="display-8 d-inline-block font-weight-bold"><i class="fas fa-warehouse"></i>
                <?PHP echo $title_site; ?>
            </h6>
            <div class="card-tools">
                <ol class="breadcrumb float-sm-right pt-1 pb-1 m-0">
                    <li class="breadcrumb-item"><a href="./">Home</a></li>
                    <li class="breadcrumb-item active">
                        <?PHP echo $title_site ?>
                    </li>
                </ol>
            </div>
        </div>

        <div class="card-body p-1">
           
        <div class="col-12 col-sm-12 pt-1">
            <div class="card card-primary card-tabs">
              <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tab1" data-toggle="pill" href="#custom-tabs-1" role="tab" aria-controls="custom-tabs-1" aria-selected="true">Statistics</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tab2" data-toggle="pill" href="#custom-tabs-2" role="tab" aria-controls="custom-tabs-2" aria-selected="false">List Table</a>
                  </li>

                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                  <div class="tab-pane fade show active" id="custom-tabs-1" role="tabpanel" aria-labelledby="custom-tabs-1">
                      <?php include_once __DIR__ . '/frame/tab1.php'; //หน้ารายการ ?>
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-2" role="tabpanel" aria-labelledby="custom-tabs-2">
                      <?php include_once __DIR__ . '/frame/tab2.php'; //หน้ารายการ ?>
                  </div>
                </div>
              </div>
              <!-- /.card -->
            </div>
          </div>

        </div>
    </div>
</section>

<?php 
include __DIR__ . '/component/script_map.php';
?>