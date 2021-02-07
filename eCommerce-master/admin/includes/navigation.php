<!-- Top Nav Bar -->
       <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button> <a class="navbar-brand" href="/eCommerce/admin/index.php">Luminous Clothing Admin</a> </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                   <!-- Menu Items -->
                   <li><a href="brands.php">Brands</a></li>
                   <li><a href="categories.php">Categories</a></li>
                   <li><a href="products.php">Products</a></li>
                   <?php if(has_permission('admin')): ?>
                   <li><a href="users.php">Users</a></li>
                   <?php endif; ?>
                   <li class="dropdown">
                       <a href="#" class="dropdown-toggle" data-toggle="dropdown">Hello <?=$user_data['first'];?>!
                       <span class="caret"></span>
                       </a>
                       <ul class="dropdown-menu" role="menu">
                           <li><a href="change_password.php">Change Password</a></li>
                           <li><a href="logout.php">Log Out</a></li>
                         </ul>
                   </li>
                  <!--  <li class="dropdown"> 
                       <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $parent['category']; ?><span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                    
                            <li><a href="#"></a></li>
                            
                        </ul>
                    </li> -->
                 </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </nav>