<?php

class Finance_OrderController extends Zend_Controller_Action {

    public function init() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->redirect('/account/access?redirect=' . 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_webapp');
        $this->view->id_module = 2;
        $this->view->title_page = 'Lançamentos';
        $this->view->description_page = 'Controle de lançamentos';

        $this->view->headLink();

        $this->view->headScript()
                ->appendFile('/public/modules/finance/script.order.js?v=' . time())
                ->appendFile('/public/modules/finance/script.file.js')
        ;
    }

    public function getFilterAction() {
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
        );

        echo Zend_Json::encode($result);
    }

    public function filterUpdateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('User');
        $model = new User();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);

        foreach ($data as $key => $value) {
            Zend_Auth::getInstance()->getIdentity()->$key = $value;
        }

        $result = $model->updateTable(Zend_Auth::getInstance()->getIdentity()->id_user, $data);

        echo Zend_Json::encode($result);
    }

    public function dropdownContactAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Contact');
        $model = new Contact();

        foreach ($model->selectTableDropdown($this->_request->getParam('q')) as $value) {
            $result[] = array(
                name => $value['nickname_contact'],
                value => $value['id_contact']
            );
        }

        echo Zend_Json::encode(array(success => true, results => $result));
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

    public function dropdownCostCenterAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('CostCenter');
        $model = new CostCenter();

        foreach ($model->selectTableDropdown($this->_request->getParam('q')) as $value) {
            $result[] = array(
                name => $value['name_cost_center'],
                value => $value['id_cost_center']
            );
        }

        echo Zend_Json::encode(array(success => true, results => $result));
    }

    public function dropdownChartAccountsAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('ChartAccounts');
        $model = new ChartAccounts();

        foreach ($model->selectTableDropdown($this->_request->getParam('q')) as $value) {
            $result[] = array(
                name => $value['name_chart_accounts'],
                value => $value['id_chart_accounts']
            );
        }

        echo Zend_Json::encode(array(success => true, results => $result));
    }

    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Order');
        $model = new Order();

        $result = $model->selectTable();
        
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

        if ($data['id_repeat_quantity'] == '') {
            unset($data['id_repeat_quantity']);
        }

        if ($data['id_repeat_period'] == '') {
            unset($data['id_repeat_period']);
        }

        if ($data['id_chart_accounts'] == '') {
            unset($data['id_chart_accounts']);
        }

        if ($data['id_cost_center'] == '') {
            unset($data['id_cost_center']);
        }

        if ($data['date_issue_order'] == '') {
            unset($data['date_issue_order']);
        }

        if ($data['date_competence_order'] == '') {
            unset($data['date_competence_order']);
        }

        if ($data['date_payment_order'] == '') {
            unset($data['date_payment_order']);
        }

        if ($data['price_order'] != '') {
            $data['price_order'] = str_replace('.', '', $data['price_order']);
            $data['price_order'] = str_replace(',', '.', $data['price_order']);
        } else {
            unset($data['price_order']);
        }

        if ($data['price_discount_order'] != '') {
            $data['price_discount_order'] = str_replace('.', '', $data['price_discount_order']);
            $data['price_discount_order'] = str_replace(',', '.', $data['price_discount_order']);
        } else {
            unset($data['price_discount_order']);
        }

        if ($data['price_addition_order'] != '') {
            $data['price_addition_order'] = str_replace('.', '', $data['price_addition_order']);
            $data['price_addition_order'] = str_replace(',', '.', $data['price_addition_order']);
        } else {
            unset($data['price_addition_order']);
        }

        if ($data['price_fees_order'] != '') {
            $data['price_fees_order'] = str_replace('.', '', $data['price_fees_order']);
            $data['price_fees_order'] = str_replace(',', '.', $data['price_fees_order']);
        } else {
            unset($data['price_fees_order']);
        }

        $data['id_company'] = Zend_Auth::getInstance()->getIdentity()->id_company_session;

        $result = $model->insertTable($data);

        if ($data['repeat_order']) {
            $data['id_order_link'] = $result['returning'];
            $this->_repeatMonth($data);
        }

        echo Zend_Json::encode($result);
    }

    public function updateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Order');
        $model = new Order();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);

        if ($data['id_chart_accounts'] == '') {
            unset($data['id_chart_accounts']);
        }

        if ($data['id_cost_center'] == '') {
            unset($data['id_cost_center']);
        }

        if ($data['date_issue_order'] == '') {
            unset($data['date_issue_order']);
        }

        if ($data['date_competence_order'] == '') {
            unset($data['date_competence_order']);
        }

        if ($data['price_order'] != '') {
            $data['price_order'] = str_replace('.', '', $data['price_order']);
            $data['price_order'] = str_replace(',', '.', $data['price_order']);
        }

        if ($data['price_discount_order'] != '') {
            $data['price_discount_order'] = str_replace('.', '', $data['price_discount_order']);
            $data['price_discount_order'] = str_replace(',', '.', $data['price_discount_order']);
        }

        if ($data['price_addition_order'] != '') {
            $data['price_addition_order'] = str_replace('.', '', $data['price_addition_order']);
            $data['price_addition_order'] = str_replace(',', '.', $data['price_addition_order']);
        }

        if ($data['price_fees_order'] != '') {
            $data['price_fees_order'] = str_replace('.', '', $data['price_fees_order']);
            $data['price_fees_order'] = str_replace(',', '.', $data['price_fees_order']);
        }

        $result = $model->updateTable($this->_request->getParam('q'), $data);

        $this->_orderDuplicate($this->_request->getParam('q'), $data);
        
        echo Zend_Json::encode($result);
    }
    
    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Order');
        $model = new Order();

        $data = json_decode($this->_request->getParam("data"), true);
        
        $result = $model->deleteTable($data);
            
        echo Zend_Json::encode($result);
    }

    protected function _repeatMonth($data = null) {
        for ($index = 1; $index < $data['id_repeat_quantity']; $index++) {
            Zend_Loader::loadClass('Order');
            $model = new Order();

            $data['date_due_order'] = date('Y-m-d', strtotime('+' . $index . ' month'));
            $data['id_repeat_quantity_current'] = ($index + 1);
            $model->insertTable($data);
        }
    }

    public function _orderDuplicate($id_order = null, $data = null) {
        Zend_Loader::loadClass('Order');
        $model = new Order();

        $total = ((floatval($data['price_order']) - floatval($data['price_discount_order'])) + floatval($data['price_addition_order']) + floatval($data['price_fees_order']));
        
        if ($data['price_down'] != '' && floatval($total) > floatval($data['price_down'])) {
            
            $array = $model->selectTableDuplicate($id_order)[0];
            
            $array['description_order'] = $data['description_order'] . ' (diferença)';
            $array['date_payment_order'] = $data['date_payment_order'];
            $array['price_order'] = floatval($total) - floatval($data['price_down']);
            $array['price_down'] = $data['price_down'];
            $array['situation_order'] = 0;
            
            return $model->insertTable($array);
        }
    }

}
