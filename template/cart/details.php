<?php
require_once '../Util/CartManager.php';
use App\Util\CartManager;

if (isset($_SESSION['user']['id'])) {
    $cartManager = new CartManager();
    $details = $cartManager->getCartDetails($_SESSION['user']['id']);
} else {
    header('Location: /logowanie');
    exit();
}
?>

<center>
    <?php if (empty($details['positions'])): ?>
        <p id="empty">Koszyk jest pusty.</p>
    <?php else: ?>
        <table id="tabela" style="margin-top: 200px;">
            <tr>
                <th></th>
                <th>Nazwa wydarzenia</th>
                <th>Lokalizacja</th>
                <th>Data</th>
                <th>Ilość</th>
                <th>Cena/szt</th>
                <th>Cena</th>
            </tr>

            <?php foreach ($details['positions'] as $position): ?>
                <tr>
                    <td><img width="100px" height="100px" src="<?php echo $position['image']; ?>"></td>
                    <td><?php echo $position['event_name']; ?></td>
                    <td><?php echo $position['location_name']; ?></td>
                    <td><?php echo $position['date']; ?></td>
                    <td><?php echo $position['amount']; ?></td>
                    <td><?php echo $position['price']; ?></td>
                    <td><?php echo number_format($position['amount'] * $position['price'], 2, '.', ''); ?></td>
                    <td>
                        <form name="position_remove" method="POST">
                            <input type="hidden" name="position_remove[id]" value="<?php echo $position['id']; ?>">
                            <?php if (isset($position['amount'])): ?>
                                <button id="trash" type="submit"><i id="ikona_trash" class="fas fa-trash-alt"></i></button>
                            <?php endif; ?>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>

            <tr>
                <th colspan="2">Ilość produktów</th>
                <th colspan="3">Cena całkowita</th>
            </tr>
            <tr>
                <td colspan="2"><?php echo $details['total_amount'] ?? 0; ?></td>
                <td colspan="3"><?php echo $details['total_price'] ?? 0; ?></td>

                <form name="checkout" action="/zamowienia" method="POST">
                    <input type="hidden" name="checkout[id]" value="<?php echo $details['id']; ?>">
                    <?php if (isset($position['amount'])): ?>
                        <td><button id="potwierdz" type="submit">Zamów</button></td>
                    <?php endif; ?>
                </form>
            </tr>
        </table>
    <?php endif; ?>
</center>

