<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Posts extends Zend_Db_Table {

    protected $_name = 'wo_posts';
    protected $_primary = 'ID';
    protected $_rowClass = 'Posts';
    
    protected function _setupDatabaseAdapter() {
        $this->_db = Zend_Registry::get('wordpress');
    }
    
    public function view_posts() {
        $sql = $this->getAdapter()->select()->from('view_posts');
        
        $sql->limit(3);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
}
