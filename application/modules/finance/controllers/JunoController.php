<?php

class Finance_JunoController extends Zend_Controller_Action {

    public function init() {
        
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->redirect('/account/access?redirect=' . 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }
    }

    public function chargesAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        $order = $this->_select($this->_request->getParam('q'));

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://sandbox.boletobancario.com/api-integration/charges');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"charge\":{\"description\":\"{$order['description_order_in']}\",\"references\":[\"{$this->_request->getParam('q')}\"],\"amount\":" . preg_replace('/[^0-9.,]/', '', $order['total_order_in']) . ",\"dueDate\":\"{$order['date_due_order_in']}\",\"installments\":1,\"maxOverdueDays\":0,\"fine\":0,\"interest\":\"0.00\",\"discountAmount\":\"0.00\",\"discountDays\":-1,\"paymentTypes\":[\"BOLETO\"],\"paymentAdvance\":true},\"billing\":{\"name\":\"{$order['nickname_contact']}\",\"document\":\"" . preg_replace('/[^0-9]/', '', $order['document_contact']) . "\",\"email\":\"{$order['email_contact']}\",\"phone\":\"string\",\"notify\":true}}");

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'X-Api-Version: 2';
        $headers[] = 'X-Resource-Token: ' . Zend_Auth::getInstance()->getIdentity()->token_private_juno_company;
        $headers[] = 'Authorization: Bearer ' . $this->_token();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $array = json_decode(curl_exec($ch), true);
        if (curl_errno($ch)) {
            $result = array(status => 'warning', message => curl_error($ch));
        } else {
            switch (intval($array['status'])) {
                case 400:
                    $result = array(status => 'warning', message => $array['details'][0]['message']);
                break;

                default:
                    $result = $this->_update($this->_request->getParam('q'), $array);
                    break;
            }
        
        }
        curl_close($ch);
        
        echo Zend_Json::encode($result);
    }

    public function _token() {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, 'https://sandbox.boletobancario.com/authorization-server/oauth/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        
        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $headers[] = 'Authorization: Basic ' . base64_encode(Zend_Auth::getInstance()->getIdentity()->client_id_juno_company . ':' . Zend_Auth::getInstance()->getIdentity()->client_secret_juno_company);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = json_decode(curl_exec($ch), true);
        
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        
        return $result['access_token'];
    }

    public function _select($_primary = null) {
        Zend_Loader::loadClass('OrderIn');
        $model = new OrderIn();
        
        $result = $model->selectOrderIn($_primary);
        
        return $result[0];
    }

    public function _update($_primary = null, $array = null) {
        Zend_Loader::loadClass('OrderIn');
        $model = new OrderIn();
        
        foreach ($array['_embedded']['charges'] as $value) {
            $data['code_payment_order_in'] = $value['id'];
            $data['link_payment_order_in'] = $value['link'];
        }

        $result = $model->updateTable($_primary, $data);
        
        return $result;
    }

}
