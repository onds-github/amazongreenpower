<?php

class Account_SecurityController extends Zend_Controller_Action {

    public function init() {
        
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->redirect('/account/access?redirect=' . 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_configuration');
        $this->view->title_page = 'Segurança';
        
        $this->view->headScript()
                ->appendFile($this->view->baseUrl('public/modules/account/js/security/default.js'));
        
    }

    public function selectAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('User');
        $model = new User();
        
        $result = $model->viewUserDetails(Zend_Auth::getInstance()->getIdentity()->id_user);

        echo Zend_Json::encode($result);
    }

    public function updateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('User');
        $model = new User();

        $data = array();
        parse_str($this->_request->getParam("data"), $data);
        
        unset($data['cpassword_user']);

        $data['password_user'] = md5($data['password_user']);

        $result = $model->updateTable(Zend_Auth::getInstance()->getIdentity()->id_user, $data);
        
        echo Zend_Json::encode($result);
    }

}
