<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php $this->load->view('responsive/head.php'); ?>
	<body>
		<div class="wrapper">
			<div class="container">
				<header>
					<div class="header">
						<?php $this->load->view('responsive/head.php')?>
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
						<div style="min-height:500px">
							<h1>Profile</h1>
							<hr />
							<br />
							<P><a href="<?=base_url()?>auth/edit_profile/">Hantera mitt konto</a></P>
							<?php
							if($this->session->userdata('group_id') == 1){?>
							<P><a href="<?=base_url()?>product/wishlist/">View your Wishlist</a></P>
							<?php
							}else{?>
							<P><a href="<?=base_url()?>user/addproduct/">Lägg till ny produkt </a></P>
							<P><a href="<?=base_url()?>user/viewproduct/">Visa alla produkter</a></P>
							<P><a href="<?=base_url()?>user/newsboard/">Lägg till nyheter</a></P>
							<P><a href="<?=base_url()?>user/viewinvoice/">Visa faktura</a></P>
							<?php
							}?>
						</div>
					</div>
					<div class="clear"></div>
					<!--footer-->
					<?php $this->load->view('responsive/footer.php')?>
				</div><!--container-->
			</div><!--wrapper-->
	</body>
</html>