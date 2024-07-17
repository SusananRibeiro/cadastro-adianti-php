<?php

use Adianti\Database\TCriteria;
use Adianti\Database\TFilter;
use Adianti\Database\TRepository;
use Adianti\Database\TTransaction;

class ClientExists {
    public static function hasSales($clientId) {
        TTransaction::open('sample');
        
        $repository = new TRepository('Venda');
        $criteria = new TCriteria();
        $criteria->add(new TFilter('cliente_id', '=', $clientId));
        
        $count = $repository->count($criteria);
        TTransaction::close();
        
        return $count > 0;
    }
}

?>