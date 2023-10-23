<?php

namespace App\Util;

class Manager
{
    // Przechowuje ilość stworzonych obiektóœ per konkretne połączenie
    private static $counter;

    // Przechowuje nasze połączenia z bazami danych (jako tablica - kluczem jest nazwa połączenia)
    private static $connections;

    // Nazwa połączenia konkretnego obiektu
    private $connection;

    /**
     * Manager constructor.
     *
     * Konstruktor wywołuje się podczas tworzenia nowego obiektu.
     * W tym konstruktorze nawiązujemy połączenia z bazami danych, jeżeli takie jeszcze nie istniały.
     * Liczymy ilość połączeń z tą samą bazą.
     *
     * @param string $connection nazwa połączenia, które chcemy nawiązać (domyślna wartość: default)
     * @param string $host adres serwera bazodanowego
     * @param string $database nazwa bazy danych
     * @param string $username użytkownik, którym logujemy się do bazy danych
     * @param string|null $password hasło
     * @param bool|false $throwException wartość true - "wyrzuca" błąd, gdy chcemy stworzyć połączenie o takiej samej nazwie
     * @throws \Exception
     */
    public function __construct(
        string $connection = 'default',
        string $host = 'localhost',
        string $database = 'eventsdb',
        string $username = 'root',
        ?string $password = null,
        bool $throwException = false
    ) {

        if ( !isset(self::$connections[ $connection ])) {

            $mysqli = new \mysqli($host, $username, $password, $database);
            if ($mysqli->connect_errno) {
                throw new \Exception('Błąd połączenia z bazą danych.', 500);
            }

            $mysqli->set_charset('utf8');

            self::$connections[$connection] = $mysqli;
            self::$counter[$connection] = 0;

        } elseif ( $throwException === true ) {
            throw new \Exception('Połączenie o takiej nazwie już istnieje');
        }

        self::$counter[$connection]++;
        $this->connection = $connection;
    }


    /**
     * Destruktor wykonuje się podczas niszczenia obiektów.
     * Niszcząc ostatni obiekt łączący się z konkretnym połączeniem, usuwamy je (połączenie).
     */
    public function __destruct()
    {
        self::$counter[$this->connection]--;

        if ( self::$counter[$this->connection] === 0 ) {
            self::$connections[$this->connection]->close();

            unset(self::$connections[$this->connection]);
        }
    }

    /**
     * Zwraca konkretny obiekt mysqli z połączeniem do bazy danych.
     * Połączenie jest wyszukiwane za pomocą nazwy.
     *
     * @param string $connection
     * @return \mysqli|null
     */
    public function get(string $connection = 'default'): ?\mysqli
    {
        if ( isset(self::$connections[ $connection ])) {
            return self::$connections[ $connection ];
        }

        return null;
    }

    /**
     * Wykonuje zapytanie SQL
     *
     * @param string $sql Wykonanie zapytania
     * @return bool|\mysqli_result Zwraca true przy powodzeniu lub objekt mysqli_result dla zapytań SELECT 
     */
    public function query(string $sql)
    {
        $result = $this->get()->query($sql);
        return $result;
    }

     /**
     * Pobiera id generowane we wcześniejszej operacji INSERT
     *
     * @return int|string Ostatnio wprowadzone id
     */
    public function getLastInsertId()
    {
        return $this->get()->insert_id;
    }

}


