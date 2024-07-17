<?php 
use Adianti\Database\TRecord;

class Cliente extends TRecord {

    const TABLENAME = 'clientes';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'serial'; 

    public function __construct($id = null) {
        parent::__construct($id);

        parent::addAttribute('id');
        parent::addAttribute('nome_cliente');
        parent::addAttribute('telefone');
        parent::addAttribute('cep');
        
    }

}


?>