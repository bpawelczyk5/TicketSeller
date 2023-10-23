<?php

require_once '../Util/CartManager.php';

use App\Util\CartManager;

if (isset($_POST['event_order']) && isset($_SESSION['user']['id'])) {
    $cartManager = new CartManager();

    $cartManager->addEvent($_SESSION['user']['id'], $_POST['event_order']['id'], $_POST['event_order']['amount']);

    header('Location: /wydarzenia');
    exit();
}
?>