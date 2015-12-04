	<?php 
	  $cat_id = $this->input->get("cat_id");
	 if($cat_id!='')
	 {
	?>
	<?=css_asset('bootstrap/bootstrap.min.css')?>
    <?=css_asset('bootstrap/jquery-ui.css')?>
    <?=css_asset('bootstrap/bootstrap-theme.min.css')?>
    
 
	<?=js_asset('bootstrap/bootstrap.min.js');?>
    <?=js_asset('bootstrap/jquery-ui.min.js');?>
    <?=js_asset('bootstrap/enscroll-0.6.0.min.js');?>
           
    <style>
        .accordion-group {
            margin-bottom: 2px;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
        }
        .accordion-toggle{
            border-bottom-width: 0;
            border: 1px solid #e5e5e5;
            border-bottom: 0px;
            z-index: 92;
            position: absolute;
            background: white;
            padding: 1% 1% 3% 10%;
            background-color: #ececec;
            background-image: -webkit-gradient(linear,left top,left bottom,color-stop(0%,#e7e7e7),color-stop(100% #ddd));
            background-image: -webkit-linear-gradient(top,#e7e7e7 0,#ddd 100%);
            background-image: -moz-linear-gradient(top,#e7e7e7 0,#ddd 100%);
            background-image: -ms-linear-gradient(top,#e7e7e7 0,#ddd 100%);
            background-image: -o-linear-gradient(top,#e7e7e7 0,#ddd 100%);
            background-image: linear-gradient(top,#e7e7e7 0,#ddd 100%);
            background-clip: padding-box;
        }
        .accordion-toggle.collapsed{

            border: 1px solid #bebebe;
        }
        .accordion-body{
            margin-top: 30px;
            padding-top: 10px;
            border: 1px solid #e5e5e5;
        }
        .navList{
            list-style: none;
            padding-left: 0px;
            height: 130px;
            
            margin-top: 5px;
        }


        .navList::-webkit-scrollbar-track
        {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
            background-color: #F5F5F5;
        }

        .navList::-webkit-scrollbar
        {
            width: 5px;
            background-color: #F5F5F5;
        }

        .navList::-webkit-scrollbar-thumb
        {
            background-color: #000000;
            border: 2px solid #555555;
        }

        .navList::-webkit-scrollbar-thumb {
            background-color: #000000;
        }
        .submit{
            margin-bottom: 3px;
        }

        /** Price **/
        .filterBox {
            border: 1px solid #bebebe;
            width: 198px;
            -webkit-transform: scale(1);
            z-index: 1;
            background: #fff;
            position: relative;
			margin-left:9px;
        }
        .noScrollBar {
            display: block;
            margin: 10px;
        }
        #slider-range  {
            height: .5em;
            background : #f3f3f3;
            border: none;
            margin: 15px 5px 15px 5px;
        }
        #slider-range .ui-widget-header {
            display: block;
            background: #a8a8a8;
        }
        #slider-range .ui-state-default {
            height: 16px;
            width: 16px;
            border-radius: 16px 16px 16px 16px;
            background: #404040;
            border: none;
            outline: none;
        }
        .sliderInput {
            display: block;
            overflow: hidden;
            margin-top: 10px;
			float:left;
        }
        .sliderInput span {
            padding: 0 5px;
        }
        .sliderInput .plugin_border{
            float: left;
            position: relative;
            width: 69px;
            border: 1px solid #eaeaea;
        }
        .plugin_border .inputText {
            width: 52px;
            border: 0;
            text-align: center;
            font-size: 11px;
            padding: 4px 0;
        }
        .plugin_border span {
            line-height: 12px;
            margin-top: 5px;
            display: inline-block;
            font-size: 11px;
            position: absolute;
            z-index: 12;
            right: 2px;
        }
        .noJsHidden {
            display: block;
            clear: both;
            overflow: hidden;
        }
        .sliderInput .noJsHidden{
            margin:10px 0;
        }
        .noJsHidden input[type="checkbox"] {
            float: left;
        }
        .noJsHidden label{
            float: left;
            font-size: 12px;
            color: #b0b0b0;
            font-weight: normal;
            margin: 2px 5px
        }
        .btn-primary {
            width: 100%;
        }
        .fHead{
            display: table;
            padding: 5px 10px;
            font-size: 12px;
            overflow: hidden;
            background-color: #ececec;
            background-image: -webkit-gradient(linear,left top,left bottom,color-stop(0%,#e7e7e7),color-stop(100% #ddd));
            background-image: -webkit-linear-gradient(top,#e7e7e7 0,#ddd 100%);
            background-image: -moz-linear-gradient(top,#e7e7e7 0,#ddd 100%);
            background-image: -ms-linear-gradient(top,#e7e7e7 0,#ddd 100%);
            background-image: -o-linear-gradient(top,#e7e7e7 0,#ddd 100%);
            background-image: linear-gradient(top,#e7e7e7 0,#ddd 100%);
            background-clip: padding-box;
            width: 30px;
            position: relative;
            z-index: 2;
            min-height: 32px;
            border: 1px solid #bebebe;;
            border-bottom: 0;
            background: #fff;
            width: 100px;
            bottom: -1px;
        }
        .fHead.collapsed {
			
            display: table;
            padding: 1px 15px;
            font-size: 16px;
            overflow: hidden;
            background-color: #ececec;
            background-image: -webkit-gradient(linear,left top,left bottom,color-stop(0%,#e7e7e7),color-stop(100% #ddd));
            background-image: -webkit-linear-gradient(top,#e7e7e7 0,#ddd 100%);
            background-image: -moz-linear-gradient(top,#e7e7e7 0,#ddd 100%);
            background-image: -ms-linear-gradient(top,#e7e7e7 0,#ddd 100%);
            background-image: -o-linear-gradient(top,#e7e7e7 0,#ddd 100%);
            background-image: linear-gradient(top,#e7e7e7 0,#ddd 100%);
            background-clip: padding-box;
            width: 100px;
            min-height: 32px;
        }
        .accordion-toggle:after {
            font-family: 'Glyphicons Halflings';  
            content: "\e113";
            float: right;
            color: grey;
			margin-top:7px;
        }
        .accordion-toggle.collapsed:after {
            content: "\e114";
        }
        #collapseOne{
            width: 184px;
            margin: 0;
		 
        }
		#slider_body{
           
        }
        .category-selector{
            width:100px;
            height:300px;
			margin-left:-17px;
        }
      .noScrollBar  .plugin_search{
            width: 100%;
        }
        .search-icon{
            position: absolute;
            top: 28px;
            right: 18px;
        }
        #filter_items,#filter_price{
            list-style:none;.
        }
        #filter_items li:after,#filter_price li:after{
            /* font-family: 'Glyphicons Halflings';  */
            font-family: 'Glyphicons Halflings';
            content: "\e014";
            color: grey;
            text-align: center;
            font-size: 13px;
            vertical-align: middle;
            width: 10px;
        }
        #filter_items li,#filter_price li{
            border:1px solid #bebebe;
            width:auto;
            display: inline;
            padding:3px;
            cursor:pointer;
        }

        #filter_items li:hover,#filter_price li:hover{
            background-color: #f3f3f3;
        }
        #filter_items li a,#filter_price li a{
            text-decoration: none;
            color: #404040;
        }
        #filter_items .value,#filter_price .value{
            padding-right: 5px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 11px;
            max-width: 116px;
            vertical-align: middle;
        }
		.category_header
		{
			margin-left: 10px;
			margin-bottom:10px;
			margin-top:38px;
			float: left;
			background-color:#CCC;
			width: 894px;
			height:35px;
		}
 .category_header_bg
		{
			margin-left: 10px;
			float: left;
			width: 894px;
			margin:9px;
		}
		  .track3:hover,
        .track3.dragging {
            background: #d9d9d9; 
            background: rgba(0, 0, 0, 0.15);
        }

        .handle3 {
            width: 7px;
            right: 0;
            background: #999;
            background: rgba(0, 0, 0, 0.4);
            border-radius: 7px;
            -webkit-transition: width 250ms;
            transition: width 250ms;
        }

        .track3:hover .handle3,
        .track3.dragging .handle3 {
            width: 10px;
        }
		.float_left
		{
			margin-top:38px;
		}
		#box
		{
			line-height:24px;
			padding-left:2px;
		}
		.fLabel{
			line-height:30px;
			}
    </style> 
<?php }
$per_page = $this->input->get("per_page");
$get_min_price=0;
$get_min_price = $this->input->get("min_price");
$get_max_price=0;
$get_max_price = $this->input->get("max_price");
$get_brand='';
$get_brand = $this->input->get("brand");

if($get_brand!='')
	$selected_brands = explode(".",$get_brand);
else
	$selected_brands = array();

$brands  = get_brands($cat_id); 

$price_ranges = array();
$price_ranges = get_price_range_filters($cat_id);
if(isset($category_name))
{
$cat_name = end(explode(">",$category_name));
}
?>
    <?php if($cat_id!=''){?>
  
      <div class="category_header">
     
      <h4> &nbsp;<b><?php echo getcategroyname($cat_id);?></b>  (<?=$total_records;?> artiklar)</h4>
      </div>
      <?php if(count($selected_brands)>=1){?>
          <div class="category_header_bg">
              <ul id="filter_items">&nbsp; Dina val:
                    <?php foreach($selected_brands as $sel_brand){?>
                    <li><a><span class="value"><?php echo str_replace(array("-","_"),array(" ","&"),$sel_brand)?></span></a></li>
                    <?php }?>
              </ul>
      </div>
      <?php }?>
       <?php if($get_max_price>1){?>
          <div class="category_header_bg">
              <ul id="filter_price">&nbsp; Pris Range:
                  <li><a><span class="value"><?php echo $get_min_price." kr - ".$get_max_price." kr"?></span></a></li>
              </ul>
      </div>
      <?php }?>
      <div class="" style="background-image:none; margin-left:10px; height: 36px; background-color: #fff; float:left;  width:893px;">  
      <div class="category_filters_containers" style="float:left">
        <?php  if(count($brands )>=1){ ?>
         <div class="filter_container" style="width:110px;">
      <div class='category-selector'>
                    <div class="js-fToggle fHead closed accordion-toggle collapsed" data-toggle="collapse" data-target='#collapseOne'>
                    <span class="fLabel" title="Varumärke">Varumärke</span>
                    </div>
                    <div id="collapseOne" class="accordion-collapse accordion-body filterBox collapse">
                        <div class="noScrollBar">
                              
                                <input placeholder="sök..." class="plugin_search" id="box" type="text" aria-describedby="sizing-addon1" /> 
                               <span class="glyphicon glyphicon-search search-icon"></span>
               <div  class="wrapper-scroll" tabindex="0" style="width: 156px; padding-right: 10px; padding-top:5px;outline: medium none; overflow: hidden;">
                                <ul class="navList">
                                <?php
								foreach  ($brands as $brand){
								?>
                                    <li class="noJsHidden">
                               <input type="checkbox" name="search_filter" value="<?php echo str_replace(array(" ","&"),array("-","_"),$brand['brand']);?>" <?php if(in_array($brand['brand'],$selected_brands)) echo 'checked="checked"' ?>>
                                    <label><?php echo $brand['brand']?></label>
                                    </li>
                               <?php }?>                                 
                                </ul></div>
                                 
                             <input type="button" class='submit btn btn-primary' id="btn_brands" value='Stäng' <?php if(count($selected_brands)==0) echo 'disabled' ?>/>
                             
                    
                        </div>
                    </div>
                </div>
                </div>
      <?php } ?>
	  <?php if (!(is_null($price_ranges['min_price']) || is_null($price_ranges['max_price'])))
	  { ?>
       <div class="filter_container left" style="width:110px">
                <div class="price-plugin row">
                    <div class="js-fToggle fHead closed accordion-toggle collapsed" data-toggle="collapse" data-target='#slider_body'>
                        <span class="fLabel" title="Pris">Pris</span>
                    </div>
                    <div class="filterBox accordion-collapse collapse" id='slider_body'>
                        <div class="noScrollBar">
                           
                                <div id="slider-range"></div>
                                <div class="sliderInput">
                                    <div class="plugin_border marginLeft right">
                                        <input type='text' class='inputText' id='lowerPrice' >
                                        <span>kr</span>
                                    </div>
                                    <span>-</span>
                                    <div class="plugin_border" style="float:right">
                                        <input type='text' class='inputText' id='upperPrice'>
                                        <span>kr</span>
                                    </div>
                                    <div class="noJsHidden" data-filtersection="sale">
                                        
                                    </div>
                                </div>
                                <div>
                                    <input type='button' id="btn_price_ranges" class="btn btn-primary" value='Välj'>
                                </div>
                            

                        </div>
                    </div>
                </div>
                </div>
     </div>
     <?php } ?>
     </div>
 <?php }?>
<div class="float_right" style="margin-left: 10px;">
  <div class="catageries">   
    <div class="clear"></div>
    <div id="product_cat">
      <?php
           echo $products;
	  ?>
    </div>
  </div>
  <!--catageries-->
  <div class="clear"></div>
<?php 
if(isset($total_records) && $total_records)
{
?>
<div class="total_products">Antal produkter: <b>
<?=$total_records;?>
</b></div>
<div class="pages_links"><?php echo $pagination?></div>
<!--pages_links-->
<?php
}
?>
</div>
 
					
<script type="text/javascript">
var resultArray ="<?php echo $get_brand?>";
        $(function () {
			 $("#slider-range").slider({
                range: true,
                min: <?php echo round( $price_ranges['min_price']); ?>,
                max: <?php echo round($price_ranges['max_price']); ?>,
                values: [<?php echo round($price_ranges['min_price']);?>,<?php echo round($price_ranges['max_price']);?>], // initial values
                slide: function (event, ui) {
                    $("#lowerPrice").val(ui.values[ 0 ]);
                    $("#upperPrice").val(ui.values[ 1 ]);
                }
            });
            $("#lowerPrice").val($("#slider-range").slider("values", 0));
            $("#upperPrice").val($("#slider-range").slider("values", 1));

            $(".inputText").blur(function (event) {
                var lowerPrice = parseInt($("#lowerPrice").val());
                var upperPrice = parseInt($("#upperPrice").val());
                console.log(upperPrice);
                if (lowerPrice > upperPrice) {
                    alert('Invalid price upper and lower limmits');
                    return false;
                }
                if (event.target.id == "lowerPrice") {
                    $("#slider-range").slider("values", 0, lowerPrice)
                }
                if (event.target.id == "upperPrice") {
                    $("#slider-range").slider("values", 1, upperPrice);
                }
				
            });

            $('#box').keyup(function () {
                var valThis = $(this).val().toLowerCase();
                var noresult = 0;
                if (valThis == "") {
                    $('.navList > li').show();
                    noresult = 1;
                    $('.no-results-found').remove();
                } else {
                    $('.navList > li').each(function () {
                        var text = $(this).text().toLowerCase();
                        var match = text.indexOf(valThis);
                        if (match >= 0) {
                            $(this).show();
                            noresult = 1;
                            $('.no-results-found').remove();
                        } else {
                            $(this).hide();
                        }
                    });
                }
                ;
                if (noresult == 0) {
                    $(".navList").append('<li class="no-results-found">No results found.</li>');
                }
            });
			
            $('.navList').click(function (val) {
                var values = $("[name=search_filter]:checked");
                if (values.length > 0) {
                    $('.submit').removeAttr('disabled');
                }
                else
                {
                    $('.submit').attr('disabled', 'disabled');
                }
                resultArray = ($.map($('input:checkbox:checked'), function (e, i) {
                    return e.value;
                })).join("."); 
                //console.log(resultArray);
            });
            $("#filter_items li").click(function (event) {
                 removed_brand = $(this).find('span').html();
				 $(".noScrollBar :input[value='"+removed_brand+"']").remove();
			     $(this).remove();
				 resultArray = ($.map($('input:checkbox:checked'), function (e, i) {
                    return e.value;
                })).join("."); 
				 $("#btn_brands").trigger('click');
            });
            $('.accordion-toggle').click(function () {
                $('.accordion-collapse').collapse('hide');
            });
            $('.wrapper-scroll').enscroll({
                showOnHover: false,
                verticalTrackClass: 'track3',
                verticalHandleClass: 'handle3'
            });
			$("#btn_brands").click(function(){
			    str_url  ="<?=base_url('category');?>/?cat_id=<?=$cat_id?>&min_price=<?=$get_min_price?>&max_price=<?=$get_max_price?>&brand="+resultArray+"&per_page="; 
				$(location).attr('href',str_url);
			});
			$("#filter_price").click(function(){
			    str_url  ="<?=base_url('category');?>/?cat_id=<?=$cat_id?>&min_price=&max_price=&brand="+resultArray+"&per_page="; 
				$(location).attr('href',str_url);
			});
			$("#btn_price_ranges").click(function(){
				min_price = $("#lowerPrice").val();
				max_price = $("#upperPrice").val();
			    str_url  ="<?=base_url('category');?>/?cat_id=<?=$cat_id?>&min_price="+min_price+"&max_price="+max_price+"&brand="+resultArray+"&per_page="; 
				$(location).attr('href',str_url);
			   });
			
        });
    </script>