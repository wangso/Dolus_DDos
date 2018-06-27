<?php
$users = $info['users'];

?>


@extends('layouts.master')

@section('content')
    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>MTD | Users</title>

        <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" />

        <style>
            .info-box-number{
                font-size:32px;
            }
            span.label{
                font-size:12px;
            }
        </style>


    </head>
    <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <!-- NAVBAR -->

        <!-- Content Wrapper. Contains page content -->
        <!-- Main content -->
        <section class="content">
            <!-- CURRENT SERVERS -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Current Servers</h3>
                            <div class="box-tools pull-right">
                                <button type="button" onclick="refreshServerStatus()" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-refresh"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="serverTable" class="table table-striped table-bordered table-condensed no-margin">
                                    <thead>
                                    <tr>
                                        <th class='text-center'>User ID</th>
                                        <th class='text-center'>User Name</th>
                                        <th class='text-center'>Source IP</th>
                                        <th class='text-center'>Connection Start Time</th>
                                        <th class='text-center'>Connection Stop Time</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-center">
                                    <?php
                                    // Append Body
                                    if(count($users) > 0){
                                        for($i=0; $i<count($users);$i++){
                                            $userUID = $users[$i]["userUID"];
                                            $username = $users[$i]["username"];
                                            $ipAddress = $users[$i]["ipAddress"];
                                            $connectionStartTime = $users[$i]["connectionStartTime"];
                                            $connectionStopTime = $users[$i]["connectionStopTime"];

                                            // Create Table Row
                                            echo "<tr>";
                                            echo "<td>".$userUID."</td>";
                                            echo "<td>".$username."</td>";
                                            echo "<td>".$ipAddress."</td>";
                                            echo "<td>".$connectionStartTime."</td>";
                                            echo "<td>".$connectionStopTime."</td>";
                                            echo "</tr>";
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <i class="fa fa-bar-chart-o"></i>

                            <h3 class="box-title">Number of Servers Used per Month</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div id="bar-chart" style="height: 300px;"></div>
                        </div>
                        <!-- /.box-body-->
                    </div>
                </div>
            </div>
            <!-- END OF CHART -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- DATATABLES -->
    <link href="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"/>
    <link href="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap4.min.js">


    </body>
    </html>
@endsection

