<?php

class Account_CompanyController extends Zend_Controller_Action {

    public function init() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->redirect('/account/access?redirect=' . 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_webapp');
        $this->view->id_module = 999;
        $this->view->title_page = 'Info. da Empresa';
        $this->view->description_page = 'Gerenciamento de informações';
        
        $this->view->headScript()
                ->appendFile('/public/modules/account/script.company.js');
        
    }

    public function dropdownCompanyAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Company');
        $model = new Company();

        foreach ($model->selectTableDropdown($this->_request->getParam('q')) as $value) {
            $result[] = array(
                name => $value['name_company'],
                value => $value['id_company']
            );
        }

        echo Zend_Json::encode(array(success => true, results => $result));
    }

    public function selectAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Company');
        $model = new Company();
        
        $result = $model->selectTable(Zend_Auth::getInstance()->getIdentity()->id_company_session);

        echo Zend_Json::encode($result);
    }

    public function updateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Company');
        $model = new Company();

        $data = array();
        parse_str($this->_request->getParam("data"), $data);

        $result = $model->updateTable(Zend_Auth::getInstance()->getIdentity()->id_company_session, $data);
        
        if ($result['status'] == 'success') {
            Zend_Auth::getInstance()->getIdentity()->name_company = $data['name_company'];
        }
        
        echo Zend_Json::encode($result);
    }

}
