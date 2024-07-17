<?php

use Adianti\Database\TRecord;

class Venda extends TRecord {
    const TABLENAME = 'vendas';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'serial'; // "serial" ou "max"
    private $clientes;
    private $produtos;

    public function __construct($id = null) {
        parent::__construct($id);

        parent::addAttribute('id'); 
        parent::addAttribute('quantidade'); 
        parent::addAttribute('total'); 
        parent::addAttribute('data_venda'); 
        parent::addAttribute('cliente_id'); // chave estrangeira
        parent::addAttribute('produto_id'); // chave estrangeira
        
    }

    // O Adianti já reconhece o método pelo nome
    public function get_clientes() {
        if(empty($this->clientes)) {
            $this->clientes = new Cliente($this->cliente_id);
        }
        return $this->clientes;
    }

    public function get_produtos() {
        if(empty($this->produtos)) {
            $this->produtos = new Produto($this->produto_id);
        }
        return $this->produtos;
    }


}

?>