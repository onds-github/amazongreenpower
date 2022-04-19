<?php

class Finance_ReportController extends Zend_Controller_Action {

    public function init() {
        
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->redirect('/account/access?redirect=' . 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }
    }

    public function extractAction() {
        $this->_helper->layout->setLayout('layout_webapp');
        $this->view->id_module = 6;
        $this->view->title_page = 'Extrato de Lançamentos';

        $this->view->headLink();
        
        $this->view->headScript()
                ->appendFile('/public/modules/finance/script.report.extract.js?v=' . time())
                ;
    }

    public function dropdownAccountAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Account');
        $model = new Account();

        foreach ($model->selectTableDropdown($this->_request->getParam('q')) as $value) {
            $result[] = array(
                name => $value['name_account'],
                value => $value['id_account']
            );
        }

        echo Zend_Json::encode(array(success => true, results => $result));
    }

    public function selectPreviousBalanceAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        
        Zend_Loader::loadClass('Report');
        $model = new Report();
        
        $result = number_format($model->selectPreviousBalance($this->_request->getParam('q'))[0]['total_accounts'], 2, ',', '.');

        echo Zend_Json::encode($result);
    }
    
    public function ajaxExtractAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Report');
        $model = new Report();

        foreach ($model->selectReportExtract() as $value) {
            $result[] = array(
                $value['id_order'],
                $value['date_payment_order'],
                $value['description_order'],
                ($value['transfer_order'] ? 'Transferência' : $value['nickname_contact']),
                $value['name_account'],
                number_format($value['price_down'], 2, ',', '.'),
                $value['id_type_order']
            );
        }

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

    public function filterPeriodStateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        $data['id_filter_period'] = Zend_Auth::getInstance()->getIdentity()->id_filter_period;
        $data['filter_period_min'] = Zend_Auth::getInstance()->getIdentity()->filter_period_min;
        $data['filter_period_max'] = Zend_Auth::getInstance()->getIdentity()->filter_period_max;
        $data['filter_id_account'] = Zend_Auth::getInstance()->getIdentity()->filter_id_account;
        
        echo Zend_Json::encode($data);
    }
    
    public function filterPeriodCustomAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        $data = array();
        parse_str($this->_request->getParam('data'), $data);
        
        Zend_Loader::loadClass('User');
        $model = new User();
        
        Zend_Auth::getInstance()->getIdentity()->id_filter_period = $data['id_filter_period'];
        Zend_Auth::getInstance()->getIdentity()->filter_period_min = $data['filter_period_min'];
        Zend_Auth::getInstance()->getIdentity()->filter_period_max = $data['filter_period_max'];
        
        $result = $model->updateTable(Zend_Auth::getInstance()->getIdentity()->id_user, $data);
        
        echo Zend_Json::encode($data);
    }

    public function filterPeriodAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('User');
        $model = new User();
        
        $data = array();
        
        switch ($this->_request->getParam('id_filter_period')) {
            case 1:
                $data['id_filter_period'] = 1;
                $data['filter_period_min'] = date("Y-m-d");
                $data['filter_period_max'] = date("Y-m-d");
                break;
            case 2:
                $data['id_filter_period'] = 2;
                $data['filter_period_min'] = date("Y-m-d", strtotime("monday this week"));
                $data['filter_period_max'] = date("Y-m-d", strtotime("sunday this week"));
                break;
            case 3:
                $data['id_filter_period'] = 3;
                $data['filter_period_min'] = date("Y-m-d", strtotime("first day of this month"));
                $data['filter_period_max'] = date("Y-m-d", strtotime("last day of this month"));
                break;
            case 4:
                $data['id_filter_period'] = 4;
                $data['filter_period_min'] = $this->_request->getParam('filter_period_min');
                $data['filter_period_max'] = $this->_request->getParam('filter_period_max');
                break;
        }
        
        Zend_Auth::getInstance()->getIdentity()->id_filter_period = $data['id_filter_period'];
        Zend_Auth::getInstance()->getIdentity()->filter_period_min = $data['filter_period_min'];
        Zend_Auth::getInstance()->getIdentity()->filter_period_max = $data['filter_period_max'];
        
        $result = $model->updateTable(Zend_Auth::getInstance()->getIdentity()->id_user, $data);
        
        echo Zend_Json::encode($data);
    }

    public function filterPeriodPrevAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('User');
        $model = new User();
        
        $data = array();
        
        switch (Zend_Auth::getInstance()->getIdentity()->id_filter_period) {
            case 1:
                $data['filter_period_min'] = date("Y-m-d", strtotime("-1 day", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_min)));
                $data['filter_period_max'] = date("Y-m-d", strtotime("-1 day", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_max)));
                break;
            case 2:
                $data['filter_period_min'] = date("Y-m-d", strtotime("-1 week", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_min)));
                $data['filter_period_max'] = date("Y-m-d", strtotime("-1 week", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_max)));
                break;
            case 3:
                $data['filter_period_min'] = date("Y-m-d", strtotime("-1 month", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_min)));
                $data['filter_period_max'] = date("Y-m-d", strtotime("-1 month", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_max)));
                break;
        }
        
        $data['id_filter_period'] = Zend_Auth::getInstance()->getIdentity()->id_filter_period;
        Zend_Auth::getInstance()->getIdentity()->filter_period_min = $data['filter_period_min'];
        Zend_Auth::getInstance()->getIdentity()->filter_period_max = $data['filter_period_max'];
        
        $result = $model->updateTable(Zend_Auth::getInstance()->getIdentity()->id_user, $data);
        
        echo Zend_Json::encode($data);
    }

    public function filterPeriodNextAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('User');
        $model = new User();
        
        $data = array();
        
        switch (Zend_Auth::getInstance()->getIdentity()->id_filter_period) {
            case 1:
                $data['filter_period_min'] = date("Y-m-d", strtotime("+1 day", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_min)));
                $data['filter_period_max'] = date("Y-m-d", strtotime("+1 day", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_max)));
                break;
            case 2:
                $data['filter_period_min'] = date("Y-m-d", strtotime("+1 week", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_min)));
                $data['filter_period_max'] = date("Y-m-d", strtotime("+1 week", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_max)));
                break;
            case 3:
                $data['filter_period_min'] = date("Y-m-d", strtotime("+1 month", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_min)));
                $data['filter_period_max'] = date("Y-m-d", strtotime("+1 month", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_max)));
                break;
        }
        
        $data['id_filter_period'] = Zend_Auth::getInstance()->getIdentity()->id_filter_period;
        Zend_Auth::getInstance()->getIdentity()->filter_period_min = $data['filter_period_min'];
        Zend_Auth::getInstance()->getIdentity()->filter_period_max = $data['filter_period_max'];
        
        $result = $model->updateTable(Zend_Auth::getInstance()->getIdentity()->id_user, $data);
        
        echo Zend_Json::encode($data);
    }

    public function filterAccountAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('User');
        $model = new User();
        
        $data = array();
        
        $data['filter_id_account'] = $this->_request->getParam("q");
        Zend_Auth::getInstance()->getIdentity()->filter_id_account = $data['filter_id_account'];
        
        $result = $model->updateTable(Zend_Auth::getInstance()->getIdentity()->id_user, $data);
        
        echo Zend_Json::encode($data);
    }

}
