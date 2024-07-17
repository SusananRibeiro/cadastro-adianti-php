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

class UsuarioListController extends TPage {

    private $datagrid;
    private $form;

    public function __construct() {
        parent::__construct(); // precisa colocar sempre

        $this->createDataGrid();

        // Criar formulário
        $this->form = new TForm('form_list_usuario');
        $new_button = new TButton('new');
        $new_button->setAction(new TAction(array('UsuarioFormController', 'onEdit')), 'Novo');
        $new_button->setImage('fa:plus green');

        $this->form->addField($new_button);

        // Criar contêiner
        $hbox = new THBox();
        $hbox->add($new_button);
        $this->form->setFields(array($new_button));
        $this->form->add($hbox);
        $vbox = new TVBox();
        $vbox->style = 'width: 100%';
        $vbox->add($this->form);
        $vbox->add(TPanelGroup::pack('Lista dos Usuários', $this->datagrid, $this->pageNavigation));
        parent::add($vbox);
    }

    private function createDataGrid() {
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid('list_usuarios')); // para deixa responsivo

        // Criar colunas
        $id = new TDataGridColumn('id', 'ID', 'center', 50);
        $nomeUsuario = new TDataGridColumn('nome_usuario', 'Usuário:', 'center', 300);
        $senha = new TDataGridColumn('senha', 'Senha:', 'center', 600);

        // Adicionar colunas ao datagrid
        $this->datagrid->addColumn($id);
        $this->datagrid->addColumn($nomeUsuario);
        $this->datagrid->addColumn($senha);

        // Adicionar ações ao datagrid
        $edit_action = new TDataGridAction(array('UsuarioFormController', 'onEdit'));
        $edit_action->setLabel('Editar');
        $edit_action->setImage('fa:edit blue');
        $edit_action->setField('id');
        $this->datagrid->addAction($edit_action);

        $delete_action = new TDataGridAction(array($this, 'onDelete'));
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
            $repository = new TRepository('Usuario');
            $criteria = new TCriteria();
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
        new TQuestion('Você realmente deseja excluir o usuário?', $action1);
    }
    
    public function Delete($param) {
        try {
            TTransaction::open('sample');
            $cliente = new Usuario($param['id']);
            $cliente->delete();
            TTransaction::close();
            $this->onReload($param);
            new TMessage('info', 'Usuário excluído com sucesso');
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