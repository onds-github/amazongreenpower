<?php

class Account_ContactController extends Zend_Controller_Action {

    public function init() {

        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->redirect('/account/access?redirect=' . 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_webapp');
        $this->view->id_module = 1;
        $this->view->title_page = 'Cadastros';
        $this->view->description_page = 'Controle de contatos da empresa';

        $this->view->headScript()
                ->appendFile('/public/library/viacep/default.js')
                ->appendFile('/public/modules/account/script.contact.js?v=' . time());
    }

    public function detailsAction() {
        $this->_helper->layout->setLayout('layout_webapp');
        $this->view->id_module = 1;
        $this->view->title_page = 'Contatos';
        $this->view->description_page = 'Controle de contatos da empresa';

        $this->view->headScript()
                ->appendFile('/public/library/viacep/default.js')
                ->appendFile('/public/modules/account/script.contact.js?v=2');
    }

    public function existsDocumentAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Contact');
        $model = new Contact();

        $result = $model->viewContactExistsDocument($this->_request->getParam('q'), Zend_Auth::getInstance()->getIdentity()->id_company_session);

        echo Zend_Json::encode($result ? false : true);
    }

    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Contact');
        $model = new Contact();

        $result = $model->selectTable();

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Contact');
        $model = new Contact();

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

        Zend_Loader::loadClass('Contact');
        $model = new Contact();

        $data = array();
        parse_str($this->_request->getParam("data"), $data);

        $result = $model->updateTable($this->_request->getParam('q'), $data);

        echo Zend_Json::encode($result);
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Contact');
        $model = new Contact();

        $data = json_decode($this->_request->getParam("data"), true);

        $result = $model->deleteTable($data);

        echo Zend_Json::encode($result);
    }

    public function uploadAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        Zend_Loader::loadClass('Contact');
        $model = new Contact();

        $_dir_upload = '/home/wtech/www/uploads/contact/' . basename($_FILES['image_contact']['name']);

        $path_parts = pathinfo($_dir_upload);

        $_filename = md5(uniqid(rand(), true)) . '.' . $path_parts['extension'];

        $_url_upload = 'https://wtech.solutions/uploads/contact/' . $_filename;

        $data = array(
            'image_contact' => $_url_upload,
            'image_contact_unlink' => '/home/wtech/www/uploads/contact/' . $_filename
        );

        $result = $model->updateTable($this->_request->getParam('q'), $data);

        move_uploaded_file($_FILES['image_contact']['tmp_name'], '/home/wtech/www/uploads/contact/' . $_filename);

        echo Zend_Json::encode($result);
    }

    public function removeImageAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        Zend_Loader::loadClass('Contact');
        $model = new Contact();

        $data = array(
            'image_contact' => null,
            'image_contact_unlink' => null
        );

        $result = $model->viewContact(Zend_Auth::getInstance()->getIdentity()->id_company_session, $this->_request->getParam('q'));

        unlink($result[0]['image_contact_unlink']);

        $result = $model->updateTable($this->_request->getParam('q'), $data);

        echo Zend_Json::encode($result);
    }

    public function importAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Contact');
        $model = new Contact();

        $handle = fopen($_FILES['file']['tmp_name'], "r");

        $header = fgetcsv($handle, 1000, ",");
        
        $replacements = array(
            'CNPJCPF' => 'document_contact',
            'RAZAOSOCIAL' => 'name_contact',
            'FANTASIA' => 'nickname_contact',
            'CEP' => 'zipcode_contact',
            'LOGRADOURO' => 'public_place_contact',
            'NUMERO' => 'number_contact',
            'COMPLEMENTO' => 'complement_contact',
            'BAIRRO' => 'district_contact',
            'CIDADE' => 'city_contact',
            'UF' => 'state_contact',
            'TELEFONE1' => 'phone_primary_contact',
            'TELEFONE2' => 'phone_secondary_contact',
            'EMAIL' => 'email_contact',
            'WEBSITE' => 'website_contact',
            'IERG' => 'state_registration_contact',
            'CLIENTE' => 'client_contact',
            'FORNECEDOR' => 'provider_contact'
        );
        
        foreach ($header as $key => $value) {
            if (isset($replacements[$value])) {
                $header[$key] = $replacements[$value];
            }
        }
        
        while ($row = fgetcsv($handle, 1000, ",")) {
            $data = array_combine($header, $row);
            $data['id_company'] = Zend_Auth::getInstance()->getIdentity()->id_company_session;
            
            $result = $model->insertTable($data);
        }
        
        fclose($handle);

        echo Zend_Json::encode($result);
    }

}
