<?php

class Account_LogginSessionController extends Zend_Controller_Action {

    public function init() {
        
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->redirect('/account/access?redirect=' . 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }
    }
    
    #página html

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_default');
        $this->view->title = 'Histórico';

        $this->view->headScript()
                ->appendFile($this->view->baseUrl('public/modules/account/js/loggin-session.js'));
    }
    
    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('LogginSession');
        $model = new LogginSession();

        foreach ($model->viewLogginSession() as $value) {
            $result[] = array(
                $value['id'],
                array(
                    name_user => $value['name_user'],
                    email => $value['email'],
                    whatsapp => $value['whatsapp']
                ),
                array(
                    mobile => $value['mobile'],
                    country => $value['country'],
                    state => $value['state'],
                    city => $value['city']
                ),
                $value['date_time']
            );
        }

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

}
