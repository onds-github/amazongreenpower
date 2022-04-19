<?php

class Finance_CashierController extends Zend_Controller_Action {

    public function init() {
        
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->redirect('/account/access?redirect=' . 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_webapp');
        $this->view->id_module = 4;
        $this->view->title_page = 'Caixa';
        $this->view->description_page = 'Controle de lançamentos de saída temporários';

        $this->view->headLink();
        
        $this->view->headScript()
                ->appendFile('/public/modules/finance/script.cashier.js?v=' . time());
    }

    public function selectContactDropdownAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Cashier');
        $model = new Cashier();
        
        foreach ($model->selectTableContactDropdown($this->_request->getParam('q')) as $value) {
            $result[] = array(
                name => $value['nickname_contact'],
                value => $value['id_contact'],
                text => $value['nickname_contact']
            );
        }

        echo Zend_Json::encode(array(success => true, results => $result));
    }

    public function selectAccountDropdownAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Cashier');
        $model = new Cashier();
        
        foreach ($model->selectTableAccountDropdown($this->_request->getParam('q')) as $value) {
            $result[] = array(
                name => $value['name_account'],
                value => $value['id_account'],
                text => $value['name_account']
            );
        }

        echo Zend_Json::encode(array(success => true, results => $result));
    }

    public function selectCostCenterDropdownAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Cashier');
        $model = new Cashier();
        
        foreach ($model->selectTableCostCenterDropdown($this->_request->getParam('q')) as $value) {
            $result[] = array(
                name => $value['name_cost_center'],
                value => $value['id_cost_center'],
                text => $value['name_cost_center']
            );
        }

        echo Zend_Json::encode(array(success => true, results => $result));
    }

    public function selectChartAccountsDropdownAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('ChartAccounts');
        $model = new ChartAccounts();
        
        foreach ($model->viewChartAccountsDropdown(Zend_Auth::getInstance()->getIdentity()->id_company_session, $this->_request->getParam('q')) as $value) {
            $result[] = array(
                name => $value['name_chart_accounts'],
                value => $value['id_chart_accounts'],
                text => $value['name_chart_accounts']
            );
        }

        echo Zend_Json::encode(array(success => true, results => $result));
    }

    public function selectAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Cashier');
        $model = new Cashier();

        $result = $model->viewCashier(Zend_Auth::getInstance()->getIdentity()->id_company_session, $this->_request->getParam('id_order_out'));

        echo Zend_Json::encode($result);
    }

    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Cashier');
        $model = new Cashier();

        $result = $model->selectTable();

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

    public function ajaxOrderAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Cashier');
        $model = new Cashier();

        $result = $model->selectTableOrder($this->_request->getParam('q'));

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Order');
        $model = new Order();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);
        
        $data['id_company'] = Zend_Auth::getInstance()->getIdentity()->id_company_session;
        
        $data['cashier_order'] = 1;
        $data['cashier_order_check'] = 1;
        $data['situation_order'] = 1;
        $data['id_type_order'] = 2;

        $data['date_due_order'] = $data['date_issue_order'];
        $data['date_payment_order'] = $data['date_issue_order'];
       
        $data['price_order'] = str_replace('.', '', $data['price_order']);
        $data['price_order'] = str_replace(',', '.', $data['price_order']);
        $data['price_down'] = $data['price_order'];
        
        $result = $model->insertTable($data);

        echo Zend_Json::encode($result);
    }

    public function insertOrderAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Order');
        $model = new Order();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);
        
        $data['cashier_id_order'] = $this->_request->getParam('q');
        $data['id_account'] = $this->_request->getParam('id_account');
        
        $data['cashier_order'] = 1;
        $data['cashier_order_check'] = 0;
        $data['situation_order'] = 1;
        $data['id_type_order'] = 2;
        
        if ($data['id_chart_accounts'] == '') {
            unset($data['id_chart_accounts']);
        }

        if ($data['id_cost_center'] == '') {
            unset($data['id_cost_center']);
        }

        if ($data['date_competence_order'] == '') {
            unset($data['date_competence_order']);
        }

        $data['date_due_order'] = $data['date_issue_order'];
        $data['date_payment_order'] = $data['date_issue_order'];
       

        $data['price_order'] = str_replace('.', '', $data['price_order']);
        $data['price_order'] = str_replace(',', '.', $data['price_order']);
        $data['price_down'] = $data['price_order'];
        
        $data['id_company'] = Zend_Auth::getInstance()->getIdentity()->id_company_session;

        $result = $model->insertTable($data);

        echo Zend_Json::encode($result);
    }

    public function updateCheckAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Cashier');
        $model = new Cashier();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);
        
        $result = $model->updateTable($this->_request->getParam('q'), $data);
        
        switch ($data['cashier_order_check']) {
            case 0:
                $result = $model->updateTableCheck($this->_request->getParam('q'), array(cashier_order_check => 1));
                break;
            
            case 1:
                $result = $model->updateTableCheck($this->_request->getParam('q'), array(cashier_order_check => 0));
                break;
        }
        
        echo Zend_Json::encode($result);
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        
        Zend_Loader::loadClass('Cashier');
        $model = new Cashier();
        
        $array = json_decode($this->_request->getParam("data"), true);
        
        foreach ($array as $value) {
            foreach ($model->selectTableOrder($value) as $value_cashier) {
                if ($value_cashier['id_order']) {
                    $result = $model->deleteTable($value_cashier['id_order']);
                }
            }
            $result = $model->deleteTable($value);
        }

        echo Zend_Json::encode($result);
    }
    
}
