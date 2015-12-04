<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<?php $this -> load -> view('responsive/head.php');?>
	<?=js_asset("jquery.filestyle.mini.js","admin")?>
	<?=js_asset("ajaxupload.js","admin")?>
	<?=js_asset('jquery.tmpl.min.js')?>
	<?=js_asset('jquery.uploadify.js')?>	
	<script>
		$(document).ready(function(){
			$("#regform").validationEngine();
			$("#loginform").validationEngine();
			var cat_limit = 1;
			$('#subsciption_level').change(function() {
		    	cat_limit = $(this).find("option:selected").attr("title");
		    	if(cat_limit == 0){
		    		$('.cat_mod').hide();
		    	}
		    	else{
		    		$('.cat_mod').show();
		    	}
		    });
			$('.selected_cat').click(function(){
				if(cat_limit > 0){
					if($('.selected_cat:checked').length > 1){
						return false;	
					}
				}
			});
			$('.reg_btn').click(function(){
				$('.regformdiv').show();
				return false;
			});
		});
	</script>
	<body>
		<div class="wrapper">
			<div class="container">
				<header>
					<div class="header">
						<?php $this->load->view('responsive/header.php')
						?>
					</div><!--header-->
				</header>
				<div class="clear"></div><!--clear-->
				<div class="main_body">
					<div class="float_left">
						<?php $this->load->view('responsive/reg_left_bar.html')
						?>
					</div>
					<div class="float_right" style="margin-left: 10px;">
						<p align="center">&nbsp;
							
						</p>
						<p align="center">&nbsp;
							
						</p>
						
						<div align="center" class="regformdiv">
							<p align="center" style="margin:10px 0px;">
								<?=image_asset('register.jpg')
								?>
								<br>
							</p>
							<form id="regform" action="" method="post">
								<table id="table16" border="0" cellpadding="0" cellspacing="0" width="350">
									<tbody>
										<tr>
											<td height="20" width="165"><span style="font-weight: 700"> <font style="font-size: 10px" face="Tahoma"> Företagsnamn </font> </span></td>
											<td height="20" width="20">&nbsp;</td>
											<td height="20" width="165"><span style="font-weight: 700"> <font style="font-size: 10px" face="Tahoma"> Organisationsnummer </font> </span></td>
										</tr>
										<tr>
											<td  height="25" width="165">
											<input style="border: 1px solid #000000;font-family:Tahoma;width:165px;height:25px;" type="text" id="company_name" class="validate[required] text small" name="company_name" value="<?=$user['company_name']?>">
											</td>
											<td height="25" width="20">&nbsp;</td>
											<td height="25" width="165">
											<input style="border: 1px solid #000000;font-family:Tahoma;width:165px;height:25px;" type="text" id="organisation_number" class="validate[required] text small" name="organisation_number" value="<?=$user['organisation_number']?>">
											</td>
										</tr>
										<tr>
											<td height="20" width="165">&nbsp;</td>
											<td height="20" width="20">&nbsp;</td>
											<td height="20" width="165">&nbsp;</td>
										</tr>
										<tr>
											<td height="20" width="165"><span style="font-weight: 700"> <font style="font-size: 10px" face="Tahoma"> Kontaktperson</font> </span></td>
											<td height="20" width="20">&nbsp;</td>
											<td height="20" width="165"><span style="font-weight: 700"> <font style="font-size: 10px" face="Tahoma"> E-MAIL ADDRESS</font> </span></td>
										</tr>
										<tr>
											<td height="25" width="165">
											<input style="border: 1px solid #000000;font-family:Tahoma;width:165px;height:25px;" type="text" id="contact_person" class="validate[required] text small" name="contact_person" value="<?=$user['contact_person']?>">
											</td>
											<td height="25" width="20">&nbsp;</td>
											<td height="25" width="165">
											<input style="border: 1px solid #000000;font-family:Tahoma;width:165px;height:25px;" type="text" id="email" class="validate[required,custom[email]] text small" name="email" disabled="true" value="<?=$user['email']?>">
											</td>
										</tr>
										<tr>
											<td height="20" width="165">&nbsp;</td>
											<td height="20" width="20">&nbsp;</td>
											<td height="20" width="165">&nbsp;</td>
										</tr>
										<tr>
											<td width="165" height="20"><span style="font-weight: 700"> <font face="Tahoma" style="font-size: 10px"> Kontaktnummer</font></span></td>
											<td width="20" height="20">&nbsp;</td>
											<td width="165" height="20"><span style="font-weight: 700"> <font face="Tahoma" style="font-size: 10px"> Adress </font></span></td>
										</tr>
										<tr>
											<td height="25" width="165">
											<input style="border: 1px solid #000000;font-family:Tahoma;width:165px;height:25px;" type="text" id="phone_number" class="validate[required,custom[integer]] text small" name="phone_number" value="<?=$user['phone_number']?>">
											</td>
											<td height="25" width="20">&nbsp;</td>
											<td width="165" height="25">
											<input style="border: 1px solid #000000;font-family:Tahoma;width:165px;height:25px;" id="postal_address" class="validate[required] text small" name="postal_address" value="<?=$user['postal_address']?>"></td>
										</tr>
										<tr>
											<td height="20" width="165">&nbsp;</td>
											<td height="20" width="20">&nbsp;</td>
											<td height="20" width="165">&nbsp;</td>
										</tr>
										<tr>
											<td height="20" width="165"><span style="font-weight: 700"> <font style="font-size: 10px" face="Tahoma"> Postnummer</font></span></td>
											<td height="20" width="20">&nbsp;</td>
											<td height="20" width="165"><span style="font-weight: 700"> <font style="font-size: 10px" face="Tahoma"> Stad   </font></span></td>
										</tr>
										<tr>
											<td height="25" width="165">
											<input style="border: 1px solid #000000;font-family:Tahoma;width:165px;height:25px;" type="text" id="zipCode" class="validate[required] text small" name="zipCode" value="<?=$user['zipCode']?>">
											</td>
											<td height="25" width="20">&nbsp;</td>
											<td height="25" width="165">
											<select class="styled" name="city_id" id="city_id">
												<?=create_ddl($cities,'city_id','city_name', $user['city_id'])
												?>
											</select>
											</td>
										</tr>
										<tr>
											<td height="20" width="165">&nbsp;</td>
											<td height="20" width="20">&nbsp;</td>
											<td height="20" width="165">&nbsp;</td>
										</tr>
										<tr>
											<td height="20" width="165"><span style="font-weight: 700"> <font style="font-size: 10px" face="Tahoma"> PASSWORD</font></span></td>
											<td height="20" width="20">&nbsp;</td>
											<td height="20" width="165"><span style="font-weight: 700"> <font style="font-size: 10px" face="Tahoma"> CONFIRM PASSWORD</font></span></td>
										</tr>
										<tr>
											<td height="25" width="165">
											<input style="border: 1px solid #000000;font-family:Tahoma;width:165px;height:25px;" type="password" id="password" class="text small" name="password">
											</td>
											<td height="25" width="20">&nbsp;</td>
											<td height="25" width="165">
											<input style="border: 1px solid #000000;font-family:Tahoma;width:165px;height:25px;" type="password" id="confirm_password" class="validate[equals[password]] text small" name="confirm_password">
											</td>
										</tr>
										<tr>
											<td height="20" width="165">&nbsp;</td>
											<td height="20" width="20">&nbsp;</td>
											<td height="20" width="165">&nbsp;</td>
										</tr>
										<tr>
											<td height="20" width="165" colspan="3"><span style="font-weight: 700"> <font style="font-size: 10px" face="Tahoma"> LOGO</font> </span></td>
										</tr>
										<tr>
											<td height="25" width="185" colspan="2">
												<input type="file" id="fileupload" />
												<span id="uploadmsg"></span>
												<input type="hidden" name="logo_image" id="logo_image" value="<?=$user['logo_image']?>" />
									            <input type="hidden" name="logo_ext" id="logo_ext" value="<?=$user['logo_ext']?>" />
											</td>
											<td height="20" width="165">
												<img id="proimage" src="<?=other_asset_url($user['logo_image'].'_s.'.$user['logo_ext'],'','uploads/images/retailer')?>" />
											</td>
										</tr>
										<tr>
											<td height="20" width="165">&nbsp;</td>
											<td height="20" width="20">&nbsp;</td>
											<td height="20" width="165">&nbsp;</td>
										</tr>
										<tr>
											<td height="20" width="165" colspan="3"><span style="font-weight: 700"> <font style="font-size: 10px" face="Tahoma"> Välj din prisplan</font> </span></td>
										</tr>
										<tr>
											<td height="25" width="185" colspan="3">
											<?=get_subscription_name($user['subscription_id'])?>
                                                                                        </td>
										</tr>
										<tr>
											<td height="20" width="165">&nbsp;</td>
											<td height="20" width="20">&nbsp;</td>
											<td height="20" width="165">&nbsp;</td>
										</tr>
							
										<tr>
											<td width="350" height="25" colspan="3">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" height="25" width="350">
											<p align="center">
												<input type="submit" value="Uppdatera"/>
											</p></td>
										</tr>
										<tr>
											<td colspan="3" height="25" width="350">&nbsp;</td>
										</tr>
									</tbody>
								</table>
								<?php echo form_close();?>
						</div>
					</div>
					<div class="clear"></div>
					<!--footer-->
					<?php $this->load->view('responsive/footer.php')
					?>
				</div><!--container-->
			</div><!--wrapper-->
			<script type="text/javascript">
				$(document).ready(function() {
					// File upload
					if ($('#fileupload').length) {
						new AjaxUpload('fileupload', {
							action: '<?=base_url()?>auth/add_retailer_image/',
							autoSubmit: true,
							name: 'userfile',
							responseType: 'json',
							onSubmit : function(file , ext) {
								$('.fileupload #uploadmsg').addClass('loading').text('Uploading...');
								this.disable();
							},
							onComplete : function(file, response) {
								if(response == 'error'){
									$('.fileupload #uploadmsg').removeClass('loading').text("Error occured in uploading! Try Again.");
									this.enable();
								}
								else{
									$('#proimage').attr('src',response.imagename);
									$('#logo_image').val(response.name);
									$('#logo_ext').val(response.ext);
									$('.fileupload #uploadmsg').removeClass('loading').text("Uploaded Successfully");
									this.enable();
								}
							}
						});
					}
			} );

            </script>	
    </body>
</html>
