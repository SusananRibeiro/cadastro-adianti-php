<?php 

use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Core\AdiantiCoreApplication;
use Adianti\Database\TTransaction;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\THidden;
use Adianti\Widget\Form\TLabel;
use Adianti\Wrapper\BootstrapFormBuilder;

class ClienteFormController extends TPage {

    private $form;

    public function __construct() {
        parent::__construct();

        $this->form = new BootstrapFormBuilder('form_cliente'); 
       
        $id = new THidden('id');
        $nomeCleinte = new TEntry('nome_cliente');
        $telefone = new TEntry('telefone');
        $cep = new TEntry('cep');

        $this->form->addFields([new TLabel('')], [$id]);
        $this->form->addFields([new TLabel('Nome:')], [$nomeCleinte]);
        $this->form->addFields([new TLabel('Telefone:')], [$telefone]);
        $this->form->addFields([new TLabel('CEP:')], [$cep]);
        $this->form->addAction('Salvar', new TAction(array($this, 'onSave')), 'fa:save green');
        $this->form->addAction('Limpar', new TAction(array($this, 'onClear')), 'fa:eraser orange');
        parent::add($this->form);
        
    }
    
    public function onClear() {
        $this->form->clear();
    }

    public function onEdit($param) {
        try {
            if(isset($param['key'])) {
                TTransaction::open('sample');
                $data = new Cliente($param['key']);
                $this->form->setData($data);
                TTransaction::close();

            } else {
                $this->form->clear();
            }

        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }

    }

    public function onSave() {
        try {
            TTransaction::open('sample');
            $data = $this->form->getData();
            if(empty($data->nome_cliente)) {
                throw new Exception('Nome do cliente é obrigatório.');
            } else if(!preg_match("/^[A-Za-z ]{7,}$/", $data->nome_cliente)) {
                throw new Exception('Nome do cliente inválido, apenas letras.');
            } else if(empty($data->telefone)) {
                throw new Exception('O telefone do cliente é obrigatório');
            } else if (!preg_match("/^[0-9]{11}$/", $data->telefone)) {
                throw new Exception('Telefone inválido, somente números.');
            } else if (empty($data->cep)) {
                throw new Exception('O CEP do cliente é obrigatório.');
            } else if (!preg_match("/^[0-9]{8}$/", $data->cep)) {
                throw new Exception('CEP inválido, somente números.');
            }
            $is_new = empty($data->id);
            if ($is_new) {
                $cliente = new Cliente();
            } else {
                $cliente = new Cliente($data->id);
            }      
            $cliente->nome_cliente = $data->nome_cliente;
            $cliente->telefone = $data->telefone;
            $cliente->cep = $data->cep;
            $cliente->store();
            
            $this->form->setData($cliente);
            if ($is_new) {
                $message = "Venda incluída com sucesso!!!";
            } else {
                $message = "Venda atualizada com sucesso!!!";
            }       
            new TMessage('info', $message);  
            AdiantiCoreApplication::loadPage('ClienteListController');
            TTransaction::close();

        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }

}

?>