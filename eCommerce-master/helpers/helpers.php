<?php
function display_errors($errors){
    $display = '<ul class="bg-danger">';
    foreach($errors as $error){
        $display .= '<li class="text-danger">'.$error.'</li>';
    }
    $display .= '</ul>';
    return $display;
}

// Security function that will only allow people to enter in our character set and the tags will not work for example, bold tags wont make the text bold, it will just return as a regular html text.
function sanitize($dirty) {
    return htmlentities($dirty,ENT_QUOTES,"UTF-8");
}

// Formats the price and outputs it onto the screen
function money($number) {
    return '$'.number_format($number,2);
}

// Function to log the user into the admin page
function login($user_id){
    $_SESSION['SBUser'] = $user_id;
    global $db;
    $date = date("Y-m-d H:i:s");
    $db->query("UPDATE users SET last_login = '$date' WHERE id = '$user_id'");
    $_SESSION['success_flash'] = 'You are now logged in!';
    header('Location: index.php');
}

// FUntion to see if user is logged in 
// ********MUST CHANGE SBUser
function is_logged_in(){
    if(isset($_SESSION['SBUser']) && $_SESSION['SBUser'] > 0){
        return true;
    }
    return false;  
}

// Redirect function
function login_error_redirect($url = 'login.php'){
    $_SESSION['error_flash'] = 'You must be logged in to access that page';
    header('Location: '.$url);
}

// Persmission check
function has_permission($permission = 'admin'){
    global $user_data;
    $permissions = explode(',', $user_data['permissions']);
    if(in_array($permission,$permissions,true)){
    return true;  
}
    return false;
}

// Redirect function
function permission_error_redirect($url = 'login.php'){
    $_SESSION['error_flash'] = 'You do not have permission to access that page';
    header('Location: '.$url);
}

// Formatting dates
function pretty_date($date){
    return date("M d, Y h:i A", strtotime($date));
}

// Getting all of the categories
function get_category($child_id){
    global $db;
    $id = sanitize($child_id);
    $sql = "SELECT p.id AS 'pid', p.category AS 'parent', c.id AS 'cid', c.category AS 'child'
            FROM categories c
            INNER JOIN categories p
            ON c.parent = p.id
            WHERE c.id = '$id'";
    $query = $db->query($sql);
    $category = mysqli_fetch_assoc($query);
    return $category;
} 