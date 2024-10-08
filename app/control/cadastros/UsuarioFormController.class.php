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

class UsuarioFormController extends TPage {
    private $form;

    public function __construct() {
        parent::__construct();
        $this->form = new BootstrapFormBuilder('form_usuario'); // qualquer coisa colocar um nome no formulário, ex: form_alunos
        $id = new THidden('id');
        $nomeUsuario = new TEntry('name');
        $login = new TEntry('login');
        $senha = new TEntry('password');
        $email = new TEntry('email');

        $this->form->addFields([new TLabel('')], [$id]); // precisa dessa parte para poder editar mesmo usando "THidden" 
        $this->form->addFields([new TLabel('Nome:')], [$nomeUsuario]);
        $this->form->addFields([new TLabel('Login:')], [$login]);
        $this->form->addFields([new TLabel('Senha:')], [$senha]);
        $this->form->addFields([new TLabel('E-mail:')], [$email]);
        $this->form->addAction('Salvar', new TAction(array($this, 'onSave')), 'fa:save green');
        $this->form->addAction('Limpar', new TAction(array($this, 'onClear')), 'fa:eraser orange');
        parent::add($this->form);
        
    }

    // Só para teste --->  echo '<pre>'; print_r($method); echo '</pre>'; exit;

    public function onClear() {
        $this->form->clear();
    }

    public function onEdit($param) {
        try {
            if (isset($param['id'])) {
                TTransaction::open('permission');
                $usuario = new Usuario($param['key']);
                $this->form->setData($usuario);
                TTransaction::close();
            } else {
                $this->form->clear();
            }
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            // TTransaction::rollback();
        }
    }
        
    // Senha criptografada no formato MD5
    public function onSave() {
        try {
            TTransaction::open('permission');
            $data = $this->form->getData();

            if(empty($data->name)) {
                throw new Exception('Nome do usuário é obrigatório.');
            } else if (!UserExists::isUsernameUnique($data->name, $data->id)) {
                throw new Exception('Nome de usuário já existe. Por favor, escolha outro.');
            } else if(empty($data->password)) {
                throw new Exception('Senha é obrigatório.');
            } else if(!preg_match("/^[A-Za-z0-9]{6,}$/", $data->password)) {
                throw new Exception('Senha inválida.');
            }

            $is_new = empty($data->id);
            if ($is_new) {
                $usuario = new Usuario();
            } else {
                $usuario = new Usuario($data->id);
            }
    
            $usuario->name = $data->name;
            $usuario->login = $data->login;
    
            if (!empty($data->password)) {
                $usuario->password = md5($data->password);
            }
            $usuario->email = $data->email;

            $usuario->store();            
            $this->form->setData($usuario);
            
            if ($is_new) {
                $message = "Usuário incluído com sucesso!!!";
            } else {
                $message = "Usuário atualizado com sucesso!!!";
            }
            new TMessage('info', $message); 
            AdiantiCoreApplication::loadPage('UsuarioListController');
            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }
    

}

?>