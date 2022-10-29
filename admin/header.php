<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>supplier Management System in PHP</title>

   
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="../vendor/parsley/parsley.css"/>

    <link rel="stylesheet" type="text/css" href="../vendor/bootstrap-select/bootstrap-select.min.css"/>

    <link rel="stylesheet" type="text/css" href="../vendor/datepicker/bootstrap-datepicker.css"/>

</head>

<body id="page-top">

  

    <div id="wrapper">

        <ul class="navbar-nav bg-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
               
                
                <div class="sidebar-brand-text mx-3">Business Ideas Platform</div>
            </a>

            
            <hr class="sidebar-divider my-0">

            <!-- Main sidebar -->
            <?php
            if($_SESSION['type'] == 'Admin')
            {
            ?>
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">
                    <span>Dashboard</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="supplier.php">
                    <span>supplier</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="client.php">
                    <span>client</span></a>
            </li>
            <?php
            }
            ?>
            <li class="nav-item">
                <a class="nav-link" href="supplier_schedule.php">
                    <span>supplier Schedule</span></a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="appointment.php">
                    <span>Appointment</span></a>
            </li>
            <?php
            if($_SESSION["type"] == 'Admin')
            {
            ?>
            <li class="nav-item">
                <a class="nav-link" href="profile.php">
                    <span>Profile</span></a>
            </li>
            <?php
            } 
            else
            {
            ?>
            <li class="nav-item">
                <a class="nav-link" href="supplier_profile.php">
                    <span>Profile</span></a>
            </li>
            <?php
            }
            ?>
            
           

        </ul>
        
        <div id="content-wrapper" class="d-flex flex-column">

          
            <div id="content">

            
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">


                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <?php
                        $user_name = '';
                        $user_profile_image = '';

                        if($_SESSION['type'] == 'Admin')
                        {
                            $object->query = "
                            SELECT * FROM admin_table 
                            WHERE admin_id = '".$_SESSION['admin_id']."'
                            ";

                            $user_result = $object->get_result();

                            foreach($user_result as $row)
                            {
                                $user_name = $row['admin_name'];
                                $user_profile_image = '../img/undraw_profile.svg';
                            }
                        }

                        if($_SESSION['type'] == 'supplier')
                        {
                            $object->query = "
                            SELECT * FROM supplier_table 
                            WHERE supplier_id = '".$_SESSION['admin_id']."'
                            ";

                            $user_result = $object->get_result();
                            
                            foreach($user_result as $row)
                            {
                                $user_name = $row['supplier_name'];
                                $user_profile_image = $row['supplier_profile_image'];
                            }
                        }

                        
                        ?>

                       
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small" id="user_profile_name"><?php echo $user_name; ?></span>
                                <img class="img-profile rounded-circle"
                                    src="<?php echo $user_profile_image; ?>" id="user_profile_image">
                            </a>
                           
                            <?php
                            if($_SESSION['type'] == 'Admin')
                            {
                            ?>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="profile.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                            <?php
                            }
                            if($_SESSION['type'] == 'supplier')
                            {
                            ?>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="supplier_profile.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                            <?php
                            }
                            ?>
                        </li>

                    </ul>

                </nav>
               
                <div class="container-fluid">