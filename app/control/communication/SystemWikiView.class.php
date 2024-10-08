<?php

use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Database\TTransaction;
use Adianti\Widget\Base\TElement;
use Adianti\Widget\Base\TScript;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Form\THidden;
use Adianti\Widget\Util\TXMLBreadCrumb;
use Adianti\Wrapper\BootstrapFormBuilder;

/**
 * SystemWikiView
 *
 * @version    7.6
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @author     Lucas Tomasi
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemWikiView extends TPage {
    protected $form;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder('wiki_form');
        
        $row1 = $this->form->addFields([new THidden('id')]);
        $row1->style = 'display: none';

        $btn_onshow = $this->form->addActionLink(_t("Back"), new TAction(['SystemWikiView', 'onBack']), 'fas:arrow-left');
        
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', 'SystemWikiSearchList' ));
        $container->add($this->form);

        parent::add($container);
    }
    
    /**
     * on load page
     */
    public function onLoad( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key']; 
                TTransaction::open('communication');
                $object = new SystemWikiPage($key);

                $title = new TElement('div');
                $title->{'class'} = 'system-wiki-view-title';
                $title->add(TElement::tag('div', $object->title));
                $title->add(TElement::tag('small', _t('Created by') . ' ' . $object->system_user->name));
                $title->add(TElement::tag('small', _t('Last modification') . ' ' . $object->date_updated));

                $tags = array_map(function($tag) {
                    return TElement::tag('span', $tag, ['class' => 'badge bg-green']);
                }, $object->getTags());
                
                $this->form->setFormTitle($title);
                $this->form->addContent([$object->content]);
                $this->form->addHeaderWidget(TElement::tag('div', $tags));
                
                TTransaction::close();
            }
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     *
     */
    public static function onBack($param)
    {
        TScript::create('history.go(-1)');
    }
}