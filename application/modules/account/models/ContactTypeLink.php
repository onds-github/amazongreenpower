<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ContactTypeLink extends Zend_Db_Table {

    protected $_schema = 'on_account';
    protected $_name = 'on_contact_type_link';
    protected $_primary = 'id_contact_type_link';
    protected $_rowClass = 'ContactTypeLink';
    
    public function viewContactTypeLinkDropdownProvider($id_company = null, $query = null) {
        $sql = $this->getAdapter()->select()->from($this->_schema . '.on_view_contact_type_link_dropdown_provider');

        $sql->where('id_company = ?', $id_company);
        
        if ($query != '') {
            $sql->where('nickname_contact ilike ?', '%' . $query . '%');
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function viewContactTypeLinkDropdownClient($id_company = null, $query = null) {
        $sql = $this->getAdapter()->select()->from($this->_schema . '.on_view_contact_type_link_dropdown_client');

        $sql->where('id_company = ?', $id_company);
        
        if ($query != '') {
        $sql->where('nickname_contact ilike ?', '%' . $query . '%');
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function viewContactTypeLink($id_contact = null, $id_contact_type = null) {
        $sql = $this->getAdapter()->select()->from($this->_schema . '.on_view_contact_type_link');

        $sql->where('id_contact = ?', $id_contact);
        
        if ($id_contact_type != '') {
            $sql->where('id_contact_type = ?', $id_contact_type);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function insertTable($data = null) {
        try {
            $this->getAdapter()->query('SET on_session.on_cookie_key = "' . $_COOKIE['on_cookie_key'] . '"');  
            $this->insert($data);
            return array(status => 'success', message => 'O registro foi salvo!');
        } catch (Exception $ex) {
            return array(status => 'warning', message => 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato com o suporte técnico!', error => $ex->getMessage());
        }
    }
    
    public function deleteTable($_primary = null) {        
        try {
            $this->getAdapter()->query('SET on_session.on_cookie_key = "' . $_COOKIE['on_cookie_key'] . '"');   
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $_primary);        
            $this->delete($where);
            return array(status => 'success', message => 'O registro foi excluído!');
        } catch (Exception $ex) {
            return array(status => 'warning', message => 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato com o suporte técnico!', error => $ex->getMessage());
        }   
    }
    
}
