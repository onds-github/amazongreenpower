<?php

class Account_ContactTypeLinkController extends Zend_Controller_Action {

    public function init() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->redirect('/account/access?redirect=' . 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }
    }

    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('ContactType');
        $model_type = new ContactType();

        Zend_Loader::loadClass('ContactTypeLink');
        $model_type_link = new ContactTypeLink();

        foreach ($model_type->viewContactType(Zend_Auth::getInstance()->getIdentity()->id_company_session) as $value) {
            $result[] = array(
                $value['id_contact_type'],
                $value['name_contact_type'],
                ($model_type_link->viewContactTypeLink($this->_request->getParam('q'), $value['id_contact_type'])[0]['id_contact_type_link'] ? : false)
            );
        }

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('ContactTypeLink');
        $model = new ContactTypeLink();

        $data['id_contact_type'] = $this->_request->getParam("id_contact_type");
        $data['id_contact'] = $this->_request->getParam("id_contact");
        
        $result = $model->insertTable($data);

        echo Zend_Json::encode($result);
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        
        Zend_Loader::loadClass('ContactTypeLink');
        $model = new ContactTypeLink();
        
        $result = $model->deleteTable($this->_request->getParam('id_contact_type_link'));
            
        echo Zend_Json::encode($result);
    }
    
}
