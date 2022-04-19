<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class LogginSession extends Zend_Db_Table {

    protected $_schema = 'account';
    protected $_name = 'loggin_session';
    protected $_primary = 'id_loggin_session';
    protected $_rowClass = 'LogginSession';
    
    public function viewLogginSession() {
        $sql = $this->getAdapter()->select()->from($this->_schema . '.view_loggin_session');
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function insertTable($data = null) {
        $this->insert($data);
    }
     
}
