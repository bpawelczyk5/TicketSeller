<?php

namespace App\Util;

require_once '../Util/Manager.php';
use App\Util\Manager;

class CartManager
{
    private $manager;

    public function __construct()
    {
        // Pobieramy managera i dopisujemy do zmiennej prywatnej
        $manager = new Manager();
        $this->manager = $manager;
    }

    /**
     * Dodawanie wydarzeń do koszyka
     *
     * @param int $userId
     * @param int $eventId
     * @param int $amount
     * @return bool
     */

    public function removePosition(int $userId, int $positionId): bool
    {
        $eventPosition = $this->manager
            ->get()
            ->query('SELECT c.id as cart_id, ce.id, ce.amount, ce.price, e.price FROM cart_event ce
                INNER JOIN cart c ON c.id = ce.cart_id
                INNER JOIN events e ON e.id = ce.event_id
                WHERE c.state = 200 AND ce.id = '.$positionId.' AND c.user_id = '.$userId)
            ->fetch_all(MYSQLI_ASSOC);

        if (isset($eventPosition[0]['id'])) {
            $result = $this->manager
                ->get()
                ->query('UPDATE cart_event SET amount = amount - 1 WHERE id = '.$positionId);

            if ($result === true) {
                $cartId = $eventPosition[0]['cart_id'];
                $updatedAmount = $eventPosition[0]['amount'] - 1;
                
                if ($updatedAmount === 0) {
                    $deleteResult = $this->manager
                        ->get()
                        ->query('DELETE FROM cart_event WHERE id = '.$positionId);

                    if ($deleteResult !== true) {
                        return false;
                    }
                }

                return $this->manager
                    ->get()
                    ->query('UPDATE cart SET amount = amount - 1, price = price - '.$eventPosition[0]['price']
                        .' WHERE id = '.$cartId);
            }
        }

        return false;
    }

     

    public function addEvent(int $userId, int $eventId, int $amount): bool
    {
        $cart = $this->getCart($userId, true);
        $eventPrice = $this->getEventPrice($eventId);

        if ($eventPrice !== null && $amount > 0 && $amount <= 10) {
            $existingPosition = $this->manager
                ->get()
                ->query('SELECT * FROM cart_event WHERE cart_id = '.$cart['id'].' AND event_id = '.$eventId)
                ->fetch_assoc();

            if ($existingPosition !== null) {
                $newAmount = $existingPosition['amount'] + $amount;
                $newPrice = $existingPosition['price'] + ($amount * $eventPrice);

                $result = $this->manager
                    ->get()
                    ->query('UPDATE cart_event SET amount = '.$newAmount.', price = '.$newPrice.' WHERE id = '.$existingPosition['id']);

                if ($result === true) {
                    return $this->manager
                        ->get()
                        ->query('UPDATE cart SET amount = amount + '.$amount.', price = price + '.($amount * $eventPrice)
                            .' WHERE id = '.$cart['id'].' AND state = 200');
                }
            } else {
                $result = $this->manager
                    ->get()
                    ->query('INSERT INTO cart_event (cart_id, event_id, amount, price) VALUES ('
                        .$cart['id'].', '.$eventId.', '.$amount.', '. $eventPrice. ')'
                    );

                if ($result === true) {
                    return $this->manager
                        ->get()
                        ->query('UPDATE cart SET amount = amount + '.$amount.', price = price + '.($amount * $eventPrice)
                            .' WHERE id = '.$cart['id'].' AND state = 200');
                }
            }
        }

        return false;
    }



    /**
     * @param int $userId
     * @return array|null
     */
    public function getCartData(int $userId): ?array
    {
        return $this->getCart($userId);
    }

    /**
     * @param int $userId
     * @return array
     */

    public function getCartDetails(int $userId): array
    {
        $temp = $this->manager
            ->get()
            ->query('SELECT c.id as cart_id, c.amount AS total_amount, c.price AS total_price, ce.id AS id, ce.amount AS amount, 
                ce.price AS price, e.price, e.image AS image, e.date AS date, e.name AS event_name, l.name AS location_name
                FROM cart c
                INNER JOIN cart_event ce ON ce.cart_id = c.id
                INNER JOIN events e ON ce.event_id = e.id
                INNER JOIN locations l ON e.location_id = l.id
                WHERE c.user_id = '.$userId.' AND c.state = 200')
            ->fetch_all(MYSQLI_ASSOC);
        
        if (count($temp)) {
            $result = [
                'id' => $temp[0]['cart_id'],
                'total_amount' => $temp[0]['total_amount'],
                'total_price' => $temp[0]['total_price'],
                'positions' => [],
            ];
        
            foreach ($temp as $position) {
                unset($position['total_amount'], $position['total_price']);
                $result['positions'][] = $position;
            }

            return $result;
        }

        return [];
    }

      public function checkout(int $userId, int $cartId): bool
    {
        return $this->manager
            ->get()
            ->query('UPDATE cart SET state = 100 WHERE id= '.$cartId.' AND user_id ='.$userId);
    }

    public function getOrders(int $userId): ?array
    {
        $temp = $this->manager
            ->get()
            ->query('SELECT c.id as cart_id, c.amount AS total_amount, c.price AS total_price, ce.id AS id, ce.amount AS amount, 
                ce.price AS price, e.price, e.image AS image, e.date AS date, e.name AS event_name, l.name AS location_name
                FROM cart c
                INNER JOIN cart_event ce ON ce.cart_id = c.id
                INNER JOIN events e ON ce.event_id = e.id
                INNER JOIN locations l ON e.location_id = l.id
                WHERE c.user_id = '.$userId.' AND c.state = 100')
            ->fetch_all(MYSQLI_ASSOC);

            if (count($temp)) {

                foreach ($temp as $position) {
                    $cartId = $position['cart_id'];

                    if (!isset($result[$cartId])) {
                        $result[$cartId] = [
                                'id' => $position['cart_id'],
                                'total_amount' => $position['total_amount'],
                                'total_price' => $position['total_price'],
                        ];
                        
                    }

                    unset($position['total_amount'], $position['total_price']);
                    $result[$cartId]['positions'][] = $position;
                }

                return $result;
            }

        return null;
    }

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Zwraca ID koszyka użytkownika
     *
     * @param int $userId
     * @param bool $create
     * @return null|array
     */
    private function getCart(int $userId, bool $create = false): ?array
    {
        // Wyszukiwanie, czy istnieje "otwarty" koszyk dla danego użytkownika
        $cart = $this->manager
            ->get()
            ->query('SELECT * FROM cart WHERE user_id = '.$userId.' AND state = 200')
            ->fetch_assoc();

        // Jeżeli koszyk nie istnieje, to tworzymy nowy
        if ( $cart === null && $create === true ) {
            $cart = $this->manager
                ->get()
                ->query('INSERT INTO cart (user_id, amount, price, state) VALUES ('.$userId.', 0, 0.00, 200)');

            if ( $cart === true ) { // Gdy uda się utworzyć koszyk, pobieramy jego ID
                return [
                    'id' => $this->manager->get()->insert_id
                ];
            }
        }

        return $cart;
    }

    /**
     * Zwraca cene książki jeżeli takowa znajduje się w bazie, w innym wypadku zwraca null
     *
     * @param int $id
     * @return float|null
     */
    private function getEventPrice(int $id): ?float
    {
        $event = $this->manager
            ->get()
            ->query('SELECT * FROM events WHERE id = '.$id)
            ->fetch_assoc();

        if ( $event !== null ) {
            return $event['price'];
        }

        return null;
    }
}