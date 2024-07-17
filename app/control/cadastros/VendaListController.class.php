<?php 

use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Database\TCriteria;
use Adianti\Database\TRepository;
use Adianti\Database\TTransaction;
use Adianti\Widget\Container\THBox;
use Adianti\Widget\Container\TPanelGroup;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Datagrid\TDataGrid;
use Adianti\Widget\Datagrid\TDataGridAction;
use Adianti\Widget\Datagrid\TDataGridColumn;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Dialog\TQuestion;
use Adianti\Widget\Form\TButton;
use Adianti\Widget\Form\TForm;
use Adianti\Wrapper\BootstrapDatagridWrapper;

class VendaListController extends TPage {
    private $datagrid;
    private $pageNavigation;
    private $form;
    
    // Método construtor
    public function __construct() {
        parent::__construct();   
        $this->createDataGrid();     
        // Criar formulário
        $this->form = new TForm('form_list_venda');
        $new_button = new TButton('new');
        $new_button->setAction(new TAction(['VendaFormController', 'onEdit']), 'Novo');
        $new_button->setImage('fa:plus green');
        $this->form->addField($new_button);
        // Criar contêiner
        $hbox = new THBox();
        $hbox->add($new_button);
        $this->form->setFields([$new_button]);
        $this->form->add($hbox);
        $vbox = new TVBox();
        $vbox->style = 'width: 100%';
        $vbox->add($this->form);
        $vbox->add(TPanelGroup::pack('Lista de Vendas', $this->datagrid, $this->pageNavigation));
        parent::add($vbox);
    }

    // Método para criar o datagrid
    private function createDataGrid() {
        // Criar grade de dados
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid('list_vendas'));
        // Criar colunas
        $id = new TDataGridColumn('id', 'ID', 'center', 50);
        $cliente_id = new TDataGridColumn('clientes->nome_cliente', 'Cliente', 'center', 300);
        $produto_id = new TDataGridColumn('produtos->nome_produto', 'Produto', 'center', 300);
        $quantidade = new TDataGridColumn('quantidade', 'Qtde', 'center', 80);
        $total = new TDataGridColumn('total', 'Total', 'center', 100);
        $data = new TDataGridColumn('data_venda', 'Data', 'center', 100);
        // Adicionar colunas ao datagrid
        $this->datagrid->addColumn($id);
        $this->datagrid->addColumn($cliente_id);
        $this->datagrid->addColumn($produto_id);
        $this->datagrid->addColumn($quantidade);
        $this->datagrid->addColumn($total);
        $this->datagrid->addColumn($data);
        // Adicionar formatação para as colunas
        $total->setTransformer(function($value) {
            return ConvertCurrency::toBRFormat($value);
        });
        $data->setTransformer(function($value) {
            return ConvertDate::toBRFormat($value);
        });
        // Adicionar ações ao datagrid
        $edit_action = new TDataGridAction(['VendaFormController', 'onEdit']);
        $edit_action->setLabel('Editar');
        $edit_action->setImage('fa:edit blue');
        $edit_action->setField('id');
        $this->datagrid->addAction($edit_action);
        $delete_action = new TDataGridAction([$this, 'onDelete']);
        $delete_action->setLabel('Excluir');
        $delete_action->setImage('fa:trash red');
        $delete_action->setField('id');
        $this->datagrid->addAction($delete_action);
        // Criar modelo de datagrid
        $this->datagrid->createModel();
    }
    
    public function onReload($param = NULL) {
        try {
            TTransaction::open('sample');
            
            $repository = new TRepository('Venda');
            
            $criteria = new TCriteria(); // é uma classe utilizada para construir critérios de seleção em consultas SQL no contexto do Adianti Framework
            
            $objects = $repository->load($criteria, FALSE);
            
            $this->datagrid->clear();
            if ($objects) {
                foreach ($objects as $object) {
                    $this->datagrid->addItem($object);
                }
            }
            
            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    public function onDelete($param) {
        $action1 = new TAction(array($this, 'Delete'));
        $action1->setParameter('id', $param['id']);
        new TQuestion('Você realmente deseja excluir a venda?', $action1);
    }
    
    public function Delete($param) {
        try {
            TTransaction::open('sample');
            $venda = new Venda($param['id']);
            $venda->delete();
            TTransaction::close();
            
            $this->onReload($param);
            
            new TMessage('info', 'Venda excluída com sucesso');
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    public function show() {
        $this->onReload();
        parent::show();
    }


}


?>