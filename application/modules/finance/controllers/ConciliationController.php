<?php

class Finance_ConciliationController extends Zend_Controller_Action {

    public function init() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->redirect('/account/access?redirect=' . 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_webapp');
        $this->view->id_module = 7;
        $this->view->title_page = 'Conciliação Bancária';
        $this->view->description_page = 'Controle dos saldos bancários';

        $this->view->headLink();

        $this->view->headScript()
                ->appendFile('/public/modules/finance/script.conciliation.js?v=' . time());
    }

//    public function selectFilterAction() {
//        $this->_helper->layout->disableLayout();
//        $this->_helper->viewRenderer->setNoRender();
//        $this->getResponse()->setHeader('Content-Type', 'application/json');
//
//        echo Zend_Json::encode(Zend_Auth::getInstance()->getIdentity());
//    }

//    public function updateUserAction() {
//        $this->_helper->layout->disableLayout();
//        $this->_helper->viewRenderer->setNoRender();
//        $this->getResponse()->setHeader('Content-Type', 'application/json');
//
//        Zend_Loader::loadClass('User');
//        $model = new User();
//
//        $data = array();
//        parse_str($this->_request->getParam('data'), $data);
//
//        $result = $model->updateTable(Zend_Auth::getInstance()->getIdentity()->id_user, $data);
//
//        Zend_Auth::getInstance()->getIdentity()->filter_id_account = $data['filter_id_account'];
//        Zend_Auth::getInstance()->getIdentity()->filter_period_min = $data['filter_period_min'];
//        Zend_Auth::getInstance()->getIdentity()->filter_period_max = $data['filter_period_max'];
//
//        echo Zend_Json::encode($result);
//    }

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

//    public function selectAccountDropdownAction() {
//        $this->_helper->layout->disableLayout();
//        $this->_helper->viewRenderer->setNoRender();
//        $this->getResponse()->setHeader('Content-Type', 'application/json');
//
//        Zend_Loader::loadClass('Report');
//        $model = new Report();
//
//        $result = $model->selectTableAccountDropdown();
//
//        echo Zend_Json::encode($result);
//    }

//    public function selectAction() {
//        $this->_helper->layout->disableLayout();
//        $this->_helper->viewRenderer->setNoRender();
//        $this->getResponse()->setHeader('Content-Type', 'application/json');
//
//        Zend_Loader::loadClass('Order');
//        $model = new Order();
//
//        $result = $model->viewOrder(Zend_Auth::getInstance()->getIdentity()->id_company_session, $this->_request->getParam('q'));
//
//        echo Zend_Json::encode($result);
//    }

    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        $xml = new SimpleXMLElement($this->getOfxAsXML($_FILES['file']['tmp_name']));
        $transactions = $xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->BANKTRANLIST->STMTTRN;

        Zend_Loader::loadClass('OrderConciliation');
        $model = new OrderConciliation();

        foreach ($transactions as $row) {

            if (strpos($row->TRNTYPE, 'DEBIT') !== false) {
                $type = 2;
            } else if (strpos($row->TRNTYPE, 'CREDIT') !== false) {
                $type = 1;
            }

            $array = array(
                'id_company' => Zend_Auth::getInstance()->getIdentity()->id_company_session,
                'id_account' => Zend_Auth::getInstance()->getIdentity()->filter_id_account,
                'date_order_conciliation' => date("Y-m-d", strtotime(substr($row->DTPOSTED, 0, 8))),
                'description_order_conciliation' => $row->MEMO,
                'price_order_conciliation' => abs($row->TRNAMT),
                'fitid_order_conciliation' => $row->FITID,
                'id_type_order' => $type
            );

            $result = $model->insertTable($array);
        }

        echo Zend_Json::encode($result);
    }

    public function updateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('OrderConciliation');
        $model = new OrderConciliation();

        $data['id_order'] = $this->_request->getParam('data');

        $result = $model->updateTable($this->_request->getParam('q'), $data);

        echo Zend_Json::encode($result);
    }

    public function getOfxAsXML($ofxFile) {
        $content = file_get_contents($ofxFile);
        $line = strpos($content, "<OFX>");
        $ofx = substr($content, $line - 1);
        $buffer = $ofx;
        $count = 0;
        while ($pos = strpos($buffer, '<')) {
            $count++;
            $pos2 = strpos($buffer, '>');
            $element = substr($buffer, $pos + 1, $pos2 - $pos - 1);
            if (substr($element, 0, 1) == '/')
                $sla[] = substr($element, 1);
            else
                $als[] = $element;
            $buffer = substr($buffer, $pos2 + 1);
        }
        $adif = array_diff($als, $sla);
        $adif = array_unique($adif);
        $ofxy = $ofx;
        foreach ($adif as $dif) {
            $dpos = 0;
            while ($dpos = strpos($ofxy, $dif, $dpos + 1)) {
                $npos = strpos($ofxy, '<', $dpos + 1);
                $ofxy = substr_replace($ofxy, "</$dif>\n<", $npos, 1);
                $dpos = $npos + strlen($element) + 3;
            }
        }
        $ofxy = str_replace('&', '&', $ofxy);
        return $ofxy;
    }

    public function getBalance() {
        $xml = new SimpleXMLElement($this->getOfxAsXML());
        $balance = $xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->LEDGERBAL->BALAMT;
        $dateOfBalance = $xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->LEDGERBAL->DTASOF;
        $date = strtotime(substr($dateOfBalance, 0, 8));
        $dateToReturn = date('Y-m-d', $date);
        return array('date' => $dateToReturn, 'balance' => $balance);
    }

    public function ajaxConciliationAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('OrderConciliation');
        $model = new OrderConciliation();

        $result = $model->selectOrderConciliation();

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

    public function ajaxOrderAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('OrderConciliation');
        $model = new OrderConciliation();

        $result = $model->selectOrder();

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

//    public function filterAccountAction() {
//        $this->_helper->layout->disableLayout();
//        $this->_helper->viewRenderer->setNoRender();
//        $this->getResponse()->setHeader('Content-Type', 'application/json');
//
//        Zend_Loader::loadClass('User');
//        $model = new User();
//
//        $data = array();
//
//        $data['filter_account'] = $this->_request->getParam("q");
//        Zend_Auth::getInstance()->getIdentity()->filter_account = $data['filter_account'];
//
//        $result = $model->updateTable(Zend_Auth::getInstance()->getIdentity()->id_user, $data);
//
//        echo Zend_Json::encode($data);
//    }

//    public function insertOrderAction() {
//        $this->_helper->layout->disableLayout();
//        $this->_helper->viewRenderer->setNoRender();
//        $this->getResponse()->setHeader('Content-Type', 'application/json');
//
//        Zend_Loader::loadClass('OrderIn');
//        $modelOrderIn = new OrderIn();
//
//        Zend_Loader::loadClass('OrderInConciliation');
//        $modelOrderInConciliation = new OrderInConciliation();
//
//        Zend_Loader::loadClass('OrderOut');
//        $modelOrderOut = new OrderOut();
//
//        Zend_Loader::loadClass('OrderOutConciliation');
//        $modelOrderOutConciliation = new OrderOutConciliation();
//
//        $data = json_decode($this->_request->getParam('data'), true);
//
//        foreach ($data as $row) {
//            switch ($row['type_order']) {
//                case 1:
//                    $arrayOrderIn = array(
//                        'id_company' => Zend_Auth::getInstance()->getIdentity()->id_company_session,
//                        'id_account' => Zend_Auth::getInstance()->getIdentity()->filter_id_account,
//                        'date_due_order_in' => $row['date_order'],
//                        'date_payment_order_in' => $row['date_order'],
//                        'description_order_in' => $row['description_order'],
//                        'price_order_in' => $row['price_order'],
//                        'situation_order_in' => 1
//                    );
//
//                    $result = $modelOrderIn->insertTable($arrayOrderIn);
//                    $modelOrderInConciliation->updateTable($row['id_order'], array('id_order_in' => $result['returning']));
//
//                    break;
//                case 2:
//                    $arrayOrderOut = array(
//                        'id_company' => Zend_Auth::getInstance()->getIdentity()->id_company_session,
//                        'id_account' => Zend_Auth::getInstance()->getIdentity()->filter_id_account,
//                        'date_due_order_out' => $row['date_order'],
//                        'date_payment_order_out' => $row['date_order'],
//                        'description_order_out' => $row['description_order'],
//                        'price_order_out' => $row['price_order'],
//                        'situation_order_out' => 1
//                    );
//
//                    $result = $modelOrderOut->insertTable($arrayOrderOut);
//                    $modelOrderOutConciliation->updateTable($row['id_order'], array('id_order_out' => $result['returning']));
//                    break;
//            }
//        }
//
//        echo Zend_Json::encode(array('status' => 'success', 'message' => 'O registr foi salvo!'));
//    }

    public function removeOrderAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('OrderConciliation');
        $modal = new OrderConciliation();

        Zend_Loader::loadClass('Order');
        $modalOrder = new Order();

        $data = json_decode($this->_request->getParam('data'), true);

        foreach ($data as $row) {
            $modal->updateTableConciliation($row['token_conciliation'], array('token_conciliation' => null));
            $modalOrder->updateTableConciliation($row['token_conciliation'], array('token_conciliation' => null));
        }

        echo Zend_Json::encode(array('status' => 'success', 'message' => 'O registro foi salvo!'));
    }

    public function conciliationOrderAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('OrderConciliation');
        $modal = new OrderConciliation();

        Zend_Loader::loadClass('Order');
        $modalOrder = new Order();

        $data_order = json_decode($this->_request->getParam('data_order'), true);

        $data_conciliation = json_decode($this->_request->getParam('data_conciliation'), true);

        //separação de lançamentos com key de identificação ID_ORDER
        foreach ($data_order as $row) {
            switch (intval($row['id_type_order'])) {
                case 1:
                    $arrayOrderIn[$row['id_order']] = $row['price_down'];
                    break;
                case 2:
                    $arrayOrderOut[$row['id_order']] = $row['price_down'];
                    break;
            }
        }

        // se localizar... realizar update!
        foreach ($data_conciliation as $row) {
            $r[] = array_search($row['price_order'], $arrayOrderIn);
            
            $better_token = md5(uniqid(rand(), true));

            switch (intval($row['id_type_order'])) {
                case 1:
                    #update order_conciliation
                    $modal->updateTable($row['id_order_conciliation'], array('token_conciliation' => $better_token));
                    #update order
                    $modalOrder->updateTable(array_search($row['price_order'], $arrayOrderIn), array(token_conciliation => $better_token));
                    unset($arrayOrderIn[array_search($row['price_order'], $arrayOrderIn)]);
                    break;
                case 2:
                    #update order_conciliation
                    $modal->updateTable($row['id_order_conciliation'], array('token_conciliation' => $better_token));
                    #update order
                    $modalOrder->updateTable(array_search($row['price_order'], $arrayOrderOut), array(token_conciliation => $better_token));
                    unset($arrayOrderOut[array_search($row['price_order'], $arrayOrderOut)]);
                    break;
            }
        }

        echo Zend_Json::encode(array('status' => 'success', 'message' => 'O registro foi salvo!'));
    }

    public function conciliationOrderSingleAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('OrderConciliation');
        $modal = new OrderConciliation();

        Zend_Loader::loadClass('Order');
        $modalOrder = new Order();

        $data_order = json_decode($this->_request->getParam('data_order'), true);

        $data_conciliation = json_decode($this->_request->getParam('data_conciliation'), true);
        
        $better_token = md5(uniqid(rand(), true));

        //separação de lançamentos com key de identificação ID_ORDER
        foreach ($data_order as $row) {
            $idsOrder[] = array(
                $row['id_order']
            );
            $modalOrder->updateTable($row['id_order'], array('token_conciliation' => $better_token));
        }

        foreach ($data_conciliation as $row) {
            $modal->updateTable($row['id_order_conciliation'], array('token_conciliation' => $better_token));
        }

        echo Zend_Json::encode(array('status' => 'success', 'message' => 'O registro foi salvo!'));
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('OrderConciliation');
        $model = new OrderConciliation();

        $data = json_decode($this->_request->getParam("data"), true);
        
        $result = $model->deleteTable($data);
            
        echo Zend_Json::encode($result);
    }

}
