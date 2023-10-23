<?php

namespace App\Util;

class Repository
{
    private $class;
    private $manager;
    private $database;

    /**
     * Repository constructor.
     *
     * @param Manager $manager
     * @param string $class
     * @param string $namespace
     * @param string|null $database
     * @throws \Exception
     */
    public function __construct(Manager $manager, string $class, string $namespace = 'App\Entity', ?string $database = null)
    {
        $this->manager = $manager;

        if ( $database !== null ) {
            $this->database = $database;
        } else {
            $this->database = lcfirst($class);
        }

        $class = ucfirst($class);
        $this->requireFile($class, $namespace);

        if ( $namespace[0] !== '\\' ) {
            $namespace = '\\'.$namespace;
        }

        if ( $namespace[ strlen($namespace)-1 ] !== '\\' ) {
            $namespace .= '\\';
        }

        $this->class = $namespace.$class;

        try {
            $instance = new $this->class();

        } catch (\Exception $e) {
            throw new \Exception('Wystąpił błąd podczas tworzenia klasy "'.$this->class.'".');
        }
    }

    /**
     * Pobiera wszystkie wartości z bazy danych i zwracania je jako obiekty
     *
     * @return array
     * @throws \ReflectionException
     */
    public function findAll(): array
    {
        $results = $this->manager
            ->get()
            ->query('SELECT * FROM '.$this->database)
            ->fetch_all(MYSQLI_ASSOC);

        $resp = [];
        foreach ($results as $result)
        {
            $resp[] = $this->createObject($result);
        }

        return $resp;
    }

    /**
     * Zapisywanie / updatowanie danych w bazie
     *
     * @param $object
     * @return bool|\mysqli_result
     * @throws \ReflectionException
     */
    public function save($object)
    {
        if ( is_array($object) ) {
            $object = $this->createObject($object);
        }

        $fields = $this->class::getFieldsMap();
        $values = [];

        foreach ($fields as $key => $data) {
            $name = $data;
            if ( !is_array($data) ) {
                $key = $data;
            } else {
                $name = $data['name'];
            }

            $getter = 'get'.ucfirst($key);
            if ( method_exists($object, $getter) ) {
                $value = $object->$getter();

                if ( $value instanceof \DateTime ) {
                    $value = $value->format('Y-m-d H:i:s');
                }

                if ( $value !== null ) {
                    $values[$name] = $value;

                } elseif ( is_array($data) && isset($data['defaultValue']) ) {

                    switch ( $data['defaultValue'] ) {
                        case 'current_date':
                            $values[$name] = (new \DateTime())->format('Y-m-d H:i:s');
                            break;

                        case 'user':
                            $values[$name] = $_SESSION['user']['id'];
                            break;

                        default:
                            $values[$name] = $data['defaultValue'];
                    }
                }
            }
        }

        if ( $object->getId() !== null ) {
            $where = '';
            $query = 'UPDATE '. $this->database.' SET ';

            foreach ($values as $field => $value) {
                if ( isset($fields[$field]['autoincrement']) && $fields[$field]['autoincrement'] === true ) {
                    $where = $field. ' = '. $value;
                    continue;
                }

                $query .= $field.' = "'.$value.'", ';
            }

            if ( $where === '' ) {
                $where = 'id = '.$object->getId();
            }

            $query = substr($query, 0, strlen($query)-2). ' WHERE '.$where;

        } else {
            $query = 'INSERT INTO '.$this->database.' ('.implode(', ', array_keys($values)).' ) VALUES ( "'. implode('","', $values).'")';
        }

        return $this->manager
            ->get()
            ->query($query);
    }

    // -----------------------------------------------------------------------------------------------------------------
    /**
     * Automatyczne dołączanie pliku z klasą
     *
     * @param string $class
     * @param string $path
     * @throws \Exception
     */
    private function requireFile(string $class, string $path)
    {
        $path = str_replace('\\', '/', $path);
        if ( $pos = strpos($path, '/') ) {
            $path = substr($path, $pos + 1);
        }

        $path .= '/'.$class.'.php';
        if ( !file_exists($path) ) {
            throw new \Exception('Podany plik "'.$path.'" nie został znaleziony');
        }

        require_once $path;
    }

    /**
     * Tworzenie obietków (konwersja tablicy na obiekt)
     *
     * @param array $data
     * @return mixed
     * @throws \ReflectionException
     */
    private function createObject(array $data)
    {
        $object = new $this->class();
        $reflection = new \ReflectionObject($object);

        foreach ($data as $field => $value) {
            if ( $value == null ) {
                continue;
            }

            if ( !$reflection->hasProperty($field) ) {
                $field = $this->convertToCamelcase($field);
            }

            $setter = 'set'.ucfirst($field);
            if ( method_exists($object, $setter) ) {
                $object->$setter($value);

            } elseif ( $reflection->hasProperty($field) ) {
                $field = $reflection->getProperty($field);

                if ( $field->isPrivate() === true ) {
                    $field->setAccessible(true);
                }

                $field->setValue($object, $value);
            }

        }

        return $object;
    }

    /**
     * Konwertowanie z _ na camelcase, np. z created_at na createdAt
     *
     * @param string $field
     * @return string
     */
    private function convertToCamelcase(string $field): string
    {
        return lcfirst( str_replace('_', '', ucwords($field, '_')) );
    }
}