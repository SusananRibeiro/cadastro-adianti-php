<?php

use Adianti\Database\TCriteria;
use Adianti\Database\TFilter;
use Adianti\Database\TRepository;
use Adianti\Database\TTransaction;

class UserExists {
    public static function isUsernameUnique($username, $id = null) {
        TTransaction::open('permission');
        
        $repository = new TRepository('Usuario');
        $criteria = new TCriteria();
        $criteria->add(new TFilter('name', '=', $username));
        
        if ($id) {
            // Exclui o próprio usuário da verificação
            $criteria->add(new TFilter('id', '!=', $id));
        }
        
        $count = $repository->count($criteria);
        TTransaction::close();
        
        return $count == 0;
    }
}


?>