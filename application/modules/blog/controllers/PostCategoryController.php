<?php

class Blog_PostCategoryController extends Zend_Controller_Action {

    public function init() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->redirect("/account/access?redirect=" . "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout("layout_webapp");
        $this->view->id_module = 999;
        $this->view->title_page = "Blog - Categorias";
        $this->view->description_page = "Gerencia publicações";

        $this->view->headLink()
//                ->appendStylesheet('/public/assets/addons/quill/quill.snow.css')
//                ->appendStylesheet('/public/assets/addons/quill/quill.bubble.css')
//                ->appendStylesheet('/public/assets/addons/quill/quill.core.css')
                ;

        $this->view->headScript()
                ->appendFile('/public/assets/addons/oncrud/script.onUpload.js')
                ->appendFile("https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest")
//                ->appendFile("/public/assets/addons/quill/quill.core.js")
                ->appendFile("/public/modules/blog/script.post-category.js");
        
    }

    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader("Content-Type", "application/json");

        Zend_Loader::loadClass("PostCategory");
        $model = new PostCategory();

        $result = $model->selectTable();

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader("Content-Type", "application/json");

        Zend_Loader::loadClass("PostCategory");
        $model = new PostCategory();

        $data = array();
        parse_str($this->_request->getParam("data"), $data);

        $data['slug_post_category'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['title_post_category'])));
        
        $result = $model->insertTable($data);

        echo Zend_Json::encode($result);
    }

    public function updateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader("Content-Type", "application/json");

        Zend_Loader::loadClass("PostCategory");
        $model = new PostCategory();

        $data = array();
        parse_str($this->_request->getParam("data"), $data);
        
        $data['slug_post_category'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['title_post_category'])));
        
        $result = $model->updateTable($this->_request->getParam("q"), $data);

        echo Zend_Json::encode($result);
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader("Content-Type", "application/json");

        Zend_Loader::loadClass("PostCategory");
        $model = new PostCategory();

        $data = json_decode($this->_request->getParam("data"), true);
        $result = $model->deleteTable($data);

        echo Zend_Json::encode($result);
    }

}
