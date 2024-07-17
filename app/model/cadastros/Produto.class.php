<?php 
use Adianti\Database\TRecord;

class Produto extends TRecord {

    const TABLENAME = 'produtos';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'serial'; 

    public function __construct($id = null) {
        parent::__construct($id);

        parent::addAttribute('id');
        parent::addAttribute('nome_produto');
        parent::addAttribute('valor');
        
    }

}

?>