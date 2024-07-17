<?php 
use Adianti\Database\TCriteria;
use Adianti\Database\TFilter;
use Adianti\Database\TRepository;
use Adianti\Database\TTransaction;

class ProductExists {
    public static function hasSales($productId) {
        TTransaction::open('sample');
        
        $repository = new TRepository('Venda');
        $criteria = new TCriteria(); // é uma classe utilizada para construir critérios de seleção em consultas SQL no contexto do Adianti Framework
        $criteria->add(new TFilter('produto_id', '=', $productId));
        
        $count = $repository->count($criteria);
        TTransaction::close();
        
        return $count > 0;
    }
}

?>