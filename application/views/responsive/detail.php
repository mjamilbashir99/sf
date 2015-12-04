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
                  <span style="float:left;"> Före:
                  <?=makeCurrency($sale['product_price']);?>
                  kr <br>
                  <label style="color:red">Nu:
                    <?=makeCurrency($sale['sale_value']);?>
                    kr</label>
                  </span> <span class="persentage_img">-<?=makeCurrency($sale['api_reduction_percent']);?>%</span> </div>
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
   
                  <span style="float:left;"> Före:
                  <?=makeCurrency($sale['product_price']);?>
                  kr <br>
                  <label style="color:red">Nu:
                    <?=makeCurrency($sale['sale_value']);?>
                    kr</label>
                  </span> <span class="persentage_img">-<?=makeCurrency($sale['api_reduction_percent']);?>%</span></div>
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
                <span style="float:left;"> Före:
                <?=makeCurrency($sale['product_price']);?>
                kr <br>
                <label style="color:red">Nu:
                  <?=makeCurrency($sale['sale_value']);?>
                  kr</label>
                </span> <span class="persentage_img">-<?=makeCurrency($sale['api_reduction_percent']);?>%</span></li>
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