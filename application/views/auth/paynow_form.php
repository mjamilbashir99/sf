<?=js_asset('jquery-1.8.1.min.js');?>
<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" name="payment" id="payment" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="usmanf_1311934583_biz@hotmail.com">
<input type="hidden" name="item_name" value="<?=$this->config->item('site_title')?> Order#<?=$order['id']?>">
<input type="hidden" name="item_number" value="<?=$this->config->item('site_title')?> payment">
<input type="hidden" name="amount" value="<?=number_format($order['totalprice'],2)?>">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="notify_url" value="<?=base_url()?>auth/ipn">
<input type="hidden" name="return" value="<?=base_url()?>auth/subscription_thanks">
<input type="hidden" name="cancel_return" value="<?=base_url()?>auth/subscription_cancel/<?=$order['id']?>">
<input type="hidden" name="first_name" value="<?=$profile['first_name']?>">
<input type="hidden" name="last_name" value="<?=$profile['last_name']?>">
<input type="hidden" name="address1" value="<?=$profile['postal_address']?>">
<input type="hidden" name="address2" value="">
<input type="hidden" name="country" value="">
<input type="hidden" name="city" value="">
<input type="hidden" name="state" value="">
<input type="hidden" name="zip" value="">
<input type="hidden" name="email" value="<?=$user->email?>">
<input type="hidden" name="invoice" value="<?=$order['id']?>">
<input type="hidden" name="night_phone_a" value="">
</form>
<script type="text/javascript">
$(document).ready(function(){
	$('#payment').submit();	
});
</script>