<?php

use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Database\TTransaction;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TLabel;
use Adianti\Wrapper\BootstrapFormBuilder;
use Safe\Exceptions\ExecException;

/**
 * WelcomeView
 *
 * @version    7.6
 * @package    control
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class WelcomeView extends TPage {
    private $form;

    function __construct() {
        parent::__construct();
        echo "<h1>Bem-vindo!</h1>";
        parent::add($this->form);
        
    }


}
