<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include('head.php'); ?>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
		$(document).ready(function() {
			$(".group1").colorbox({
				rel : 'group1',
				width : '800px',
				height : '600px',
				scalePhotos : true,
				slideshow : false
			});
            $('.buylink').click(function(){
            	$.post("<?=base_url()?>product/updatebuycount/", {pid:$(this).attr('rel')},function(){
            	});
            });            
		});
		var geocoder;
		var map;
		var address = '113 j1 wapda town';
		function initialize() {
			var address = $('#map_canvas').attr('rel');
			geocoder = new google.maps.Geocoder();
			var latlng = new google.maps.LatLng(-34.397, 150.644);
			var myOptions = {
				zoom : 8,
				center : latlng,
				mapTypeControl : true,
				mapTypeControlOptions : {
					style : google.maps.MapTypeControlStyle.DROPDOWN_MENU
				},
				navigationControl : true,
				mapTypeId : google.maps.MapTypeId.ROADMAP
			};
			map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
			if(geocoder) {
				geocoder.geocode({
					'address' : address
				}, function(results, status) {
					if(status == google.maps.GeocoderStatus.OK) {
						if(status != google.maps.GeocoderStatus.ZERO_RESULTS) {
							map.setCenter(results[0].geometry.location);

							var infowindow = new google.maps.InfoWindow({
								content : '<b>' + address + '</b>',
								size : new google.maps.Size(150, 50)
							});

							var marker = new google.maps.Marker({
								position : results[0].geometry.location,
								map : map,
								title : address
							});
							google.maps.event.addListener(marker, 'click', function() {
								infowindow.open(map, marker);
							});
						} else {
							alert("No results found");
						}
					} else {
						alert("Geocode was not successful for the following reason: " + status);
					}
				});
			}
		}
	</script>
<body onload="initialize()">
<div class="wrapper">
  <div class="container">
    <header>
      <div class="header">
        <?php include('header.php')?>
      </div>
      <!--header--> 
    </header>
    <div class="clear"></div>
    <!--clear-->
    <?php echo $contents?>
     <!--main_body-->
    <div class="clear"></div>
    <!--footer-->
    <?php include("footer.php")?>
  </div>
  <!--container--> 
</div>
<!--wrapper-->
</body>
</html>
