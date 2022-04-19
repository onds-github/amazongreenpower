<?php

class AboutController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function indexAction() {
        
        $this->view->title = 'Página inicial';
        
        $this->view->page = '613f528b6c338df4d4ac8cc3';
        
        $this->view->headLink();
        
        $this->view->headScript()
                ->appendFile($this->view->baseUrl('public/modules/application/js/home.js'));
    }
    
}
