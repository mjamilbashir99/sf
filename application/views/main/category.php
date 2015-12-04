<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php $this->load->view('main/head.html'); ?>
	<body>
		<div class="wrapper">
			<div class="container">
				<header>
					<div class="header">
						<?php $this->load->view('main/header.html')?>
					</div><!--header-->
				</header>
				<div class="clear"></div><!--clear-->
				<div class="main_body">
					<div class="float_left">
						<?php   
                                                $this->load->view('main/left_div.php');
                                                $per_page = $this->input->get("per_page");
                                                $cat_id = $this->input->get("cat_id");
                                                $color = $this->input->get("color");
                                                $brand = $this->input->get("brand");
                                                if(isset($category_name)){
                                                    $cat_name = end(explode(">",$category_name));
                                                }
                                                ?>						
					</div>
					<div class="float_right" style="margin-left: 10px;">
						<div class="catageries"> 
							<div class="search" style="background: none; margin-top: -0px; display:none">
                                                            <?php if($cat_id){ ?>
								<div class="search_bg" style="background-image: none;width:100%; height: 70px; background-color: #fff; border: solid 1px #999999;">
                                                                    <form method="get" action="<?=base_url('category');?>" id="filter_form">
                                                                        <input type="hidden" name="cat_id" value="<?=$cat_id;?>" />
                                                                        <input type="hidden" name="per_page" value="<?=$per_page;?>" />
									<div class="dropdown_search cat_filters" style="width:100%">
										<p style="float: left; line-height:70px; margin-left: 10px; margin-right: 10px;">
											<?=$cat_name;?>
										</p>
                                                                            <div class="category_filters_containers">
                                                                            <?php
                                                                            $filters = explode(",",$category_detail['filters']);
                                                                            
                                                                            foreach($filters as $filter){
                                                                                if(!$filter){
                                                                                    continue;
                                                                                }
                                                                                if($filter == 'product_price'){
                                                                                    $price_range = get_price_range($cat_id);
                                                                                    if($price_range['min_price'] == $price_range['max_price']){
                                                                                        $price_range['min_price'] = 0;
                                                                                    }
                                                                                    
                                                                                    if(isset($query_params['min_price']) && $query_params['min_price']){
                                                                                        $min_value = intval($query_params['min_price']);
                                                                                    }else{
                                                                                        $min_value = intval($price_range['min_price']);
                                                                                    }
                                                                                    
                                                                                    if(isset($query_params['max_price']) && $query_params['max_price']){
                                                                                        $max_value = intval($query_params['max_price']);
                                                                                    }else{
                                                                                        $max_value = intval($price_range['max_price']);
                                                                                    }
                                                                                ?>
                                                                                <script type="text/javascript">
                                                                                $(function() {
                                                                                    $( "#slider_range" ).slider({
                                                                                        range: true,
                                                                                        min: <?=$price_range['min_price']?>,
                                                                                        max: <?=$price_range['max_price']?>,
                                                                                        values: [ <?=$min_value?>, <?=$max_value?> ],
                                                                                        slide: function( event, ui ) {
                                                                                            $("#min_price").val(ui.values[ 0 ]);
                                                                                            $("#max_price").val(ui.values[ 1 ]);
                                                                                            $("#price_range_output").html("Kr "+ui.values[0]+" - Kr "+ui.values[ 1 ]);
                                                                                        },
                                                                                        stop: function(){
                                                                                            $('#filter_form').submit();
                                                                                        }
                                                                                     });
                                                                                });
                                                                                    </script>
                                                                                <div class="filter_container no_right_margin" style="width:310px;">
                                                                                    <div class="title" style="width:298px"><label>PRIS</label></div>
                                                                                    <div class="clear"></div>
                                                                                    <div class="border left" style="margin:0; margin-right:8px;">
                                                                                            <input class="filter_input" style="margin-left:0;" type="text" value="<?=$min_value?>" id="min_price" name="min_price"  maxlength="5"><span>&nbsp;kr</span>
                                                                                        </div>
                                                                                    <div id="slider_range"></div>
                                                                                    <div class="slider_input">
                                                                                        <div class="border left" style="margin:0; margin-left:7px;">
                                                                                            <input class="filter_input" type="text" value="<?=$max_value?>" id="max_price" name="max_price"  maxlength="5"><span>&nbsp;kr</span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <?php
                                                                                }elseif($filter=='color'){
                                                                                    $filter_field_data = get_filter_field_data($filter,$cat_id);
                                                                                    $color_selected = 0;
                                                                                    if(isset($query_params[$filter]) && $query_params[$filter]){
                                                                                       $color_selected = 1; 
                                                                                    }
                                                                                    ?>
                                                                                   <div class="filter_container" >
                                                                                       <input type="hidden" name="<?=$filter;?>" id="color_value" value="<?=($color_selected)?$query_params[$filter]:'';?>" />
                                                                                       <div class="title">
                                                                                            <label>FÄRG</label>
                                                                                       </div>
                                                                                       <div class="clear"></div>
                                                                                       <div>
                                                                                           <a href="" id="selected_color_box" style="<?=($color_selected)?'display:block; float:left; background-color:'.$query_params[$filter]:'';?>">
                                                                                               
                                                                                           </a>
                                                                                           
                                                                                           <a id="remove_color_filter" style="<?=($color_selected)?'display:block;':'display:none;';?> float:right; cursor: pointer;">Bort färgfilter</a>
                                                                                           
                                                                                           <div id="choose_color_box"  style="position:relative; <?=($color_selected)?'display:none;':'';?>">
                                                                                               <select class="filters" style="width:137px;">
                                                                                                   <option>Välj färg</option>
                                                                                               </select>
                                                                                               <div id="color_picker_choose" style="width:137px; height:18px; top:0; position:absolute; z-index: 10; background:none;"></div>
                                                                                           </div>
                                                                                           <span style="width:1px;height:1px;position:relative;">
                                                                                               <div id="color_picker" style="background-color:#EEEEEE; visibility:hidden; border:solid 1px #999999; border-top:none; z-index:99;  width:125px; padding: 5px; position:absolute;">
                                                                                                   <ul class="color_filter">
                                                                                                   <?php
                                                                                                   foreach($filter_field_data as $data){
                                                                                                   ?>
                                                                                                   <li>
                                                                                                    <a class="color_box" href="<?=$data['value'];?>" style="background-color:<?=$data['value']?>;">
                                                                                                        <span></span>
                                                                                                    </a>
                                                                                                   </li>
                                                                                                   <?php
                                                                                                   }
                                                                                                   ?>
                                                                                                   </ul>
                                                                                               </div>
                                                                                           </span>
                                                                                       </div>
                                                                                   </div> 
                                                                                <?php
                                                                                }else{
                                                                                    $filter_field_data = get_filter_field_data($filter,$cat_id);
                                                                                    if($filter=='brand'){
                                                                                        $filter_name = 'varumärke';
                                                                                    }
                                                                            ?>
                                                                            <div class="filter_container">
                                                                                <div class="title" style="text-transform: uppercase;">
                                                                                    <label><?=str_replace("_"," ",$filter_name);?></label>
                                                                                </div>
                                                                                <div>
                                                                                <select class="filters other_filters" name="<?=$filter;?>">
                                                                                    <option value="">
                                                                                        Välj <?=str_replace("_"," ",$filter_name);?> <?=image_asset('arrow.jpg')?>
                                                                                    </option>
                                                                                    <?php
                                                                                    foreach($filter_field_data as $data){
                                                                                        ?>
                                                                                        <option value="<?=$data['value'];?>"><?=$data['value'];?></option>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                                </div>
                                                                            </div>
                                                                            <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                            </div>
									</div>
                                                                    </form>
                                                                    
								</div>
                                                            <?php } ?>
								<div class="clear"></div>
							</div>
							<div class="clear"></div>
                           <div id="product_cat"><?=$products?></div>
							
						</div><!--catageries-->
						<div class="clear"></div>
                                                <?php 
                                                if(isset($total_records) && $total_records){
                                                ?>
                                                <div class="total_products">Antal produkter: <b><?=$total_records;?></b></div>
                                                
                      <div class="pages_links"><?php echo $pagination?></div>
						<!--pages_links-->
                                                <?php
                                                }
                                                ?>
					</div><!--float_right>
					<div class="clear"></div>
					</div><!--main_body-->
					<div class="clear"></div>
					<!--footer-->
					<?php $this->load->view('main/footer.html')?>
				</div><!--container-->
			</div><!--wrapper-->
	</body>
</html>
