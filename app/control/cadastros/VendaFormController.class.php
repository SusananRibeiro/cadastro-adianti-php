<?php 

use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Core\AdiantiCoreApplication;
use Adianti\Database\TTransaction;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\THidden;
use Adianti\Widget\Wrapper\TDBCombo;
use Adianti\Widget\Wrapper\TQuickForm;

class VendaFormController extends TPage {
    private $form;
    
    public function __construct() {
        parent::__construct();
        $this->form = new TQuickForm('form_vendas');
        $this->form->style = 'width: 100%';
        
        $id = new THidden('id');
        $cliente_id = new TDBCombo('cliente_id', 'sample', 'Cliente', 'id', 'nome_cliente');
        $produto_id = new TDBCombo('produto_id', 'sample', 'Produto', 'id', 'nome_produto');
        $quantidade = new TEntry('quantidade');
        $total = new TEntry('total');
        $dataVenda = new TEntry('data_venda');
        // $dataVenda = new TDate('data_venda');

        $this->form->addQuickField('', $id, 50);
        $this->form->addQuickField('Cliente:', $cliente_id, 200);
        $this->form->addQuickField('Produto:', $produto_id, 200);
        $this->form->addQuickField('Quantidade:', $quantidade, 200);
        $this->form->addQuickField('Total:', $total, 200);
        $this->form->addQuickField('Data:', $dataVenda, 200);
        
        $this->form->addQuickAction('Salvar', new TAction(array($this, 'onSave')), 'fa:save green');
        $this->form->addQuickAction('Limpar', new TAction(array($this, 'onClear')), 'fa:eraser orange');

        parent::add($this->form);
    }
    
    public function onClear() {
        $this->form->clear();
    }

    public function onEdit($param) {
        try {
            if (isset($param['id'])) {
                TTransaction::open('sample');
                $venda = new Venda($param['id']);
                $venda->data_venda = ConvertDate::toBRFormat($venda->data_venda);
                $venda->total = ConvertCurrency::toBRFormat($venda->total);
                $this->form->setData($venda);
                TTransaction::close();
            } else {
                $this->form->clear();
            }
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public function onSave() {
        try {
            TTransaction::open('sample');
            $data = $this->form->getData();
            // Validações de dados
            if(empty($data->cliente_id)) {
                throw new Exception('O cliente é obrigatório.');
            } else if(empty($data->produto_id)) {
                throw new Exception('O produto é obrigatório.');
            } else if (empty($data->quantidade)) {
                throw new Exception('A quantidade é obrigatória.');
            } else if ($data->quantidade <= 0) {
                throw new Exception('A quantidade deve ser maior que zero.');
            } else if (!preg_match("/^\d+$/", $data->quantidade)) {
                throw new Exception('Quantidade inválida, somente números.');
            } else if (empty($data->total)) {
                throw new Exception('O total é obrigatório.');
            } else if (!preg_match("/^\d+(,\d+)?$/", $data->total)) {
                throw new Exception('Total inválido.');
            } else if (!preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $data->data_venda)) {
                throw new Exception('Data inválida. A data deve estar no formato dd/mm/aaaa');
            }

            $data->data_venda= ConvertDate::toUSFormat($data->data_venda);
            $data->total = ConvertCurrency::toUSFormat($data->total);
            $is_new = empty($data->id);
            if ($is_new) {
                $venda = new Venda();
            } else {
                $venda = new Venda($data->id);
            } 
            $venda->cliente_id = $data->cliente_id;
            $venda->produto_id = $data->produto_id;
            $venda->quantidade = $data->quantidade;
            $venda->data_venda = $data->data_venda;
            $venda->total = $data->total;
            $venda->store();
            $this->form->setData($venda);   
            if ($is_new) {
                $message = "Venda incluída com sucesso!!!";
            } else {
                $message = "Venda atualizada com sucesso!!!";
            }       
            new TMessage('info', $message);  
            AdiantiCoreApplication::loadPage('VendaListController');
            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }


}


?>