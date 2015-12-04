<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<?php $this->load->view('main/head.html');?>
 	<body>
            	<script type="text/javascript">
   
                
		$(document).ready(function() {
                    
			$('#rotating_banner').before('<div id="banner_nav">').cycle({
				fx : 'scrollDown',
				timeout : 5000,
				pager : '#banner_nav',
                        });
		});

	</script>
		<div class="wrapper">
			<div class="container">
				<header>
					<div class="header">
						<!-- header -->
						<?php $this->load->view('main/header.html')?>
						<div class="rotating_banner_container">
							<div class="banner_left" id="rotating_banner">
								<?php
								$banners = get_homepage_rotating_banners();
								foreach($banners as $banner)
								{
								?>
                                                                <a style="display:block; width:910px; height: 370px; " target="_blank" <?=(isset($banner['url']) && $banner['url'])?'href="'.$banner['url'].'"':'';?> >
                                                                    <img class="banner_imgs" src="<?=base_url('assets/uploads/images/banners/'.$banner['image_name']."_o.".$banner['image_ext']);?>" />
                                                                </a>
                                                                <?php
								}
								?>
							</div><!--banner_left-->
						</div>
						<div class="banner_right">
                                                    <?php
                                                    $banner = get_homepage_right_banner();
                                                    if($banner){
                                                    ?>
                                                    <a target="_blank" <?=(isset($banner['url']) && $banner['url'])?'href="'.$banner['url'].'"':'';?> >
                                                        <img src="<?=base_url('assets/uploads/images/banners/'.$banner['image_name']."_o.".$banner['image_ext']);?>" width="250" height="370" />
                                                    </a>
                                                    <?php 
                                                    }
                                                    ?>
						</div><!--banner_right-->
						<div class="clear"></div><!--clear-->
						<div class="bottom_ad">
						<?php
                                                    $banner = get_sale_of_week_banner();
                                                    if($banner){
                                                    ?>
                                                    <a target="_blank" <?=(isset($banner['url']) && $banner['url'])?'href="'.$banner['url'].'"':'';?> >
                                                        <img src="<?=base_url('assets/uploads/images/banners/'.$banner['image_name']."_o.".$banner['image_ext']);?>" />
                                                    </a>
                                                    <?php 
                                                    }
                                                    ?>
                                                    <div class="clear"></div>
						</div><!--bottom_ad-->
					</div><!--header-->
				</header>
				<div class="clear"></div><!--clear-->
				<div class="main_body">
					<? // =$category?>
					<div class="fasions_cata" id="">
						<div class="slides_container">
							<div class="slide">
								<?php
								$categories = get_homepage_categories();
								$i = 1;
								foreach($categories as $cat){
								$subcats = get_child_categories($cat['cat_id']);
								?>
                                                                <div class="fashion_cata" style="<?=($i==5)?'width:225px;':'';?>">
									<img src="<?=base_url('assets/uploads/images/categories/'.$cat['cat_img'].'_o.'.$cat['cat_ext']);?>" />
									<div class="categories_content">
										<h4><a style="color:#fff;" href="<?=base_url('category/?cat_id='.$cat['cat_id']);?>"> <?php echo $cat['cat_title'];?> </a></h4>
										<ul>
											<?php
											$count = 1;

											if(isset($subcats) && $subcats && count($subcats)){
											foreach($subcats as $subcat){
											?>
											<li>
												<a href="<?=base_url('category/?cat_id='.$subcat['cat_id']);?>"><?php echo $subcat['cat_title'];?></a>
											</li>
											<?php
											if($count ==2){
											break;
											}
											$count++;
											}
											}
											?>
											<li style="background:url(<?=image_asset_url('arrow-red.jpg')?>) left no-repeat;">
												<a style="color:red" href="<?=base_url('category/?cat_id='.$cat['cat_id']);?>"> MORE </a>
											</li>
										</ul>
									</div>
								</div><!--fashion_cata-->
								<?php
								if($i%5==0){
								?> <div class="clear"></div>
							</div>
							<div class="slide">
								<?php
								}
								$i++;
								}
								?> <div class="clear"></div>
							</div>
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
					 $popular_retailers = get_popular_retailers();
					 if(count($popular_retailers)){
					?>
                    <div class="showcase" style="height:200px;">
						<h4>POPULÄRA BUTIKER</h4>
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
                                    <a hrfe="<?=base_url('retailer/index/'.$retailer['uid']);?>">
                                       <img src="<?=base_url('assets/uploads/images/retailer/'.$retailer['logo_image'].'_m.'.$retailer['logo_ext']);?>"/>
                                    </a>
									</div>
                                    <div class="readmore_container">
									<a href="<?=base_url('retailer/index/'.$retailer['uid']);?>"> <img src="<?=image_asset_url('arrow_red.jpg')?>" alt="" style="margin-right: 5px;">Se alla produkter </a>
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
				</div><!--main_body-->
				<!--footer-->
				<?php $this->load->view('main/footer.html')?>
			</div><!--container-->
		</div><!--wrapper-->
	</body>
</html>
