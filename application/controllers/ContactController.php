<?php

class ContactController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function indexAction() {
        
        $this->view->title = 'Página inicial';
        $this->view->page = '613f528b6c338dc775ac8cce';
        
        
        $this->view->headLink();
        
        $this->view->headScript()
                ->appendFile($this->view->baseUrl('public/modules/application/js/home.js'));
    }
    
    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Contact');
        $model = new Contact();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);

        $result = $model->insertTable($data);

        echo Zend_Json::encode($result);
    }

}
