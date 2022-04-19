<?php

class Website_PartnerController extends Zend_Controller_Action {

    public function init() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->redirect("/account/access?redirect=" . "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout("layout_webapp");
        $this->view->id_module = 999;
        $this->view->title_page = "Parceiros";
        $this->view->description_page = "Gerencia publicações";

        $this->view->headLink()
                ;

        $this->view->headScript()
                ->appendFile('/public/assets/addons/oncrud/script.onUpload.js')
                ->appendFile("https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest")
                ->appendFile("/public/modules/website/script.partner.js");
        
    }

    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader("Content-Type", "application/json");

        Zend_Loader::loadClass("Partner");
        $model = new Partner();

        $result = $model->selectTable();

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader("Content-Type", "application/json");

        Zend_Loader::loadClass("Partner");
        $model = new Partner();

        $data = array();
        parse_str($this->_request->getParam("data"), $data);

        $result = $model->insertTable($data);

        echo Zend_Json::encode($result);
    }

    public function updateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader("Content-Type", "application/json");

        Zend_Loader::loadClass("Partner");
        $model = new Partner();

        $data = array();
        parse_str($this->_request->getParam("data"), $data);
        
        if ($this->_request->getParam("json")) {
            $data['description_partner'] = strval($this->_request->getParam("json"));
        }

        $result = $model->updateTable($this->_request->getParam("q"), $data);

        echo Zend_Json::encode($result);
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader("Content-Type", "application/json");

        Zend_Loader::loadClass("Partner");
        $model = new Partner();

        $data = json_decode($this->_request->getParam("data"), true);
        $result = $model->deleteTable($data);

        echo Zend_Json::encode($result);
    }

}
