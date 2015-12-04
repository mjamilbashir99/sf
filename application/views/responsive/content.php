					<div class="float_right" style="margin-left: 20px;">
						<p style="padding-left:10px">
							<?php if($content['c_image']!=''){?>
                               <img src="<?php echo base_url()?>assets/uploads/images/page_contents/<?php echo $content['c_image'] ?>" height="33"/>
                            <?php }else{?>                           
                            <span style="font-weight: 700"> <font face="Tahoma" style="font-size: 10px"> <?=$content['c_name']?></font></span>
                            <?php }?>
						</p>
						<p>&nbsp;</p>
						<?=$content['c_text']?>
					</div>
					<div class="clear"></div>
					<!--footer-->
					