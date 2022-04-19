<?php

class Finance_TransferController extends Zend_Controller_Action {

    public function init() {

        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->redirect('/account/access?redirect=' . 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_webapp');
        $this->view->id_module = 5;
        $this->view->title_page = 'Transferências';
        $this->view->description_page = 'Gerenciamento de transferências entre contas';

        $this->view->headLink();

        $this->view->headScript()
                ->appendFile('/public/modules/finance/script.transfer.js');
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
                value => $value['id_account'],
                text => $value['name_account']
            );
        }

        echo Zend_Json::encode(array(success => true, results => $result));
    }

    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Transfer');
        $model = new Transfer();

        $result = $model->selectTable();

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        $data = array();
        parse_str($this->_request->getParam('data'), $data);

        if ($data['price_order'] != '') {
            $data['price_order'] = str_replace('.', '', $data['price_order']);
            $data['price_order'] = str_replace(',', '.', $data['price_order']);
        }

        $data['transfer_id_order'] = $this->_insertOut($data)['returning'];

        $result = $this->_insertIn($data);

        echo Zend_Json::encode($result);
    }

    public function _insertOut($data = null) {

        $data_out['id_type_order'] = 2;
        $data_out['description_order'] = $data['description_order'];
        $data_out['transfer_order'] = true;
        $data_out['id_account'] = $data['id_account_out'];
        $data_out['price_order'] = $data['price_order'];

        $data_out['date_issue_order'] = $data['date_due_order'];
        $data_out['date_due_order'] = $data['date_due_order'];
        $data_out['date_payment_order'] = $data['date_due_order'];
        $data_out['situation_order'] = true;
        $data_out['id_company'] = Zend_Auth::getInstance()->getIdentity()->id_company_session;

        Zend_Loader::loadClass('Order');
        $model = new Order();

        return $model->insertTable($data_out);
    }

    public function _insertIn($data = null) {

        $data_in['id_type_order'] = 1;
        $data_in['description_order'] = $data['description_order'];
        $data_in['transfer_order'] = true;
        $data_in['transfer_id_order'] = $data['transfer_id_order'];
        $data_in['id_account'] = $data['id_account_in'];
        $data_in['price_order'] = $data['price_order'];

        $data_out['date_issue_order'] = $data['date_due_order'];
        $data_in['date_due_order'] = $data['date_due_order'];
        $data_in['date_payment_order'] = $data['date_due_order'];
        $data_in['situation_order'] = true;
        $data_in['id_company'] = Zend_Auth::getInstance()->getIdentity()->id_company_session;

        Zend_Loader::loadClass('Order');
        $model = new Order();

        return $model->insertTable($data_in);
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Order');
        $model = new Order();

        $array = json_decode($this->_request->getParam("data"), true);

        foreach ($array as $key => $value) {
            $transfer_id_order = $model->selectTableTransferIdOrder($value)[0]['transfer_id_order'];
            $result = $model->deleteTable($value);
            if ($transfer_id_order) {
                $result = $model->deleteTable($transfer_id_order);
            }
        }

        echo Zend_Json::encode($result);
    }

}
