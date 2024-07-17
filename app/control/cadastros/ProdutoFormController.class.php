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

class ProdutoFormController extends TPage {
    private $form;

    public function __construct() {
        parent::__construct();

        $this->form = new BootstrapFormBuilder('form_produto'); 
       
        $id = new THidden('id');
        $nomeProduto = new TEntry('nome_produto');
        $valor = new TEntry('valor');
        $this->form->addFields([new TLabel('')], [$id]); // precisa dessa parte para poder editar mesmo usando "THidden" 
        $this->form->addFields([new TLabel('Nome:')], [$nomeProduto]);
        $this->form->addFields([new TLabel('Valor:')], [$valor]);
        $this->form->addAction('Salvar', new TAction(array($this, 'onSave')), 'fa:save green');
        $this->form->addAction('Limpar', new TAction(array($this, 'onClear')), 'fa:eraser orange');
        parent::add($this->form);
        
    }

    public function onClear() {
        $this->form->clear();
    }

    public function onEdit($param) {
        try {
            if (isset($param['id'])) {
                TTransaction::open('sample');
                $produto = new Produto($param['id']);
                $produto->valor = ConvertCurrency::toBRFormat($produto->valor);
                $this->form->setData($produto);
                TTransaction::close();
            } else {
                $this->form->clear();
            }
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }


     // Atualizando o Método onSave para Usar a Classe DateHelper e ConvertCurrency
     public function onSave() {
        try {
            TTransaction::open('sample');
            $data = $this->form->getData();

            if(empty($data->nome_produto)) {
                throw new Exception('Nome do produto é obrigatório.');
            } else if(!preg_match("/^[A-Za-z ]{7,}$/", $data->nome_produto)) {
                throw new Exception('Nome do produto inválido, apenas letras.');
            } else if(empty($data->valor)) {
                throw new Exception('O valor do produto é obrigatório');
            } else if (!preg_match("/^\d+(,\d+)?$/", $data->valor)) {
                throw new Exception('Valor inválido.');
            } 
            $is_new = empty($data->id);  // Verifica se o ID está vazio, indicando um novo registro      
            $data->valor = ConvertCurrency::toUSFormat($data->valor);   
            if ($is_new) {
                $produto = new Produto();
            } else {
                $produto = new Produto($data->id);
            } 
            $produto->nome_produto = $data->nome_produto;
            $produto->valor = $data->valor;
            $produto->store();
            $this->form->setData($produto);
            if ($is_new) {
                $message = "Venda incluída com sucesso!!!";
            } else {
                $message = "Venda atualizada com sucesso!!!";
            }

            new TMessage('info', 'Registro salvo com sucesso');
            AdiantiCoreApplication::loadPage('ProdutoListController');
            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
}

?>