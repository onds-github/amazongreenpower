<?php

class Settings_EquipmentTypeController extends Zend_Controller_Action {

    public function init() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->redirect('/account/access?redirect=' . 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout("layout_webapp");
        $this->view->id_module = 999;
        $this->view->title_page = 'Plano de Contas';
        $this->view->description_page = 'Registros das atividades da empresa';

        $this->view->headLink();

        $this->view->headScript()
                ->appendFile('/public/modules/settings/script.chart-accounts.js');
    }

    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('ChartAccounts');
        $model = new ChartAccounts();

        $result = $model->selectTable();

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('ChartAccounts');
        $model = new ChartAccounts();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);

        $data['id_company'] = Zend_Auth::getInstance()->getIdentity()->id_company_session;

        $result = $model->insertTable($data);

        echo Zend_Json::encode($result);
    }

    public function updateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('ChartAccounts');
        $model = new ChartAccounts();

        $data = array();
        parse_str($this->_request->getParam("data"), $data);

        $result = $model->updateTable($this->_request->getParam("q"), $data);

        echo Zend_Json::encode($result);
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('ChartAccounts');
        $model = new ChartAccounts();

        $data = json_decode($this->_request->getParam("data"), true);

        $result = $model->deleteTable($data);

        echo Zend_Json::encode($result);
    }

}
