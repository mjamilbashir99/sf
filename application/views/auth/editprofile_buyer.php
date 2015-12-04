<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<?php $this -> load -> view('responsive/head.php');?>
	<script>
		$(document).ready(function() {
			$("#regform").validationEngine();
		});
	</script>
	<body>
		<div class="wrapper">
			<div class="container">
				<header>
					<div class="header">
						<?php $this->load->view('responsive/header.html')
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
						<p align="center">
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
										<td height="20" width="165"><span style="font-weight: 700"> <font style="font-size: 10px" face="Tahoma"> FIRST NAME</font> </span></td>
										<td height="20" width="20">&nbsp;</td>
										<td height="20" width="165"><span style="font-weight: 700"> <font style="font-size: 10px" face="Tahoma"> LAST NAME</font> </span></td>
									</tr>
									<tr>
										<td  height="25" width="165">
										<input style="border: 1px solid #000000;font-family:Tahoma;width:165px;height:25px;" type="text" id="first_name" class="validate[required] text small" name="first_name" value="<?=$user['first_name']?>">
										</td>
										<td height="25" width="20">&nbsp;</td>
										<td height="25" width="165">
										<input style="border: 1px solid #000000;font-family:Tahoma;width:165px;height:25px;" type="text" id="last_name" class="validate[required] text small" name="last_name" value="<?=$user['last_name']?>">
										</td>
									</tr>
									<tr>
										<td height="20" width="165">&nbsp;</td>
										<td height="20" width="20">&nbsp;</td>
										<td height="20" width="165">&nbsp;</td>
									</tr>
									<tr>
										<td height="20" width="165"><span style="font-weight: 700"> <font style="font-size: 10px" face="Tahoma"> E-MAIL</font> </span></td>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td height="25" width="165">
										<input style="border: 1px solid #000000;font-family:Tahoma;width:165px;height:25px;" type="text" id="email" class="validate[required,custom[email]] text small" disabled="true" name="email" value="<?=$user['email']?>">
										</td>
										<td colspan="2">&nbsp;</td>
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
										<td width="165" height="20"><span style="font-weight: 700"> <font face="Tahoma" style="font-size: 10px"> BIRTH YEAR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												GENDER</font></span></td>
										<td height="20" width="20">&nbsp;</td>
										<td height="20" width="165"><span style="font-weight: 700"> <font style="font-size: 10px" face="Tahoma"> CITY</font></span></td>
									</tr>
									<tr>
										<td width="165" height="25">
											<select name="dob" id="dob">
												<?php
												for($i = date('Y'); $i >= date('Y', strtotime('-100 years')); $i--){
													$sel = (($user['dob'] == $i)?'selected="selected"':'');
										          echo "<option value='$i' $sel>$i</option>";
										        } 
												?>
											</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<select name="gender" id="gender">
												<option value="1" <?=(($user['gender'] == '1')?'selected="selected"':'')?>>Male</option>
												<option value="0" <?=(($user['gender'] == '0')?'selected="selected"':'')?>>Female</option>
											</select>
										</td>
										<td height="25" width="20">&nbsp;</td>
										<td width="165" height="25">
											<select class="styled" name="city_id" id="city_id">
												<?=create_ddl($cities,'city_id','city_name', $user['city_id'])?>
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
										<span style="text-decoration: underline">
										I agree to Salefinder terms and 
										conditions</span></font></p></td>
									</tr>
									<tr>
										<td width="350" height="25" colspan="3">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3" height="25" width="350">
										<p align="center">
											<input type="submit" value="Update"/>
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
					<?php $this->load->view('responsive/footer.html')
					?>
				</div><!--container-->
			</div><!--wrapper-->
	</body>
</html>
