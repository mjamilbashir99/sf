
	<script>
		$(document).ready(function() {
			$("#regform").validationEngine();
		});
		
		
		
		
	</script>
	
					<div class="float_right" style="margin-left: 10px;">
						<p align="center">&nbsp;
							
						</p>
						<p style="text-align: center;">
							<?=image_asset('register.jpg')
							?>
							<br>
							&nbsp;
						</p>
						<div align="center">
							<form id="regform" action="" method="post">
							<table id="table16" border="0" cellpadding="0" cellspacing="0" width="350">
								<tbody>
									<tr>
										<td height="20" width="165"><span style="font-weight: 700"> <font style="font-size: 10px" face="Tahoma">FÖRNAMN</font> </span></td>
										<td height="20" width="20">&nbsp;</td>
										<td height="20" width="165"><span style="font-weight: 700"> <font style="font-size: 10px" face="Tahoma"> EFTERNAMN</font> </span></td>
									</tr>
									<tr>
										<td  height="25" width="165">
										<input style="border: 1px solid #000000;font-family:Tahoma;width:165px;height:25px;" type="text" id="first_name" class="validate[required] text small" name="first_name">
										</td>
										<td height="25" width="20">&nbsp;</td>
										<td height="25" width="165">
										<input style="border: 1px solid #000000;font-family:Tahoma;width:165px;height:25px;" type="text" id="last_name" class="validate[required] text small" name="last_name">
										</td>
									</tr>
									<tr>
										<td height="20" width="165">&nbsp;</td>
										<td height="20" width="20">&nbsp;</td>
										<td height="20" width="165">&nbsp;</td>
									</tr>
									<tr>
										<td height="20" width="165"><span style="font-weight: 700"> <font style="font-size: 10px" face="Tahoma"> E-POST</font> </span></td>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td height="25" width="165">
										<input style="border: 1px solid #000000;font-family:Tahoma;width:165px;height:25px;" type="text" id="email" class="validate[required,custom[email],ajax[ajaxEmailCall]] text small" name="email">
										</td>
										<td colspan="2">&nbsp;</td>
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
										<td width="165" height="20"><span style="font-weight: 700"> <font face="Tahoma" style="font-size: 10px"> FÖDELSEÅR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											KÖN</font></span></td>
										<td height="20" width="20">&nbsp;</td>
										<td height="20" width="165"><span style="font-weight: 700"> <font style="font-size: 10px" face="Tahoma"> STAD</font></span></td>
									</tr>
									<tr>
										<td width="165" height="25">
											<select name="dob" id="dob">
												<?php
												for($i = date('Y'); $i >= date('Y', strtotime('-100 years')); $i--){
										          echo "<option value='$i'>$i</option>";
										        } 
												?>
											</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<select name="gender" id="gender">
												<option value="1">Man</option>
												<option value="0">Kvinna</option>
											</select>
										</td>
										<td height="25" width="20">&nbsp;</td>
										<td width="165" height="25">
											<select class="styled" name="city_id" id="city_id">
												<?=create_ddl($cities,'city_id','city_name')?>
											</select>
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
										<span style="text-decoration: underline">   <a href="" class="topopup">JAG GODKÄNNER SALEFINDER ALLMÄNNA VILLKOR</a>
										</span></font></p></td>
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
					
	</body>
</html>
<script>
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
