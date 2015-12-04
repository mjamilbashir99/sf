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
		    	cat_limit = $(this).find("option:selected").data("cat_limit");
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
    <style>
    #subscription_text
	{
		background:#EEE;
		padding:5px;
		margin-bottom:5px;
	}
    </style>
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
						<?php $this->load->view('responsive/reg_left_bar.php')
						?>
					</div>
					<div class="float_right" style="margin-left: 10px;">
                                            <div style="display: none;">
						<p align="center">&nbsp;
							
						</p>
						<p align="center">
							<?=image_asset('register_retailer.jpg')?>
							<br>
							<font face="Tahoma" style="font-size: 10px"><br>
								SIGN IN IF YOU ARE ALREADY A REGISTERED MEMBER:</font>
								<br />
							&nbsp;
						</p>
						<div align="center">
							<form id="loginform" action="<?=base_url()?>auth/login" method="post" accept-charset="utf-8">
							<table id="table16" border="0" cellpadding="0" cellspacing="0" width="350">
								<tbody>
									<tr>
										<td height="20" width="165"><font style="font-size: 10px; font-weight: 700" face="Tahoma"> E-Post</font></td>
										<td height="20" width="20">&nbsp;</td>
										<td height="20" width="165"><span style="font-weight: 700"> <font style="font-size: 10px" face="Tahoma"> LÖSENORD</font></span></td>
									</tr>
									<tr>
										<td style="" height="25" width="165">
											<input type="text" name="email" id="emailadress" class="validate[required,custom[email]]" maxlength="80" size="30" style="border: 1px solid #000000;width:165px;height:25px;" />
										</td>
										<td height="25" width="20">&nbsp;</td>
										<td height="25" width="165">
											<input type="password" name="password" id="pass" class="validate[required]" size="30" style="border: 1px solid #000000;width:165px;height:25px;" />
										</td>
									</tr>
									<tr>
										<td height="25" width="165" style="color: red;"></td>
										<td height="20" width="20">&nbsp;</td>
										<td height="25" width="165" style="color: red;"></td>
									</tr>
									<tr>
										<td height="20" width="165">&nbsp;</td>
										<td height="20" width="20">&nbsp;</td>
										<td height="20" width="165">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3" height="25" width="350">
										<p align="center">
											<input type="image" src="<?=image_asset_url('button-signin.jpg')?>" />
										</p></td>
									</tr>
								</tbody>
							</table>
							<?php echo form_close();?>
						</div>
						<p align="center">
							<a style="font-size: 10px; text-decoration: underline;font-family: Tahoma" href="<?=base_url()?>auth/forgot_password"> FORGOT YOUR PASSWORD OR USERNAME?</a>
						</p>
						<p align="center">
							<span style="text-decoration: underline"> 
								<a style="font-size: 10px; font-weight: 700;font-family: Tahoma" href="#" class="reg_btn"> NOT REGISTERED YET? GET STARTED HERE!</a></span>
						</p>
						<p align="center">&nbsp;
							
						</p>
						<p align="center">&nbsp;
							
						</p>
                                        </div>
						<div align="center" class="regformdiv">
							<p align="center" style="margin:10px 0px;">
								<?=image_asset('register_retailer.jpg')
								?>
								<br>
								<font face="Tahoma" style="font-size: 10px">
									<br>
									FYLL I FORMULÄRET NEDAN FÖR ATT REGISTRERA OCH BÖRJA SÄLJA PÅ SALEFINDER:
                                                                </font>
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
											<input style="border: 1px solid #000000;font-family:Tahoma;width:165px;height:25px;" type="text" id="company_name" class="validate[required] text small" name="company_name">
											</td>
											<td height="25" width="20">&nbsp;</td>
											<td height="25" width="165">
											<input style="border: 1px solid #000000;font-family:Tahoma;width:165px;height:25px;" type="text" id="organisation_number" class="validate[required] text small" name="organisation_number">
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
											<td height="20" width="165"><span style="font-weight: 700"> <font style="font-size: 10px" face="Tahoma"> E-Post Adress</font> </span></td>
										</tr>
										<tr>
											<td height="25" width="165">
											<input style="border: 1px solid #000000;font-family:Tahoma;width:165px;height:25px;" type="text" id="contact_person" class="validate[required] text small" name="contact_person">
											</td>
											<td height="25" width="20">&nbsp;</td>
											<td height="25" width="165">
											<input style="border: 1px solid #000000;font-family:Tahoma;width:165px;height:25px;" type="text" id="email" class="validate[required,custom[email],ajax[ajaxEmailCall]] text small" name="email">
											</td>
										</tr>
										<tr>
											<td height="20" width="165">&nbsp;</td>
											<td height="20" width="20">&nbsp;</td>
											<td height="20" width="165">&nbsp;</td>
										</tr>
										<tr>
											<td width="165" height="20"><span style="font-weight: 700"> <font face="Tahoma" style="font-size: 10px"> Kontakt nummer</font></span></td>
											<td width="20" height="20">&nbsp;</td>
											<td width="165" height="20"><span style="font-weight: 700"> <font face="Tahoma" style="font-size: 10px">  Adress</font></span></td>
										</tr>
										<tr>
											<td height="25" width="165">
											<input style="border: 1px solid #000000;font-family:Tahoma;width:165px;height:25px;" type="text" id="phone_number" class="validate[required,custom[integer]] text small" name="phone_number">
											</td>
											<td height="25" width="20">&nbsp;</td>
											<td width="165" height="25">
											<input style="border: 1px solid #000000;font-family:Tahoma;width:165px;height:25px;" id="postal_address" class="validate[required] text small" name="postal_address"></td>
										</tr>
										<tr>
											<td height="20" width="165">&nbsp;</td>
											<td height="20" width="20">&nbsp;</td>
											<td height="20" width="165">&nbsp;</td>
										</tr>
										<tr>
											<td height="20" width="165"><span style="font-weight: 700"> <font style="font-size: 10px" face="Tahoma"> Post nummer</font></span></td>
											<td height="20" width="20">&nbsp;</td>
											<td height="20" width="165"><span style="font-weight: 700"> <font style="font-size: 10px" face="Tahoma"> Stad</font></span></td>
										</tr>
										<tr>
											<td height="25" width="165">
											<input style="border: 1px solid #000000;font-family:Tahoma;width:165px;height:25px;" type="text" id="zipCode" class="validate[required] text small" name="zipCode">
											</td>
											<td height="25" width="20">&nbsp;</td>
											<td height="25" width="165">
											<select class="styled" name="city_id" id="city_id">
												<?=create_ddl($cities,'city_id','city_name')
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
											<td height="20" width="165"><span style="font-weight: 700"> <font style="font-size: 10px" face="Tahoma"> LÖSENORD</font></span></td>
											<td height="20" width="20">&nbsp;</td>
											<td height="20" width="165"><span style="font-weight: 700"> <font style="font-size: 10px" face="Tahoma"> BEKRÄFTA LÖSENORD</font></span></td>
										</tr>
										<tr>
											<td height="25" width="165">
											<input style="border: 1px solid #000000;font-family:Tahoma;width:165px;height:25px;" type="password" id="password" class="validate[required] text small" name="password">
											</td>
											<td height="25" width="20">&nbsp;</td>
											<td height="25" width="165">
											<input style="border: 1px solid #000000;font-family:Tahoma;width:165px;height:25px;" type="password" id="confirm_password" class="validate[required,equals[password]] text small" name="confirm_password">
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
												<input type="hidden" name="logo_image" id="logo_image" value="" />
									            <input type="hidden" name="logo_ext" id="logo_ext" value="" />
											</td>
											<td height="20" width="165">
												<img id="proimage" />
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
                                            <div id="subscription_text">
                                            	
                                            </div>
											<select name="subsciption_level" id="subsciption_level">
												<?=get_subscription_levels()
												?>
1											</select></td>
										</tr>
										<tr>
											<td height="20" width="165">&nbsp;</td>
											<td height="20" width="20">&nbsp;</td>
											<td height="20" width="165">&nbsp;</td>
										</tr>
										<tr class="cat_mod">
											<td width="165" height="20"><span style="font-weight: 700"> <font face="Tahoma" style="font-size: 10px"> Välj din kategori</font></span></td>
											<td width="20" height="20">&nbsp;</td>
											<td width="165" height="20">&nbsp;</td>
										</tr>
										<tr class="cat_mod">
											<td width="350" height="65" bgcolor="#EEEEEE" colspan="3">&nbsp;
												<?=get_parent_cat_checklist()?>
											</td>
										</tr>
										<tr>
											<td width="350" height="25" colspan="3">&nbsp;</td>
										</tr>
										<tr>
											<td width="350" height="25" colspan="3">
											<p align="center">
												<input type="checkbox" value="1" name="agree" id="agree" class="validate[required]">    
												<font face="Tahoma" style="font-size: 10px">&nbsp;</font>
                                                
                                               
												<font face="Tahoma" style="font-size: 12px"> 
                                                <span style="text-decoration: underline"> 
                                                  <a href="" class="topopup">JAG GODKÄNNER SALEFINDER ALLMÄNNA VILLKOR</a>
										        </span></font>
											</p></td>
										</tr>
										<tr>
											<td width="350" height="25" colspan="3">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" height="25" width="350">
											<p align="center">
												<input type="image" src="<?=image_asset_url('button-register.jpg')?>" />
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
           <div id="toPopup">
	 	        <div class="close" ></div>
	        <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
	        <div id="popup_content"> <!--your content start-->
	            <p>

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec tincidunt, augue ultricies ultrices blandit, orci massa placerat nisl, a vestibulum sapien nisl in sem. Sed imperdiet sodales neque a mattis. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Cras eu laoreet justo, a dignissim mauris. Mauris quis eros sit amet tortor dapibus convallis. Sed bibendum venenatis felis in aliquam. Maecenas commodo mattis dui, non ultrices lacus sodales quis. Aenean eget eros quam. Sed eget turpis non ante tempor cursus. Phasellus in magna felis. Vestibulum eu suscipit ipsum, id vehicula quam. Sed lobortis, nisl quis congue posuere, massa velit porttitor lacus, in ultricies libero massa vitae nisl.
</p><p>
Donec in blandit eros. Vivamus mattis convallis lorem, at semper elit accumsan vitae. Phasellus mollis vel nisl posuere laoreet. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In sodales, ipsum at feugiat gravida, odio felis posuere tellus, sit amet semper elit ipsum eu nisi. Vivamus volutpat odio in turpis tincidunt consectetur. Praesent vel cursus velit, sit amet commodo ipsum. Donec a erat tincidunt, dapibus metus convallis, euismod augue. Nulla facilisi. Aliquam commodo, justo in mollis venenatis, enim enim dapibus libero, id porttitor dolor nibh quis erat. Suspendisse eget pharetra ipsum. Ut at orci pulvinar, semper nisi at, accumsan felis.

Cras eu mauris eu nunc semper eleifend a et ligula. Sed sed fringilla nulla, a vulputate nisl. Sed congue, elit vitae tristique tincidunt, quam neque varius leo, eu ullamcorper ipsum lectus nec purus. Sed in malesuada odio. Nam varius quam ac diam pellentesque, vitae tempus massa elementum. Curabitur id tellus ligula. Duis fringilla eu urna ut egestas. Curabitur iaculis leo at magna convallis vulputate eget non sem. Suspendisse adipiscing, sapien eget ullamcorper convallis, odio erat porta orci, ut consectetur dolor ipsum sit amet sapien.
</p><p>
Sed eros eros, viverra vitae gravida ac, aliquam at sem. Integer tincidunt leo leo, eget pharetra diam euismod vitae. Suspendisse non lectus quis sapien suscipit pellentesque non eget orci. Sed et diam a lectus sagittis placerat. Sed consequat consequat ante, eu semper erat tempus id. Integer gravida tristique orci, viverra ultricies nibh auctor ac. Ut sed euismod odio. Fusce rhoncus dictum ante quis cursus. Donec ut quam non sem sollicitudin placerat. Ut pulvinar ac nisl in posuere. Suspendisse condimentum magna nec diam consectetur, ac congue nunc pulvinar. Nunc semper massa eget laoreet facilisis.

In hac habitasse platea dictumst. Nunc aliquam viverra metus, ut dictum nunc molestie et. Maecenas in sapien id massa mattis luctus. In vitae fermentum leo. Sed sagittis tortor eget augue venenatis, et semper mi feugiat. Phasellus nisi dui, faucibus sit amet nisl sed, dapibus ornare elit. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In id accumsan urna, et tincidunt felis. Integer ac lacus imperdiet dui volutpat vestibulum nec nec magna. Praesent posuere erat nec nisl sodales, sed sagittis lectus dignissim. Nam fringilla ligula ante, sed lobortis nisl accumsan sed. 
                </p>
	        </div> <!--your content end-->
	 
	    </div> <!--toPopup end-->
			<script type="text/javascript">
				function showSubsciptionText()
				{
					subs_value = $("#subsciption_level").val();
					if(subs_value==1)
					{
						subsciption_str ="<p><strong>Small</strong> --- 100 aktiva annonser + under en kategori.(1,000 SEK)</p>";
					}
					else if(subs_value==2)
					{
						subsciption_str ="<p><strong>Medium</strong> --- 500 aktiva annonser + under alla kategorier + 1 banner på förstasidan.(5,000 SEK)</p>";
					}
					else if(subs_value==3)
					{
						subsciption_str ="<p><strong>Large</strong> --- obegränsad antal annonser + under alla kategorier + 1 banner på förstasidan.(10,000 SEK)</p>";
					}
					else if(subs_value==14)
					{
						subsciption_str ="<p><strong>Kampanj</strong> --- 5 aktiva annonser under en kategori. (0 SEK)</p>";
					}
					$("#subscription_text").html(subsciption_str);
				}
				$(document).ready(function() {
					
					showSubsciptionText();
					$("#subsciption_level").change(function(){
					   showSubsciptionText();
					});
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
        
<script type="text/javascript">
// Popup window code
jQuery(function($) {
	    $("a.topopup").click(function() {
	            loading(); // loading
	            setTimeout(function(){ // then show popup, deley in .5 second
	                loadPopup(); // function show popup
	            }, 500); // .5 second
	    return false;
	    });
 
	    /* event for close the popup */
	    $("div.close").hover(
	                    function() {
	                        $('span.ecs_tooltip').show();
	                    },
	                    function () {
	                        $('span.ecs_tooltip').hide();
	                    }
	                );
	 
	    $("div.close").click(function() {
	        disablePopup();  // function close pop up
	    });
	 
	    $(this).keyup(function(event) {
	        if (event.which == 27) { // 27 is 'Ecs' in the keyboard
	            disablePopup();  // function close pop up
	        }
	    });
			 
	        $("div#backgroundPopup").click(function() {
	        disablePopup();  // function close pop up
	    });
	 
	    $('a.livebox').click(function() {
	        alert('Hello World!');
	    return false;
	    });
	 
	     /************** start: functions. **************/
	    function loading() {
	        $("div.loader").show();
	    }
	    function closeloading() {
	        $("div.loader").fadeOut('normal');
	    }
	 
	    var popupStatus = 0; // set value
	 
	    function loadPopup() {
	        if(popupStatus == 0) { // if value is 0, show popup
	            closeloading(); // fadeout loading
	            $("#toPopup").fadeIn(0500); // fadein popup div
	            $("#backgroundPopup").css("opacity", "0.7"); // css opacity, supports IE7, IE8
            $("#backgroundPopup").fadeIn(0001);
	            popupStatus = 1; // and set value to 1
	        }
	    }
	 
	    function disablePopup() {
	        if(popupStatus == 1) { // if value is 1, close popup
	            $("#toPopup").fadeOut("normal");
	            $("#backgroundPopup").fadeOut("normal");
	            popupStatus = 0;  // and set value to 0
	        }
	    }
	    /************** end: functions. **************/
	}); // jQuery End
</script>

                                                
        
	</body>
</html>
