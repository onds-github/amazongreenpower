<?php

class Settings_MenuController extends Zend_Controller_Action {

    public function init() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->redirect("/account/access?redirect=" . "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout("layout_webapp");
        $this->view->id_module = 999;
        $this->view->title_page = "Menu";
        $this->view->description_page = "Gerencia menus do site";

        $this->view->headLink();

        $this->view->headScript()
                ->appendFile("/public/modules/settings/script.menu.js");
    }

    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader("Content-Type", "application/json");

        Zend_Loader::loadClass("Menu");
        $model = new Menu();

        $result = $model->selectTable(null, $this->_request->getParam("q"));

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader("Content-Type", "application/json");

        Zend_Loader::loadClass("Menu");
        $model = new Menu();

        $data = array();
        parse_str($this->_request->getParam("data"), $data);
        
        $data['id_menu_group'] = $this->_request->getParam("q");

        $result = $model->insertTable($data);

        echo Zend_Json::encode($result);
    }

    public function updateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader("Content-Type", "application/json");

        Zend_Loader::loadClass("Menu");
        $model = new Menu();

        $data = array();
        parse_str($this->_request->getParam("data"), $data);

        $result = $model->updateTable($this->_request->getParam("q"), $data);

        echo Zend_Json::encode($result);
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader("Content-Type", "application/json");

        Zend_Loader::loadClass("Menu");
        $model = new Menu();

        $data = json_decode($this->_request->getParam("data"), true);

        $result = $model->deleteTable($data);

        echo Zend_Json::encode($result);
    }

}
