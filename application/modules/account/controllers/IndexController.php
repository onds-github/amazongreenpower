<?php

class Account_IndexController extends Zend_Controller_Action {

    public function init() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector->gotoRoute(array('module' => 'account', 'controller' => 'access', 'action' => 'index'));
        }
    }

    public function indexAction() {
            $this->_helper->redirector->gotoRoute(array('module' => 'account', 'controller' => 'dashboard', 'action' => 'index'));
    }

}
