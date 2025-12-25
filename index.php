<?php 
$title = "Admin";
require_once "includes/header.php"; 

if(isset($_GET['menu'])){
    $menu = $_GET['menu'];
}else{
    $menu = 1;
}
?>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <?php require_once "includes/navbar.php" ?>
        <!-- /.navbar -->
        <!-- Main Sidebar Container -->
        <?php require_once "includes/sidebar.php" ?>
        <!-- / Main Sidebar Container -->

        <!-- Content Wrapper. Contains page content -->
        <?php
        switch ($menu) {
            case 1: require_once "pages/dashboard.php"; break;
            case 2: require_once "pages/users.php"; break;
            case 3: require_once "pages/membertype.php"; break;
            default:require_once "pages/dashboard.php"; break;
        }
        
        ?>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <?php require_once "includes/footer.php" ?>
        <!-- /.Main Footer -->
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <?php require_once "includes/required_scripts.php" ?>

</body>

</html>