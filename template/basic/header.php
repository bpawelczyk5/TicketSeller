<?php
	require_once '../Util/CartManager.php';
	use App\Util\CartManager;
?>

<div id="navbar">
	<div id="div_logo">
		<a href="/"><img id="logo" src="/img/event_logo.png"  width="60px"></a>
	</div>	
  	<div id="div_linki">
	  	<a href="/" id="home_link" class="linki">Home</a>
	  	<a href="/wydarzenia" class="linki">Wydarzenia</a>
	  	
	  	<?php if (isset($_SESSION['user']['id'])) 
		{ ?>	
	  		<a href="/zamowienia" class="linki">Zamówienia</a>
	  	<?php } ?>

	  	<a href="/kontakt" class="linki">Kontakt</a>
	</div>

		<?php if (isset($_SESSION['user']['type_id']) && ($_SESSION['user']['type_id'] == 1 || $_SESSION['user']['type_id'] == 2)) 
		{
			echo '<a href="/dodawanie-wydarzen" class="linki">Dodaj wydarzenie</a>';
		} ?>

		<?php if (isset($_SESSION['user']['type_id']) && ($_SESSION['user']['type_id'] == 1))
		{
			echo '<a href="/crud-panel" class="linki">Panel administracyjny</a>';
		} ?>

	<div id="menu_buttons">
	  	<a href="/koszyk">
	    	<div id="cart_price" style="float: right;">
	    		<?php

		    		 if ( isset($_SESSION['user']['id']) )
		    		 {
		            	$cartManager = new CartManager();
		        
	                	$cart = $cartManager->getCartData($_SESSION['user']['id']);
		    		 }
	        	?>
	  			
	  			<div style=" float:right; position: relative; margin-bottom: 8px;">
	            	<i class="fas fa-shopping-cart" style="font-size: 1.5rem;"></i>
	            	<span style="font-size: 0.9rem; color: #fff; background: slategray; padding: 1px 2px 4px 2px; text-align: center;
	                border-radius: 50%; width: 13px; height: 12px; position:absolute; top: 0.7rem; left: 1.1rem;">
	                
	                	<?php echo ($cart['amount']) ?? '0'; ?>
	            	</span>
	        	</div>

	        	<?php echo ($cart['price']) ?? '0,00'; ?> zł
	   		</div>
		</a>
		
	<?php	
		if (isset($_SESSION['user']['id'])) 
		{ ?>

			<a href="/logout">
			<div style="margin-left:8px; position: relative; margin-bottom: 8px;">
	            <i class="fas fa-sign-out-alt" style="font-size: 1.65rem;"></i>
	    	</div>
		</a>
	<?php }
		else
		{ ?>
			<a href="/logowanie">
			<div style="margin-left:10px; position: relative; margin-bottom: 8px;">
	            <i id="ikona" class="fas fa-sign-in-alt" style="font-size: 1.65rem;"></i>
	    	</div>
		</a>
	<?php } ?>
		
	</div>			
</div>


