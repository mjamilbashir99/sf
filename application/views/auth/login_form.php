<?php $login = array('name' => 'email', 'id' => 'email', 'value' => set_value('email'), 'maxlength' => 80, 'size' => 30, 'class'=> 'validate[required, custom[email]]');
if ($login_by_username AND $login_by_email) {
	$login_label = 'Email or login';
} else if ($login_by_username) {
	$login_label = 'Login';
} else {
	$login_label = 'Email';
}
$password = array('name' => 'password', 'id' => 'password', 'size' => 30, );
$remember = array('name' => 'remember', 'id' => 'remember', 'value' => 1, 'checked' => set_value('remember'), 'style' => 'margin:0;padding:0', );
?>

					<div class="float_right" style="margin-left: 10px;">
						<p align="center">&nbsp;
							
						</p>
						<p align="center">
							<?=image_asset('signin.jpg')?>
							<br>
							<font face="Tahoma" style="font-size: 10px;text-align: center;"><br>
								LOGGA IN OM DU ÄR REDAN REGISTRERAD:</font>
								<br />
							&nbsp;
						</p>
						<div align="center">
							<form id="loginform" action="<?=base_url()?>auth/login" method="post" accept-charset="utf-8">
							<table id="table16" border="0" cellpadding="0" cellspacing="0" width="350">
								<tbody>
									<tr>
										<td height="20" width="165"><font style="font-size: 10px; font-weight: 700" face="Tahoma"> E-POST</font></td>
										<td height="20" width="20">&nbsp;</td>
										<td height="20" width="165"><span style="font-weight: 700"> <font style="font-size: 10px" face="Tahoma"> LÖSENORD</font></span></td>
									</tr>
									<tr>
										<td style="" height="25" width="165">
											<input type="text" name="email" id="email" maxlength="80" size="30" class="validate[required,custom[email]]" style="border: 1px solid #000000;width:165px;height:25px;" />
										</td>
										<td height="25" width="20">&nbsp;</td>
										<td height="25" width="165">
											<input type="password" name="password" id="password" size="30" class="validate[required]" style="border: 1px solid #000000;width:165px;height:25px;" />
										</td>
									</tr>
									<tr>
										<td height="25" width="165" style="color: red;"><?php echo form_error($login['name']);?><?php echo isset($errors[$login['name']]) ? $errors[$login['name']] : '';?></td>
										<td height="20" width="20">&nbsp;</td>
										<td height="25" width="165" style="color: red;"><?php echo form_error($password['name']);?><?php echo isset($errors[$password['name']]) ? $errors[$password['name']] : '';?></td>
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
							<a style="font-size: 10px; text-decoration: underline;font-family: Tahoma" href="<?=base_url()?>auth/forgot_password"> Glömt ditt lösenord eller användarnamn?</a>
						</p>
						<p align="center">
							<span style="text-decoration: underline"> 
								<a style="font-size: 10px; font-weight: 700;font-family: Tahoma" href="<?=base_url()?>auth/register"> Ännu ej registrerad? Registrera nu! </a></span>
						</p>
						<p align="center">&nbsp;
							
						</p>
					</div>
					<div class="clear"></div>
					<!--footer-->
					
