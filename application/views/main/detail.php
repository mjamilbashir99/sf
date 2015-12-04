<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php $this->load->view('main/head.html'); ?>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
		$(document).ready(function() {
			$(".group1").colorbox({
				rel : 'group1',
				width : '800px',
				height : '600px',
				scalePhotos : true,
				slideshow : false
			});
            $('.buylink').click(function(){
            	$.post("<?=base_url()?>product/updatebuycount/", {pid:$(this).attr('rel')},function(){
            	});
            });            
		});
		var geocoder;
		var map;
		var address = '113 j1 wapda town';
		function initialize() {
			var address = $('#map_canvas').attr('rel');
			geocoder = new google.maps.Geocoder();
			var latlng = new google.maps.LatLng(-34.397, 150.644);
			var myOptions = {
				zoom : 8,
				center : latlng,
				mapTypeControl : true,
				mapTypeControlOptions : {
					style : google.maps.MapTypeControlStyle.DROPDOWN_MENU
				},
				navigationControl : true,
				mapTypeId : google.maps.MapTypeId.ROADMAP
			};
			map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
			if(geocoder) {
				geocoder.geocode({
					'address' : address
				}, function(results, status) {
					if(status == google.maps.GeocoderStatus.OK) {
						if(status != google.maps.GeocoderStatus.ZERO_RESULTS) {
							map.setCenter(results[0].geometry.location);

							var infowindow = new google.maps.InfoWindow({
								content : '<b>' + address + '</b>',
								size : new google.maps.Size(150, 50)
							});

							var marker = new google.maps.Marker({
								position : results[0].geometry.location,
								map : map,
								title : address
							});
							google.maps.event.addListener(marker, 'click', function() {
								infowindow.open(map, marker);
							});
						} else {
							alert("No results found");
						}
					} else {
						alert("Geocode was not successful for the following reason: " + status);
					}
				});
			}
		}
	</script>
<body onload="initialize()">
<div class="wrapper">
  <div class="container">
    <header>
      <div class="header">
        <?php $this->load->view('main/header.html')?>
      </div>
      <!--header--> 
    </header>
    <div class="clear"></div>
    <!--clear-->
    <div class="main_body">
      <div class="float_left">
        <?php $this->load->view('main/left_div.php')?>
      </div>
      <div class="float_right">
        <?=$product_detail?>
        <div class="detail_showcase">
          <h4>FLER
            <?=getcategroyname($cat_id)?>
          </h4>
          <div class="clear"></div>
          <div id="more_from_category" class="jcarousel_detailpage">
            <ul>
              <?php
                                                                    $popular_sales = get_more_cat_pro($cat_id, $product_id);
                                                                    foreach ($popular_sales as $sale){
                                                                    ?>
              <li class="pro_slider"> <a class="image_container" href="<?=base_url()?>product/product_detail/<?=$sale['pro_id']?>" >
                <?php
        if($sale['from_api']==0)
        {
      	 	 $image_path_original = other_asset_url($sale['product_image'] . '_m.' . $sale['product_ext'], '', 'uploads/images/products');	
        }
        else
        {
       		$image_path_original = str_replace('160','440',$sale['product_image']);
        }
        
        ?>
                <img src="<?=$image_path_original?>"  alt=""> </a>
                <div class="clear"></div>
                <div style="width: 158px; padding-left:15px;">
                  <p>
                    <?=trim_text(strip_tags($sale['product_name']),25);?>
                  </p>
                  <span class="clear"></span>
                  <?php
                                                                            if($sale['sale_type_id']==5)
                                                                            {
                                                                                $new_value      = $sale['sale_value'];
                                                                                $off_percentage = $sale['api_reduction_percent'];
                                                                            }
                                                                            else
                                                                            {
                                                                                $new_value      = $sale['sale_price'];
                                                                                $off_percentage = 100-($sale['sale_price']*100/$sale['product_price']);
                                                                            }
                                                                            ?>
                  <span style="float:left;"> Före:
                  <?=makeCurrency($sale['product_price']);?>
                  kr <br>
                  <label style="color:red">Nu:
                    <?=makeCurrency($new_value);?>
                    kr</label>
                  </span> <span class="persentage_img">-<?=makeCurrency($off_percentage);?>%</span> </div>
              </li>
              <!--pro_slider-->
              <?php
                                                                    }
                                                                    ?>
            </ul>
          </div>
          <div class="clear"></div>
        </div>
        <!--showcase-->
        <?php if($store_name!='') {
                                                  $popular_sales = get_more_retailer_pro($store_name,$product_id);
                                                 if(count($popular_sales)){ 
                                            ?>
        <div class="detail_showcase">
          <h4>FLER PRODUKTER FRÅN
            <?=strtoupper($store_name)?>
          </h4>
          <div class="clear"></div>
          <div id="more_from_retailer" class="jcarousel_detailpage">
            <ul>
              <?php
                                                                    foreach ($popular_sales as $sale){
                                                                    ?>
              <li class="pro_slider"> <a class="image_container" href="<?=base_url()?>product/product_detail/<?=$sale['pro_id']?>" >
                <?php
        if($sale['from_api']==0)
        {
      	 	 $image_path_original = other_asset_url($sale['product_image'] . '_m.' . $sale['product_ext'], '', 'uploads/images/products');	
        }
        else
        {
       		$image_path_original = str_replace('160','440',$sale['product_image']);
        }
        
        ?>
                <img src="<?=$image_path_original?>" alt=""> </a>
                <div class="clear"></div>
                <div style="width: 158px; padding-left:15px;">
                  <p>
                    <?=trim_text(strip_tags($sale['product_name']),25);?>
                  </p>
                  <span class="clear"></span>
                  <?php
                                                                           if($sale['sale_type_id']==5)
                                                                            {
                                                                                $new_value      = $sale['sale_value'];
                                                                                $off_percentage = $sale['api_reduction_percent'];
                                                                            }
                                                                            else
                                                                            {
                                                                                $new_value      = $sale['sale_price'];
                                                                                $off_percentage = 100-($sale['sale_price']*100/$sale['product_price']);
                                                                            }
                                                                            ?>
                  <span style="float:left;"> Före:
                  <?=makeCurrency($sale['product_price']);?>
                  kr <br>
                  <label style="color:red">Nu:
                    <?=makeCurrency($new_value);?>
                    kr</label>
                  </span> <span class="persentage_img">-
                  <?=makeCurrency($off_percentage);?>
                  %</span> </div>
              </li>
              <!--pro_slider-->
              <?php
                                                                    }
                                                                    ?>
            </ul>
          </div>
          <div class="clear"></div>
        </div>
        <?php }}?>
        <div class="detail_showcase">
          <h4>FLER
            <?=getcategroyname($cat_id)?>
            UNDER
            <?=makeCurrency($detail['sale_price'])?>
            KR</h4>
          <div class="clear"></div>
          <div class="jcarousel_detailpage detail_sales" id="more_on_detailpage"><!--id=more_on_detailpage -->
            <ul>
              <?php
                                                                    $popular_sales = get_pro_by_price(0, $detail['sale_price'],$cat_id);
                                                                    if($popular_sales){
                                                                            foreach ($popular_sales as $sale){
                                                                            ?>
              <li class="pro_slider"> <a class="image_container" href="<?=base_url()?>product/product_detail/<?=$sale['pro_id']?>" >
                <?php
        if($sale['from_api']==0)
        {
      	 	 $image_path_original = other_asset_url($sale['product_image'] . '_m.' . $sale['product_ext'], '', 'uploads/images/products');	
        }
        else
        {
       		$image_path_original = str_replace('160','440',$sale['product_image']);
        }
        
        ?>
                <img src="<?=$image_path_original?>"  alt=""> </a>
                <div class="clear"></div>
                <p>
                  <?=trim_text(strip_tags($sale['product_name']),25);?>
                </p>
                <span class="clear"></span>
                <?php
																					if($sale['sale_type_id']==5)
                                                                                    {
                                                                             	       $new_value      = $sale['sale_value'];
                                                                                 	   $off_percentage = $sale['api_reduction_percent'];
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                  	  $new_value      = $sale['sale_price'];
                                                                                  	  $off_percentage = 100-($sale['sale_price']*100/$sale['product_price']);
                                                                                    }
                                                                                    ?>
                <span style="float:left;"> Före:
                <?=makeCurrency($sale['product_price']);?>
                kr <br>
                <label style="color:red">Nu:
                  <?=makeCurrency($new_value);?>
                  kr</label>
                </span> <span class="persentage_img">-
                <?=makeCurrency($off_percentage);?>
                %</span> </li>
              <!--pro_slider-->
              <?php
                                                                            }
                                                                    }
                                                                    else{
                                                                            echo '<li>No product available</li>';
                                                                    }
                                                                    ?>
            </ul>
          </div>
          <div class="clear"></div>
        </div>
        <!--showcase-->
        <div class="clear"></div>
      </div>
    </div>
    <!--main_body-->
    <div class="clear"></div>
    <!--footer-->
    <?php $this->load->view('main/footer.html')?>
  </div>
  <!--container--> 
</div>
<!--wrapper-->
</body>
</html>
