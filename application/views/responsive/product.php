<html>
<head>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
   <?=js_asset("jquery.js","admin")."\n";?>
    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
    <link rel="stylesheet" href="/resources/demos/style.css" />
	<title>Product Detail</title>
</head>
<style type="text/css">
#left_pannel{
float:left;
background-color:#3399FF;

width: 400px;
}
#picture {
    background-color: #999999;
    border-radius: 40px 40px 40px 40px;
    height: 180px;
    margin-left: 450px;
    width: 450px;
}
#picture ul{ list-style:none; padding-top: 40px;}
#picture ul li{ list-style:none; display:inline; margin-right:20px; float: left;} 
#picture  a.small, #picture a.small:visited { display:inline; width:100px; height:100px; text-decoration:none; background:#ffffff; top:0; left:0; border:0;}
#picture a img {border:0;}
#picture a.small:hover {text-decoration:none; background-color:#000000; color:#000000;}
#picture a .large {display:block; position:absolute; width:0; height:0; border:0; top:0; left:0;}
#picture a.small:hover .large {display:inline; position:absolute; top: 90px; left:150px; width:200px; height:200px; }
</style>
    <script>
   $(document).ready(function() {
        $( "#accordion" ).accordion();
		 $('#featured_pro').click(function(){		 
		  $.ajax({
						type: "POST",
						  url: "<?=base_url()?>product/product_featured",						 
						  success: function(msg){
						  $('#picture').html('<ul>'+msg+'</ul>');	
						  }
					});	
		 });
		 $('.cat').click(function(){
		  var cat_id = $(this).attr('rel');
		  $.ajax({
						type: "POST",
						  url: "<?=base_url()?>product/product_category",
						  data: {cat_id:cat_id},
						  success: function(msg){
						  $('#picture').html('<ul>'+msg+'</ul>');	
						  }
					});	
		 });
		$('#city_form').submit(function() {
		 var city_id     = $("#city_id_t").val();
		 var retailer_id = $("#retailer_id_t").val();
		 $.ajax({
						type: "POST",
						  url: "<?=base_url()?>product/search_form",
						  data: {city_id:city_id,retailer_id:retailer_id},
						  success: function(msg){
						  $('#picture').html('<ul>'+msg+'</ul>');	
						  }
					});	
		  return false;
		}); 
		$('#newsletter_form').submit(function() {
		 var email = $("#email").val();
		 $.ajax({
						type: "POST",
						  url: "<?=base_url()?>product/newsletter_subscribe",
						  data: {email:email},
						  success: function(msg){
						  	if(msg=='email added')
							{
							 $('#Thankyou').show();
							  $('#Sorry').hide();	
							}
							else
							{
							 $('#Thankyou').hide();									
							 $('#Sorry').show();	
							}
						  }
					});	
		  return false;
		}); 
    });
    </script>
<body>
<div id="wrapper">
<div id="left_pannel">
<div id="accordion">
    <h3>Home</h3>
    <div>
        <p>
        Mauris mauris ante, blandit et, ultrices a, suscipit eget, quam. Integer
        ut neque. Vivamus nisi metus, molestie vel, gravida in, condimentum sit
        amet, nunc. Nam a nibh. Donec suscipit eros. Nam mi. Proin viverra leo ut
        odio. Curabitur malesuada. Vestibulum a velit eu ante scelerisque vulputate.
        </p>
    </div>
    <h3>Categories</h3>
    <div>
        <p>
        
        <?=$categories_listing?>
        </p>
    </div>
    <h3>Fetured Products</h3>
    <div>
        <p>
       		<a href="#" id="featured_pro">Is Featured</a>
        </p>
     </div>
    <h3>Seach By Location and Retailer</h3>
    <div>
        <p>
        <form method="post" id="city_form" name="city_form" action="">
         <br />			
            <select class="styled" name="city_id_t" id="city_id_t">
            	 <option value="0">ALL Locations</option>
                <?=create_ddl($cities,'city_id','city_name')?>					
            </select> 
            <select class="styled" name="retailer_id_t" id="retailer_id_t">
                <option value="0">ALL</option> 
                <?=create_ddl($retailers,'id','username')?>					
            </select>            				
            <span class="note error"></span> </p> 	     
            <input type="submit" value="Search" id="search" class="submit small" name="search">
        </form>
        </p>
        
    </div>
     <h3>Subscribe to NewsLetter</h3>
    <div>
        <p>
        <form method="post" id="newsletter_form" name="newsletter_form" action="">
         <br />			
            <input name="email" type="text" class="required text small" id="email" />			            
            <span class="note error"></span> </p>			         
            <input type="submit" value="Submit" id="add" class="submit small" name="add">
            </form>
        </p>
        
    </div>      
</div>
</div>
<div id="centre_pannel">
<p>Show Product.</p>
 <form method="post" id="form1" name="form1" action="">         
        <label>Search:</label>	       			
        <input name="product_name" type="text" class="required text small" id="product_name" />	     
<input type="submit" value="Search" id="search" class="submit small" name="search">
</form>
<div id="picture"><ul><?=$product_listing?></ul></div>
<div id="Thankyou" style="display:none;">"Thanks For Subscription"</div>
<div id="Sorry" style="display:none;">"Email Address All reday for this user"</div>
</div>
</div>
</body>
</html>