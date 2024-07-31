<?php

use Adianti\Database\TRecord;

class UsuarioUnidade extends TRecord {
    const TABLENAME = 'system_user_unit';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'serial'; 

    // id, system_user_id, system_unit_id
    public function __construct($id = null) {
        parent::__construct($id);

        parent::addAttribute('id'); 
        parent::addAttribute('system_user_id'); 
        parent::addAttribute('system_unit_id'); 
        
    }
}

?>