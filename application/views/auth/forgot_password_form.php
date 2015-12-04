<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php $this->load->view('responsive/head.php'); ?>
	<body>
		<div class="wrapper">
			<div class="container">
				<header>
					<div class="header">
						<?php $this->load->view('responsive/header.php')?>
					</div><!--header-->
				</header>
				<div class="clear"></div><!--clear-->
				<div class="main_body">
					
					<?php
					$login = array(
						'name'	=> 'login',
						'id'	=> 'login',
						'value' => set_value('login'),
						'maxlength'	=> 80,
						'size'	=> 30,
					);
					if ($this->config->item('use_username', 'tank_auth')) {
						$login_label = 'Email or login';
					} else {
						$login_label = 'Email';
					}
					?>
					<?php echo form_open($this->uri->uri_string()); ?>
					<table>
						<tr>
							<td><?php echo form_label($login_label, $login['id']); ?></td>
							<td><?php echo form_input($login); ?></td>
							<td style="color: red;"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="2">
								<?php echo form_submit('reset', 'Get a new password'); ?>
							</td>
						</tr>
					</table>
					<?php echo form_close(); ?>

					<div class="clear"></div>
					<!--footer-->
					<?php $this->load->view('responsive/footer.php')?>
				</div><!--container-->
			</div><!--wrapper-->
	</body>
</html>
