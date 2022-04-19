<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Analytics extends Zend_Db_Table {

    protected $_schema = 'development';
    protected $_name = 'analytics';
    protected $_primary = 'id_analytics';
    protected $_rowClass = 'Analytics';
       
    public function insertTable($data = null) {
        return $this->insert($data);    
    }
    
}
