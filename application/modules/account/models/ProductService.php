<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ProductService extends Zend_Db_Table {

    protected $_schema = 'account';
    protected $_name = 'product_service';
    protected $_primary = 'id_product_service';
    protected $_rowClass = 'ProductService';
    
    public function viewProductService($id_company = null, $id_product_service_type = null) {
        $sql = $this->getAdapter()->select()->from($this->_schema . '.view_product_service');

        $sql->where('id_company = ?', $id_company);
        
        $sql->where('id_product_service_type = ?', $id_product_service_type);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function insertTable($data = null) {  
        return $this->insert($data);
    }
    
    public function updateTable($q = null, $data = null) {        
        $where = $this->getAdapter()->quoteInto('id = ?', $q);        
        return $this->update($data, $where);         
    }
    
    public function deleteTable($q = null) {        
        $where = $this->getAdapter()->quoteInto('id = ?', $q);        
        return $this->delete($where);         
    }
    
}
