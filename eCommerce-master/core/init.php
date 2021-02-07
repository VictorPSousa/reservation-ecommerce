<!-- Connects to MySQL database, the first parameter might have to be changed to the host providers server numbers, third parameter is for password -->
<?php
$db =mysqli_connect('127.0.0.1','root','','eCommerce');

if(mysqli_connect_errno()) {
    echo 'Database connection has failed with the following errors: '. mysqli_connect_error();
    die();
}
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/eCommerce/config.php';
require_once BASEURL.'helpers/helpers.php';
require BASEURL.'/vendor/autoload.php';

$cart_id ='';
// If cookie exists, then set that to cart id for 30 days. 
if(isset($_COOKIE[CART_COOKIE])){
    $cart_id = sanitize($_COOKIE[CART_COOKIE]);
}

// Get user data if session is set
if(isset($_SESSION['SBUser'])){
    $user_id = $_SESSION['SBUser'];
    $query = $db->query("SELECT * FROM users WHERE id = '$user_id'");
    $user_data = mysqli_fetch_assoc($query);
    $fn = explode(' ', $user_data['full_name']);
    $user_data['first'] = $fn[0];
    $user_data['last'] = $fn[1];
}


// Session flash checks
if(isset($_SESSION['success_flash'])){
    echo '<div class="bg-success"><p class="text-success text-center">'.$_SESSION['success_flash'].'</p></div>'; 
    unset($_SESSION['success_flash']);   
    
}
// Error session flash checks
if(isset($_SESSION['error_flash'])){
    echo '<div class="bg-danger"><p class="text-danger text-center">'.$_SESSION['error_flash'].'</p></div>'; 
    unset($_SESSION['error_flash']);  
}
