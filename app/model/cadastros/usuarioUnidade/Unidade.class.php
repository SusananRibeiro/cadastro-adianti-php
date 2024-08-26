<?php

use Adianti\Database\TRecord;

class Unidade extends TRecord {
    
    const TABLENAME = 'system_unit';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'serial'; 

    public function __construct($id = null) {
        parent::__construct($id);

        // id,name,connection_name,custom_code
        parent::addAttribute('id'); 
        parent::addAttribute('name'); 
        parent::addAttribute('connection_name'); 
        
    }
}

?>