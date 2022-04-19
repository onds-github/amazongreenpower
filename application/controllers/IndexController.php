<?php

class IndexController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function indexAction() {
        $this->view->headLink();
        
        $this->view->title_page = 'Amazon Power';
        
        $this->view->headScript()
                ->appendFile($this->view->baseUrl('public/modules/application/js/home.js'));
    }
    
}
