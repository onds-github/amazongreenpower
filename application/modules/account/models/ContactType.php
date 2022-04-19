<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ContactType extends Zend_Db_Table {

    protected $_schema = 'on_account';
    protected $_name = 'on_contact_type';
    protected $_primary = 'id_contact_type';
    protected $_rowClass = 'ContactType';
    
    public function viewContactType($id_company = null) {
        $sql = $this->getAdapter()->select()->from($this->_schema . '.on_view_contact_type');

        $sql->where('id_company = ?', $id_company);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function insertTable($data = null) {  
        return $this->insert($data);
    }
    
    public function updateTable($id_contact_type = null, $data = null) {        
        $where = $this->getAdapter()->quoteInto('id_contact_type = ?', $id_contact_type);        
        return $this->update($data, $where);         
    }
    
    public function deleteTable($id_contact_type = null) {        
        $where = $this->getAdapter()->quoteInto('id_contact_type = ?', $id_contact_type);        
        return $this->delete($where);         
    }
    
}
