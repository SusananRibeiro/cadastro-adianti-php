<?php

use Adianti\Database\TRecord;

class UsuarioUnidade extends TRecord {
    const TABLENAME = 'system_user_unit';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'serial'; 
    private $usuarios;
    private $unidades;

    // id, system_user_id, system_unit_id
    public function __construct($id = null) {
        parent::__construct($id);

        parent::addAttribute('id'); 
        parent::addAttribute('system_user_id'); 
        parent::addAttribute('system_unit_id'); 
        
    }

    // O Adianti já reconhece o método pelo nome
    public function get_usuarios() {
        if(empty($this->usuarios)) {
            $this->usuarios = new Usuario($this->system_user_id);
        }
        return $this->usuarios;
    }

    public function get_unidades() {
        if(empty($this->unidades)) {
            $this->unidades = new Unidade($this->system_unit_id);
        }
        return $this->unidades;
    }
}

?>