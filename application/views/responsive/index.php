
            	<script type="text/javascript">
   
                
		$(document).ready(function() {
                    
			$('#rotating_banner').before('<div id="banner_nav">').cycle({
				fx : 'scrollDown',
				timeout : 5000,
				pager : '#banner_nav',
                        });
		});
   
	</script>
    </header>
		<div class="wrapper">
			<div class="container">
				
						
							<table border="0" width="1170" id="table22" cellspacing="0" cellpadding="0" height="500">

							<tr>
								<td width="575" class="home_overlay_container">
               
							 
                         <?=image_asset('kvinna.png')?>
                         
                         <div class="homepage_content">
                           
                            <p>
 <a href="<?=base_url('category?cat_id=186&per_page=');?>"><?=image_asset('kvinna_text.png')?></a>             
    </p>
                   </div>

								<td width="20">&nbsp;</td>

								<td width="575"class="home_overlay_container">
                          
               
                   <?=image_asset('man.png')?>
                 <div class="homepage_content">
                <p>
                <a href="<?=base_url('category?cat_id=185&per_page=');?>"><?=image_asset('man_text.jpg')?></a>
                </p>
</div>
							</tr>
<tr>
 <td style="font-size: 10px" height="35" colspan="3">&nbsp;</td>
</tr>
<tr>
 <td style="border-bottom:1px solid #DDDDDD; font-size: 10px" height="25" colspan="3">&nbsp;</td>
 </tr>
<tr>
 <td style="font-size: 10px" height="20" colspan="3">&nbsp;</td>
</tr>

						</table>
					<script> $('.home_overlay_container img').css('opacity', 0.4);  </script>
		     </div><!--header-->
				
				
                
                <!--clear-->
				
                
               	</tr>

				  <tr>
<div class="main_body">
			<td height="25">

						<table border="0" width="1170" id="table23" cellspacing="0" cellpadding="0">

							<tr>

								<td width="376">

								 <a href="<?=base_url();?>"><?=image_asset('banner1.jpg')?></a>
                                <div class="center_home">
                                 <a href="<?=base_url('category?cat_id=202&per_page=');?>"> Skor &gt;</a>
                               </div>
							  <td width="20">&nbsp;</td>

								<td width="376">

								<a href="<?=base_url();?>"><?=image_asset('banner2.jpg')?></a>
                                <div class="center_home">
                                <a href="<?=base_url('category?cat_id=207&per_page=');?>">Sport &gt;</a>
                                </div>

							  <td width="20">&nbsp;</td>

								<td width="376">

								 <a href="<?=base_url();?>"><?=image_asset('banner3.jpg')?></a>
                                 <div class="center_home">
                                 <a href="<?=base_url('category?cat_id=184&per_page=');?>">Kläder &gt;</a> 
                                  </div>
                                

						  </tr>

						</table>
</td>
</tr>
<tr>
 <td style="font-size: 10px" height="20">&nbsp;</td>
</tr>
						</td>
                  </div>
	
	</div><!--fashions_cata-->
					<div class="showcase">
						<h4>POPULÄRA PRODUKTER</h4>
						<div class="clear"></div>
						<div id="popular_sales" class="jcarousel_popular_sales">
							<ul>
								<?php
								$popular_sales = get_popular_sales();
                                                                $i = 1;
								foreach ($popular_sales as $sale){
                                                                    if($i==5){
                                                                        $border = "border:none;";
                                                                        $i=0;
                                                                    }else{
                                                                        $border = "";
                                                                    }
                                                                    $i++;
																	if($sale['product_image']=='')
																	   continue;
								?>
                                                                <li style="<?=$border;?>"  class="pro_slider">
									<a class="image_container" href="<?=base_url()?>product/product_detail/<?=$sale['product_id']?>" > 
                                    <?php
                                        if($sale['from_api']==0)
                                        {
                                           $image_path_original = other_asset_url($sale['product_image'] . '_m.' . $sale['product_ext'], '', 'uploads/images/products');	
                                       		//echo $image_path_original;
									    }
                                        else
                                        {
											
                                        	$image_path_original = str_replace(array('w=440','h=440'),array('w=160','h=160'),$sale['product_image']);
                                        	//echo $image_path_original;
										}
                                    ?>
                                                                            
                                                                            <img align="middle" src="<?php echo $image_path_original;?>" alt=""> 
                                                                  </a>
									<div class="clear"></div>
                                                                        <div class="text_container" style="text-align:left;">
                                                                            <p>
                                                                                    <?=trim_text(strip_tags($sale['product_name']),25);?>
                                                                            </p>
                                                                            <span class="clear"></span>
                                                                            <span style="float:left;"> Före: <?=makeCurrency($sale['product_price']);?> kr
                                                                                        <br>
                                                                                        <label style="color:red">Nu: <?=makeCurrency($sale['sale_price']);?> kr</label> </span>
                                                                                <span class="persentage_img">-<?=makeCurrency($sale['api_reduction_percent']);?>%</span><!--persentage_img-->
                                                                            
                                                                        </div>
								</li><!--pro_slider-->
								<?php
								}
								?>
							</ul>
						</div>
						<div class="clear"></div>
					</div><!--showcase-->
					<div class="clear"></div>
					<?php 
					 $popular_brands = get_popular_brands();
					 if(count($popular_brands)){
					?>
                    
                    <div class="showcase popular_brands" style="height:200px;">
						<h4>MEST POPULÄRA VARUMÄRKEN</h4>
						<div class="clear"></div>
						<div class="arrow_left"></div><!--arrow_left-->
						<div class="companies_logo">
							<ul id="popular_brands" class="jcarousel-skin-tango">
								<?php
								//can give limit if want default is already set
								foreach ($popular_brands as $brand){
								?>
								<li>
									<div class="image_container">
                                       <img src="<?=$brand['image'];?>"/>
									</div>
								</li>
								<?php
								}
								?>
							</ul>
						</div><!--companies_logo-->
						<div class="clear"></div>
					</div><!--showcase-->
	               <div class="clear"></div>
                    <?php }?>
                     <?php 
					 $popular_retailers = get_popular_retailers();
					 if(count($popular_retailers)){
					?>
                    
                    <div class="showcase" style="height:200px;">
						<h4>MEST POPULÄRA BUTIKER</h4>
						<div class="clear"></div>
						<div class="arrow_left"></div><!--arrow_left-->
						<div class="companies_logo">
							<ul id="popular_retailers" class="jcarousel-skin-tango">
								<?php
								//can give limit if want default is already set
								foreach ($popular_retailers as $retailer){
								?>
								<li>
									<div class="image_container">
                                   
                                       <img src="<?=$retailer['image'];?>"/>
                                   
									</div>
                                    <div class="readmore_container" style="display:none">
									<a href="<?=$retailer['link'];?>"> <img src="<?=image_asset_url('arrow_red.jpg')?>" alt="" style="margin-right: 5px;">Se alla produkter </a>
                                    </div>
								</li>
								<?php
								}
								?>
							</ul>
						</div><!--companies_logo-->
						<div class="clear"></div>
					</div><!--showcase-->
	               <div class="clear"></div>
                    <?php }?>
		
           <script>
		  // $(".home_overlay").mouseover(function(){$(this).hide("slow",'linear')});
		   //$(".home_overlay_container").mouseleave(function(){$(".home_overlay").show("slow",'linear')});
		
  
// when hover over the selected image change the opacity to 1  
$('.home_overlay_container').hover(  
   function(){  
      $(this).find('img').stop().fadeTo('slow', 1);  
   },  
   function(){  
      $(this).find('img').stop().fadeTo('slow', 0.4);  
   }); 
		   
		   </script>                                     
				
