<?php 
    require_once $_SERVER['DOCUMENT_ROOT'].'/eCommerce/core/init.php';
    $product_id = sanitize($_POST['product_id']);
    $size = sanitize($_POST['size']);
    $available = sanitize($_POST['available']);
    $quantity = sanitize($_POST['quantity']);
    $item = array();
    $item[] = array(
    'id'       => $product_id,
    'size'     => $size,
    'quantity' => $quantity
     );
    
    // Set domain to http host if it is not local host
    $domain = ($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false;
    $query = $db->query("SELECT * FROM Products WHERE id = '{$product_id}'");
    $product = mysqli_fetch_assoc($query);
    $_SESSION['success_flash'] = $product['title']. ' was added to your cart.';
    
    // Check to see if the cart cookie exists
    if($cart_id != ''){
        $cartQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
        $cart = mysqli_fetch_assoc($cartQ);
        $previous_items = json_decode($cart['items'], true);
        $item_match = 0;
        $new_items = array();
        foreach($previous_items as $pitem){
            if($item[0]['id'] == $pitem['id'] && $item['9']['size']== $pitem['size']){
                $pitem['quantity'] = $pitem['quantity'] + $item[0]['quantity'];
                // Check to see if they try to get more than avialable, then reset to available amount
                if($pitem['quantity'] > $available){
                    $pitem['quantity'] = $available;
                }
                $item_match = 1;
            }
            $new_items[] = $pitem;
        }
        // If size or id does not match then merge with previous items
        if($item_match != 1){
            $new_items = array_merge($item,$previous_items);
        }
        $items_json = json_encode($new_items);
        $cart_expire = date("Y-m-d H:i:s", strtotime("+30 days"));
        $db->query("UPDATE cart SET items = '{$items_json}', expire_date = '{$cart_exipre}' WHERE id = '{$cart_id}'");
        setcookie(CART_COOKIE,'',1,"/",$domain,false);
        setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,'/',$domain,false); 
    }else{
        // Add the cart to the database and set cookie
        $items_json = json_encode($item);
        $cart_expire = date("Y-m-d H:i:s",strtotime("+30 days"));
        // Insert cart items into the database
        $db->query("INSERT INTO cart (items,expire_date) VALUES ('{$items_json}','{$cart_expire}')");
        $cart_id = $db->insert_id;
        setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,'/',$domain.false);
    }  
?>