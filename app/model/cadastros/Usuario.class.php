<?php

use Adianti\Database\TRecord;

class Usuario extends TRecord {

    const TABLENAME = 'system_users';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'serial'; 

    public function __construct($id = null) {
        parent::__construct($id);

        parent::addAttribute('id'); 
        parent::addAttribute('name'); 
        parent::addAttribute('login'); 
        parent::addAttribute('password'); 
        parent::addAttribute('email'); 
    }

}

?>