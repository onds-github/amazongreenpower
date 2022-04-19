<?php

class PostController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function indexAction() {
        $this->view->title = 'Página inicial';
        $this->view->page = '613f528b6c338ddbc8ac8cde';
        
        $this->view->headLink();
        
        $this->view->headScript()
                ->appendFile($this->view->baseUrl('public/modules/application/js/home.js'));
    }
    
    public function pAction() {
        $this->view->title = 'Página inicial';
        $this->view->page = '613f528b6c338d1e19ac8cd2';
        
        
        $this->view->headLink();
        
        $this->view->headScript()
                ->appendFile($this->view->baseUrl('public/modules/application/js/home.js'));
    }
    
}
