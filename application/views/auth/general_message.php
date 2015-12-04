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
					<div class="float_left">
						<?php $this->load->view('responsive/reg_left_bar.php')?>	
					</div>
					<div class="float_right" style="margin-left: 10px;">
						<p align="center">&nbsp;
							
						</p>
						<p style="color: #FF5050;min-height:200px;">
							<?php echo $message; ?>
						</p>
					</div>
					<div class="clear"></div>
					<!--footer-->
					<?php $this->load->view('responsive/footer.php')?>
				</div><!--container-->
			</div><!--wrapper-->
	</body>
</html>