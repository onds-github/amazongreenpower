<?php

class Account_UserController extends Zend_Controller_Action {

    public function init() {
        
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->redirect('/account/access?redirect=' . 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_webapp');
        $this->view->id_module = 100;
        $this->view->title_page = 'Info. da Conta';
        $this->view->description_page = 'Gerenciamento de informações';
        
        $this->view->headScript()
                ->appendFile('/public/modules/account/user/script.default.js')
                ->appendFile('/public/modules/account/user/script.security.js');
        
    }

    public function selectAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('User');
        $model = new User();
        
        $result = $model->selectTable(Zend_Auth::getInstance()->getIdentity()->id_user);

        echo Zend_Json::encode($result);
    }

    public function updateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('User');
        $model = new User();

        $data = array();
        parse_str($this->_request->getParam("data"), $data);

        if ($data['menu_user']) {
            $data['menu_user'] = Zend_Auth::getInstance()->getIdentity()->menu_user == 0 ? 1 : 0;
            Zend_Auth::getInstance()->getIdentity()->menu_user = $data['menu_user'];
        }
        
        $result = $model->updateTable(Zend_Auth::getInstance()->getIdentity()->id_user, $data);
        
        if ($data['id_company_session'] && $result['status'] == 'success') {
            Zend_Auth::getInstance()->getIdentity()->id_company_session = $data['id_company_session'];
        }
        
        if ($data['name_user'] && $result['status'] == 'success') {
            Zend_Auth::getInstance()->getIdentity()->name_user = $data['name_user'];
        }
        
        if ($data['menu_user'] && $result['status'] == 'success') {
            $result['menu_user'] = $data['menu_user'];
        }
        
        echo Zend_Json::encode($result);
    }

    public function uploadAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        Zend_Loader::loadClass('User');
        $model = new User();

        $_dir_upload = '/home/wtech/www/uploads/profile/' . basename($_FILES['image_user']['name']);

        $path_parts = pathinfo($_dir_upload);

        $_filename = md5(uniqid(rand(), true)) . '.' . $path_parts['extension'];

        $_url_upload = 'https://wtech.solutions/uploads/profile/' . $_filename;

        $data = array(
            'image_user' => $_url_upload,
            'image_user_unlink' => '/home/wtech/www/uploads/profile/' .$_filename
        );
        Zend_Auth::getInstance()->getIdentity()->image_user = $_url_upload;
        $result = $model->updateTable(Zend_Auth::getInstance()->getIdentity()->id_user, $data);

        move_uploaded_file($_FILES['image_user']['tmp_name'], '/home/wtech/www/uploads/profile/' . $_filename);

        echo Zend_Json::encode($result);
    }
    
    public function removeImageAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        Zend_Loader::loadClass('User');
        $model = new User();

        $data = array(
            'image_user' => null,
            'image_user_unlink' => null
        );
        
        $result = $model->viewUser(Zend_Auth::getInstance()->getIdentity()->id_company_session, Zend_Auth::getInstance()->getIdentity()->id_user);
        
        unlink($result[0]['image_user_unlink']);
        
        $result = $model->updateTable(Zend_Auth::getInstance()->getIdentity()->id_user, $data);

        echo Zend_Json::encode($result);
    }
    
}
