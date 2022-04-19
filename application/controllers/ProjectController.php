<?php

class ProjectController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function indexAction() {
        
        $this->view->title = 'Página inicial';
        $this->view->page = '613f528b6c338d548dac8d1e';
        
        
        $this->view->headLink();
        
        $this->view->headScript()
                ->appendFile($this->view->baseUrl('public/modules/application/js/home.js'));
    }
    
}
