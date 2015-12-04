<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<?php $this->load->view('responsive/head.php'); ?>
	<?=css_asset("datatables.css","admin")?>
	<?=js_asset("jquery.dataTables.min.js","admin")?>
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
						<div style="min-height:500px">
							<h1>News Board</h1>
							<hr />
							<br />
							<h3>Add</h3>
							<form action="" method="post">
								<p>
									<textarea name="news_text"></textarea>
								</p>
								<p style="margin-top:10px">
									<input type="submit" name="add" value="Add News" />
								</p>
							</form>
							<br />
							<hr />
							<h3>View</h3>
							<br />
							<form action="" method="post">
								<table cellpadding="0" cellspacing="0" width="100%" class="datatable" id="quiz_data">
									<thead>
										<tr>
											<th>News</th>
											<th>Status</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?=$news_html;?>
									</tbody>
								</table>
							</form>
						</div>
					</div>
					<div class="clear"></div>
					<!--footer-->
					<?php $this->load->view('responsive/footer.php')?>
				</div><!--container-->
			</div><!--wrapper-->
			<script type="text/javascript">
				$(document).ready(function() {
					$('#quiz_data').dataTable({
						"iDisplayLength" : 25,
						"aoColumns" : [{
							"asSorting" : ["asc"]
						}, null, {
							"asSorting" : false
						}]
					});
					$('.deletenews').live('click', function() {
						var pid = $(this).attr('rel');
						var x = window.confirm("WARNING: This will permanently remove the selected news. Are you sure you wish to do this?");
						if(x) {
							window.location = '<?=base_url()?>user/delete_news/' + pid;
						}
						return false;
					});
				});

			</script>
	</body>
</html>