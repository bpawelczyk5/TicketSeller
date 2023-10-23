<?php

require_once '../Util/CartManager.php';
use App\Util\CartManager;

if ( isset($_POST['position_remove']['id']))
{
	$cartManager= new CartManager();
	$cartManager->removePosition($_SESSION['user']['id'], $_POST['position_remove']['id']);
} 

?>