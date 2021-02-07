<?php
    require_once '../core/init.php';
    if(!is_logged_in()){
        header('Location: login.php');
    }
    include 'includes/head.php';
    include 'includes/navigation.php';
   
?>

<h1 class="text-center">Welcome to the home page</h1>
<h2 class="text-center">Click on the tabs above to navigate the admin area.</h2>
<?php include 'includes/footer.php'?>
