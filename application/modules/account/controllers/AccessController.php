<?php

class Account_AccessController extends Zend_Controller_Action {

    public function init() {

    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_blank');
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector->gotoRoute(array('module' => 'account', 'controller' => 'index', 'action' => 'index'));
        }

        $this->view->title = 'Acesso ao sistema';

        $this->view->headLink();

        $this->view->headScript()
                ->appendFile($this->view->baseUrl('public/modules/account/js/access/index.js'));
    }

    public function existsDocumentAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass("User");
        $model = new User();

        $result = $model->viewUserExistsDocument($this->_request->getParam("field"));

        echo Zend_Json::encode($result ? true : false);
    }
    
    public function requestAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        $data = array();
        parse_str($this->_request->getParam('data'), $data);
        
        switch ($this->process($data['email_user'], $data['password_user'])) {
            case true:
                $array = array(status => 'success', message => 'Login autorizado, você será redirecionado.');
                break;
            case false:
                $array = array(status => 'danger', message => 'As informações de login estão incorretas ou a conta está bloqueada');
                break;
        }

        echo Zend_Json::encode($array);
    }

    protected function process($email, $password) {
        $adapter = $this->getAuthAdapter();
        $adapter->setIdentity($email);
        $adapter->setCredential($password);
        $adapter->setCredentialTreatment('MD5(?)');

        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($adapter);
        if ($result->isValid()) {
            $user = $adapter->getResultRowObject();
            $auth->getStorage()->write($user);
            return true;
        } else {
            return false;
        }
    }

    protected function getAuthAdapter() {
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);

        $authAdapter->setTableName('on_user');
        $authAdapter->setIdentityColumn('email_user');
        $authAdapter->setCredentialColumn('password_user');

        return $authAdapter;
    }

    public function outAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector->gotoRoute(array('module' => 'account', 'controller' => 'access', 'action' => 'index'));

    }

}
