<?php 
    define('BASEURL', $_SERVER['DOCUMENT_ROOT'].'/eCommerce/');
    define('CART_COOKIE', 'SBwi72UCKlqiqzz2');
    define('CART_COOKIE_EXPIRE', time() + (86400 *30));
    define('TAXRATE',0.051);  // Massachusets tax rate, set to 0 if you aren't charging tax.
    define('CURRENCY', 'usd');
    define('CHECKOUTMODE', 'TEST'); // Change TEST to LIVE when you are ready to go live
    
    if(CHECKOUTMODE == 'TEST'){
        define('STRIPE_PRIVATE','sk_test_oZsG4T7DVBfDVcvlGq8ZlxRH');
        define('STRIPE_PUBLIC','pk_test_uE29lmOAGyFmtoehWeqYqBJQ');
    }

    if(CHECKOUTMODE == 'LIVE'){
        define('STRIPE_PRIVATE','sk_live_1Ac4uXrIs5ChoDy425TG4Gng');
        define('STRIPE_PUBLIC','pk_live_qsn8vf6nYLg1LflzcVuWAAeM');
    }