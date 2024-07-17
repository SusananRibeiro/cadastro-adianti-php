<?php

use Adianti\Database\TCriteria;
use Adianti\Database\TFilter;
use Adianti\Database\TRecord;
use Adianti\Database\TRepository;
use Adianti\Database\TTransaction;

class Usuario extends TRecord {

    const TABLENAME = 'usuarios';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'serial'; 

    public function __construct($id = null) {
        parent::__construct($id);

        parent::addAttribute('id');
        parent::addAttribute('nome_usuario');
        parent::addAttribute('senha');
        
    }

}

?>