 <!-- Navbar -->
 <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <?php if(!$viewMode){ ?>
                <li class="nav-item">
                    <a class="nav-link" id="pushmenu" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-2x fa-bars"></i></a>
                </li>
                <?php } ?>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="./" class="nav-link">Home</a>
                </li>
                <!-- <li class="nav-item d-none d-sm-inline-block">
                    <a href="?module=howto" class="nav-link">คู่มือการใช้งาน</a>
                </li> -->
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <div class="ul-datetime-clock">
                        <ul>
                            <li><?php echo Setting::$arr_day_of_weekEN[date('N', strtotime('today'))]; ?>
                                <?php echo nowDateEN(date('Y-m-d H:i:s')); ?>&nbsp;
                            </li>
                            <li id="currentTime"></li>
                        </ul>
                    </div>
                    <!--clock-->
                </li>
            </ul>

        </nav>
        <!-- /.navbar -->