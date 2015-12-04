<html>
<head>
<style>
.image p img{
margin-top:200px;
}
.image {
margin:0 auto;
width:600px;
height:400px;
background:url(<?=base_url()?>assets/images/redirect.jpg?=1);
}
</style>

<meta http-equiv="Content-Language" content="sv">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Salefinder</title>
</head>

<body onLoad="setTimeout('target()', millis(1))">
<div class="image">
<p align="center">
<?php echo image_asset('ajax.gif')?>
</p>
</div>
<script type="text/javascript"> 
    <!--
        function target(){
            window.location.replace("<?php echo urldecode($url)?>");
        }
        function millis(s) {
            return 1000*s;
        }
       
    //--> 
</script>
</body>

</html>
