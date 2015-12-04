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
					<form name="orderform"  id="orderform" action="" method="post">
                        <h3>Choose your Subsciption Package:</h3>
                        <hr />
                        <p>
							<label>Package:</label>
							<br />
							<?php
							foreach ($levels as $level) {?>
								<input type="radio" name="subscription_id" value="<?=$level['id']?>" /><?=$level['name']?> (<?=$level['description']?>)<br />
							<?php
							}?>
							<span class="note error"></span>
						</p>
                        <input type="submit" name="paynow" value="Proceed to Payment" />
                        
                    </form>
					
					
					<div class="clear"></div>
					<!--footer-->
					<?php $this->load->view('responsive/footer.php')?>
				</div><!--container-->
			</div><!--wrapper-->
	</body>
</html>