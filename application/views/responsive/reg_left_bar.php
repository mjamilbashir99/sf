<style>
    .mans_cata ul{
        padding-bottom: 10px;
    }
</style>
<div class="catagari" style="min-height:300px">
	<div class="mans_cata">
            <ul style="">
			<li>
				<a href="<?=base_url()?>">> Hem</a>
			</li>
		</ul>
		<h4>HANTERA MITT KONTO</h4>
		<ul>
			<?php
			if (!$this->tank_auth->is_logged_in(TRUE)){?>
			<li>
				<a href="<?=base_url()?>auth/register"> > BLI MEDLEM</a>
			</li>
			<li>
				<a href="<?=base_url()?>auth/login"> > LOGGA IN</a>
			</li>
			<?php }
			else{ 
				if($this->session->userdata('group_id') == 2){
				if(check_ads_limit()){?>
				<li>
					<a href="<?=base_url()?>user/addproduct"> > Lägg till ny produkt</a>
				</li>
                <li>
					<a href="<?=base_url()?>user/add_product_csv"> > Upload products by csv</a>
				</li>
				<?php
				}else{?>
				<li>
					<a href="#" class="adslimit"> > Lägg till ny produkt</a>
				</li>	
				<?php
				}?>
				<li>
					<a href="<?=base_url()?>user/viewproduct"> > Visa alla produkter</a>
				</li>
				<li>
					<a href="<?=base_url()?>user/newsboard"> > Lägg till nyheter</a>
				</li>
				<li>
					<a href="<?=base_url()?>user/viewinvoice"> > Visa faktura</a>
				</li>
			<?php
				}else{?>
			<li>
				<a href="<?=base_url()?>product/wishlist"> > MIN ÖNSKELISTA</a>
			</li>		
			<?php }?>	
			<li>
				<a href="<?=base_url()?>auth/edit_profile"> > Mitt Konto</a>
			</li>
			
			<?php
			}?>
		</ul>
	</div><!--mans_cata-->
	<div class="mans_cata">
		<h4>SÅ FUNGERAR DET</h4>
		<ul>
			<?=static_pages_list()?>
		</ul>
	</div><!--mans_cata-->
	<div class="mans_cata">
		<h4>FÖLJ OSS</h4>
		<ul>
			<li>
                <a href="https://www.facebook.com/salefinder.se" target="_blank">> FACEBOOK</a>
			</li>
			<li>
				<a href="#">> TWITTER</a>
			</li>
			<li>
				<a href="#">> YOUTUBE</a>
			</li>
		</ul>
	</div><!--mans_cata-->
</div><!--catagari-->