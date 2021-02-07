</div>
    <footer class="text-center col-md-12" id="footer">&copy; Copyright 2017 Hung Huynh</footer>
   
    
    <!-- Allows logo to stay on screen until you can't see it anymore -->
    <script>
        $(window).scroll(function () {
            var vscroll = $(this).scrollTop();
            $('#logo-text').css({
                "transform": "translate(0px, " + vscroll / 2 + "px)"
            });
        });
        
        function detailsmodal(id){
            var data = {"id":id};
            jQuery.ajax({
                url : '/eCommerce/includes/detailsmodal.php',
                method : "POST",
                data : data,
                success : function(data){
                    jQuery('body').append(data);
                    jQuery('#details-modal').modal('toggle');
                },
                Error : function(){
                    alert("Something went wrong!");
                }
            });
        }
        
        function update_cart(mode,edit_id,edit_size){
            var data = {"mode":mode, "edit_id":edit_id, "edit_size":edit_size}
            jQuery.ajax({
                url : '/eCommerce/admin/parsers/update_cart.php',
                method : "POST",
                data : data,
                success : function(){
                      location.reload();
                },
                error : function(){
                    alert("Something went wrong!");
                }
            });
        }
       
        
        function add_to_cart(){
            jQuery('#modal_errors').html("");
            var size = jQuery('#size').val();
            var quantity = jQuery('#quantity').val();
            // Makes check to see if user tries to add more than there is available items
            var available = jQuery('#available').val();
            var error = '';
            var data = jQuery('#add_product_form').serialize();
            if(size == ''|| quantity == '' || quantity == 0){
                error += '<p class="text-danger text-center">You must choose a size and quantity.</p>';
                jQuery('#modal_errors').html(error);
                return;
            }else if(quantity > available){
                error += '<p class="text-danger text-center">There are only '+available+' available in that size.</p>';
                jQuery('#modal_errors').html(error);
                return;
            }else{
                jQuery.ajax({
                    url: '/eCommerce/admin/parsers/add_cart.php',
                    method: 'post',
                    data: data,
                    success: function(){
                        location.reload();
                    },
                    error: function(){alert("Something went wrong.");}
                });
            }
        }
    </script>
    </body>

    </html>