<?php
	if ( isset($_POST['checkout']['id']) && isset($_SESSION['user']['id']) ) {
	    require_once '../Util/CartManager.php';
	    $cartManager = new \App\Util\CartManager();

	   	$cartManager->checkout($_SESSION['user']['id'], $_POST['checkout']['id']);
	}
?>