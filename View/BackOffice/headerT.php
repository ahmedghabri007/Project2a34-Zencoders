<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Dynamic title -->
    <title>
        <?php 
            if (!empty($pageTitle)) {
                echo htmlspecialchars($pageTitle);
            } elseif (!empty($ticketPage)) {
                echo 'Ticket Details';
            } else {
                echo 'Dashboard';
            }
        ?>
    </title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
      href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900"
      rel="stylesheet"
    >

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php 
        // Dynamically include the appropriate sidebar 
        if (!empty($ticketPage)) {
            include __DIR__ . '/sidebarticket.php';
        } else {
            include __DIR__ . '/sidebar.php';
        }
        ?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav
                  class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow"
                >
                    <button
                      id="sidebarToggleTop"
                      class="btn btn-link d-md-none rounded-circle mr-3"
                    >
                        <i class="fa fa-bars"></i>
                    </button>
                    <!-- Page heading -->
                    <h4 class="ml-3 mb-0 text-gray-800">
                        <?php 
                            if (!empty($ticketPage)) {
                                echo 'Ticket Management';
                            } else {
                                echo 'Event Management';
                            }
                        ?>
                    </h4>
                </nav>
                <!-- End of Topbar -->
