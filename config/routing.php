<?php

$routing = [
    '/' => [
        'view' => 'basic/homepage.php',
        'title' => 'Strona główna',
    ],
    'logowanie' => [
        'view' => 'basic/login.php',
        'title' => 'Logowanie',
        'navbar' => false,
    ],
    'rejestracja' => [
        'view' => 'basic/register.php',
        'title' => 'Rejestracja',
        'navbar' => false,
    ],
    'kontakt' => [
        'view' => 'basic/kontakt.php',
        'title' => 'Kontakt',
    ],
    'wydarzenia' => [
        'view' => 'events/list.php',
        'title' => 'Wydarzenia',
        'before_header' => 'Service/add_event_to_cart.php',
    ],
    'koszyk' => [
        'view' => 'cart/details.php',
        'title' => 'Koszyk',
        'before_header' => 'Service/remove_from_cart.php',
    ],
    'logout' => [
        'view' => 'basic/logout.php',
        'title' => 'Wylogowanie',
    ],
    'zamowienia' => [
        'view' => 'order/lista.php',
        'title' => 'Zamowienia',
        'before_header' => 'Service/checkout.php',
    ],
    'dodawanie-wydarzen' => [
        'view' => 'events/new_events.php',
        'title' => 'Dodawanie wydarzeń',

    ],
    'crud-panel' => [
        'view' => 'admin_panel/crud_panel.php',
        'title' => 'Panel CRUD',
    ],
    'crud-panel-edycja' => [
        'view' => 'admin_panel/edit_user.php',
        'title' => 'Panel CRUD - Edycja',
    ],
];