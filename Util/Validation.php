<?php

namespace App\Util;

class Validation
{
    public function validate(array $validation, array $data): ?array
    {
        $errors = [];
        foreach ($validation as $field => $rule) {
            if ( !isset($data[$field]) ) {
                $errors['main'] = 'Brak wymaganych pól';
            }

            if ( isset($rule['min']) && strlen($data[$field]) < $rule['min'] ) {
                $errors[$field] = 'Minimalna długość tekstu to '.$rule['min'];
            }

            if ( isset($rule['max']) && strlen($data[$field]) > $rule['max'] ) {
                $errors[$field] = 'Maksymalna długość tekstu to '.$rule['max'];
            }
        }

        if ( count($errors) === 0 ) {
            return null;
        }

        return $errors;
    }
}