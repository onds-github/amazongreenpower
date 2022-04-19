<?php

class Account_FilterController extends Zend_Controller_Action {

    public function init() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->redirect('/account/access?redirect=' . 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }
    }

    public function selectAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Contact');
        $model = new Contact();

        $result = array(
            filter_id_type_order => Zend_Auth::getInstance()->getIdentity()->filter_id_type_order,
            filter_id_type_date => Zend_Auth::getInstance()->getIdentity()->filter_id_type_date,
            filter_id_type_period => Zend_Auth::getInstance()->getIdentity()->filter_id_type_period,
            filter_period_min => Zend_Auth::getInstance()->getIdentity()->filter_period_min,
            filter_period_max => Zend_Auth::getInstance()->getIdentity()->filter_period_max,
            filter_id_contact => Zend_Auth::getInstance()->getIdentity()->filter_id_contact,
            filter_id_account => Zend_Auth::getInstance()->getIdentity()->filter_id_account,
            filter_id_chart_accounts => Zend_Auth::getInstance()->getIdentity()->filter_id_chart_accounts,
            filter_id_cost_center => Zend_Auth::getInstance()->getIdentity()->filter_id_cost_center,
            filter_conciliation_id_account => Zend_Auth::getInstance()->getIdentity()->filter_conciliation_id_account,
            filter_conciliation_period_min => Zend_Auth::getInstance()->getIdentity()->filter_conciliation_period_min,
            filter_conciliation_period_max => Zend_Auth::getInstance()->getIdentity()->filter_conciliation_period_max,
            filter_report_extract_id_account => Zend_Auth::getInstance()->getIdentity()->filter_report_extract_id_account,
            filter_report_extract_period_min => Zend_Auth::getInstance()->getIdentity()->filter_report_extract_period_min,
            filter_report_extract_period_max => Zend_Auth::getInstance()->getIdentity()->filter_report_extract_period_max,
            
            
        );

        echo Zend_Json::encode($result);
    }

    public function updateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('User');
        $model = new User();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);
        
        if ($data['filter_toggle']) {
            switch (Zend_Auth::getInstance()->getIdentity()->filter_toggle) {
                case 1:
                    $data['filter_toggle'] = 0;
                    break;
                case 0:
                    $data['filter_toggle'] = 1;
                    break;
            }
        }

        foreach ($data as $key => $value) {
            Zend_Auth::getInstance()->getIdentity()->$key = $value;
        }

        $result = $model->updateTable(Zend_Auth::getInstance()->getIdentity()->id_user, $data);
        $result['returning'] = $data['filter_toggle'];
        echo Zend_Json::encode($result);
    }

    public function toggleAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('User');
        $model = new User();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);
        
        if ($data['filter_toggle']) {
            switch (Zend_Auth::getInstance()->getIdentity()->filter_toggle) {
                case 1:
                    $data['filter_toggle'] = 0;
                    break;
                case 0:
                    $data['filter_toggle'] = 0;
                    break;
            }
        }

        foreach ($data as $key => $value) {
            Zend_Auth::getInstance()->getIdentity()->$key = $value;
        }

        $result = $model->updateTable(Zend_Auth::getInstance()->getIdentity()->id_user, $data);

        echo Zend_Json::encode($data['filter_toggle']);
    }


    public function filterPeriodStateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        $data['id_filter_period'] = Zend_Auth::getInstance()->getIdentity()->id_filter_period;
        $data['filter_period_min'] = Zend_Auth::getInstance()->getIdentity()->filter_period_min;
        $data['filter_period_max'] = Zend_Auth::getInstance()->getIdentity()->filter_period_max;

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
                $data['filter_period_min'] = date("Y-m-d", strtotime("first day of previous month", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_min)));
                $data['filter_period_max'] = date("Y-m-d", strtotime("last day of previous month", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_max)));
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
                $data['filter_period_min'] = date("Y-m-d", strtotime("first day of next month", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_min)));
                $data['filter_period_max'] = date("Y-m-d", strtotime("last day of next month", strtotime(Zend_Auth::getInstance()->getIdentity()->filter_period_max)));
                break;
        }

        $data['id_filter_period'] = Zend_Auth::getInstance()->getIdentity()->id_filter_period;
        Zend_Auth::getInstance()->getIdentity()->filter_period_min = $data['filter_period_min'];
        Zend_Auth::getInstance()->getIdentity()->filter_period_max = $data['filter_period_max'];

        $result = $model->updateTable(Zend_Auth::getInstance()->getIdentity()->id_user, $data);

        echo Zend_Json::encode($data);
    }

    public function insertClientAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('ContactTypeLink');
        $model = new ContactTypeLink();

        $data['id_contact_type'] = Zend_Auth::getInstance()->getIdentity()->id_contact_type_link_client;
        $data['id_contact'] = $this->_request->getParam("id_contact");

        $result = $model->insertTable($data);

        echo Zend_Json::encode($result);
    }

}
