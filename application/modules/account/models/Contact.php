<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Contact extends Zend_Db_Table {

    protected $_name = 'on_contact';
    protected $_primary = 'id_contact';
    protected $_rowClass = 'Contact';
    
    public function viewEmailExists($a = null, $b = null) {
        $sql = $this->getAdapter()->select()->from('on_contact');

        if ($a != '') {
            $sql->where('email = ?', $a);
        }
        
        if ($b != '') {
            $sql->where('id_project = ?', $b);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function viewContactDropdownClient($id_company = null, $query_contact = null) {
        $sql = $this->getAdapter()->select()->from('on_contact');

        $sql->where('id_company = ?', $id_company);
        
        if ($query_contact != '') {
        $sql->where('query_contact ilike ?', '%' . $query_contact . '%');
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function viewContactDropdownProvider($id_company = null) {
        $sql = $this->getAdapter()->select()->from('on_contact');

        $sql->where('id_company = ?', $id_company);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectTableDropdown($query = null) {
        $sql = $this->getAdapter()->select()->from('on_contact');

        $sql->where('id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        if ($query != '') {
            $sql->where('nickname_contact ilike ?', '%' . $query . '%');
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function viewContact($id_company = null, $_primary = null) {
        $sql = $this->getAdapter()->select()->from('on_contact');

        $sql->where('id_company = ?', $id_company);
        
        if ($_primary != '') {
            $sql->where($this->_primary . ' = ?', $_primary);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectTable($_primary = null) {
        $sql = $this->getAdapter()->select()->from('on_contact');

        if ($_primary != '') {
            $sql->where($this->_primary . ' = ?', $_primary);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function insertTable($data = null) {
        try {
            $returning = $this->insert($data);
            return array(status => 'success', message => 'O registro foi salvo!', returning => $returning);
        } catch (Exception $ex) {
            return array(status => 'warning', message => 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato com o suporte técnico!', error => $ex->getMessage());
        }
    }
    
    public function updateTable($_primary = null, $data = null) {    
        try {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $_primary);        
            $this->update($data, $where);
            return array(status => 'success', message => 'O registro foi salvo!');
        } catch (Exception $ex) {
            return array(status => 'warning', message => 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato com o suporte técnico!', error => $ex->getMessage());
        }
    }
    
    public function deleteTable($_primary = null) {        
        try {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' IN(?)', $_primary);        
            $this->delete($where);
            return array(status => 'success', message => 'O registro foi excluído!');
        } catch (Exception $ex) {
            return array(status => 'warning', message => 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato com o suporte técnico!', error => $ex->getMessage());
        }   
    }
    
}
