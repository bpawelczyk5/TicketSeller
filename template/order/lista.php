<?php
	require_once '../Util/CartManager.php';
	$cartManager = new \App\Util\CartManager();

    if (!isset($_SESSION['user']['id'])) 
    {
        header("Location: /");
        exit;
    } 

	$orders = $cartManager->getOrders($_SESSION['user']['id']); 

    if ($orders !== null)
    {
        usort($orders, function ($a, $b) 
        {
            return $b['id'] - $a['id'];
        });
    }
    else
    {
        echo '<center><p id="empty">Nie masz żadnych zamówień.</p></center>';
    }
?>

<center style="margin-top: 200px;">

<?php if ($orders !== null):?>
    
    <?php foreach ($orders as $order): ?>
        <table id="tabela" style="margin-top: 200px;">
            <tr>
                <th>Dane klienta</th>
            </tr>
                
            <tr>
                <td><span><?php echo $_SESSION['user']['login']; ?></span></td>
            </tr>

            <tr>
                <th></th>
                <th> Nazwa wydarzenia </th>
                <th> Lokalizacja </th>
                <th> Data </th>
                <th> Ilość </th>
                <th> Cena/szt </th>
                <th> Cena </th>
            </tr>

            <?php foreach ($order['positions'] as $position): ?>
                <tr>
                    <td><img width="100px" height="100px" src="<?php echo $position['image'];?>"></td>
                    <td><?php echo $position['event_name']; ?></td>
                    <td><?php echo $position['location_name']; ?></td>
                    <td><?php echo $position['date']; ?></td>
                    <td><?php echo $position['amount']; ?></td>
                    <td><?php echo $position['price']; ?></td>
                    <td><?php echo number_format($position['amount'] * $position['price'], 2, '.', ''); ?></td>
                </tr>
            <?php endforeach; ?>

            <tr>
                <th>ID zamówienia</th>
                <th colspan="2"> Ilość produktów </th>
                <th colspan="3"> Cena całkowita </th>
            </tr>
          
            <tr>
                <td><?php echo $order['id'] ?> </td>
                <td colspan="2"> <?php echo $order['total_amount'] ?> </td>
                <td colspan="3"> <?php echo $order['total_price'] ?> </td>
            </tr>
        </table> 
    <?php endforeach; ?>
<?php endif; ?>

</center>

