<?php

class Finance_FileController extends Zend_Controller_Action {

    public function init() {
        
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->redirect('/account/access?redirect=' . 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }
    }

    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('File');
        $model = new File();

        $array = $model->selectTable(null, $this->_request->getParam('id_table_parent'), $this->_request->getParam('name_table_parent'));

        foreach ($array as $value) {
            $result[] = array(
                '',
                $value['id_file'],
                $value['link_file'],
                $value['name_file'],
                $value['size_file'],
                $value['datetime_file']
            );
        }

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        
        Zend_Loader::loadClass('File');
        $model = new File();

        $_dir_upload = '/home/6four6digitalmarketing/www/uploads/' . basename($_FILES['file']['name']);

        $path_parts = pathinfo($_dir_upload);

        $_filename = md5(uniqid(rand(), true)) . '.' . $path_parts['extension'];

        $_url_upload = 'http://6four6digitalmarketing.web296.uni5.net/uploads/' . $_filename;

        $data = array(
            'id_table_parent' => $this->_request->getParam('id_table_parent'),
            'name_table_parent' => $this->_request->getParam('name_table_parent'),
            'name_file' => $_FILES['file']['name'],
            'size_file' => $_FILES['file']['size'],
            'type_file' => $_FILES['file']['type'],
            'link_file' => $_url_upload,
            'unlink_file' => '/home/6four6digitalmarketing/www/uploads/' . $_filename,
            'id_company' => Zend_Auth::getInstance()->getIdentity()->id_company_session
        );
        
        $result = $model->insertTable($data);

        move_uploaded_file($_FILES['file']['tmp_name'], '/home/6four6digitalmarketing/www/uploads/' . $_filename);

        echo Zend_Json::encode($result);
    }
    
    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        
        Zend_Loader::loadClass('File');
        $model = new File();
        
        unlink($model->selectTable($this->_request->getParam('q'))[0]['unlink_file']);
        
        $result = $model->deleteTable($this->_request->getParam('q'));
        
        echo Zend_Json::encode($result);
    }
    
}
