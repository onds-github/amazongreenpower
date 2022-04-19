<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class User extends Zend_Db_Table {

    protected $_name = 'on_user';
    protected $_primary = 'id_user';
    protected $_rowClass = 'User';
    
    public function viewDropdownUserBuyer($query = null) {
        $sql = $this->getAdapter()->select()->from($this->_schema . '.view_dropdown_user_buyer');
        
        if ($query != '') {
            $sql->where('query ilike ?', '%' . $query . '%');
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function viewUserResetSenha($a = null) {
        $sql = $this->getAdapter()->select()->from($this->_schema . '.view_user');
        
        if ($a != '') {
            $sql->where('reset_password = ?', $a);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function viewUserExistsEmail($email = null) {
        $sql = $this->getAdapter()->select()->from($this->_schema . '.on_view_user');
        
        $sql->where('email_user = ?', $email);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function viewUserExistsDocument($a = null) {
        $sql = $this->getAdapter()->select()->from($this->_schema . '.view_user');
        
        if ($a != '') {
            $sql->where('documento = ?', $a);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function viewUserDocument($a = null) {
        $sql = $this->getAdapter()->select()->from($this->_schema . '.view_user');
        
        if ($a != '') {
            $sql->where('documento = ?', $a);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function viewUsers($id_company = null) {
        $sql = $this->getAdapter()->select()->from($this->_schema . '.on_view_user');
        
        if ($id_company != '') {
            $sql->where('id_company_session = ?', $id_company);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function viewUser($_primary = null) {
        $sql = $this->getAdapter()->select()->from($this->_schema . '.on_view_user');
        
        if ($_primary != '') {
            $sql->where($this->_primary . ' = ?', $_primary);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function viewUserDetails($id_user = null) {
        $sql = $this->getAdapter()->select()->from($this->_schema . '.view_user');
        
        $sql->where('id_user = ?', $id_user);

        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function viewClientDropdown($name = null) {
        $sql = $this->getAdapter()->select()->from($this->_schema . '.view_user');
        
        if ($name != '') {
            $sql->where('name ilike ?', '%' . $name . '%');
        }
        
        $sql->where('id_user_role = 3');
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function viewClient($id = null) {
        $sql = $this->getAdapter()->select()->from($this->_schema . '.view_user');
        
        if ($id != '') {
            $sql->where('id = ?', $id);
        }
        
        $sql->where('id_user_role = 3');
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function viewPartnerDropdown($name = null) {
        $sql = $this->getAdapter()->select()->from($this->_schema . '.view_user');
        
        if ($name != '') {
            $sql->where('name ilike ?', '%' . $name . '%');
        }
        
        $sql->where('id_user_role = 2');
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function viewPartner($id = null) {
        $sql = $this->getAdapter()->select()->from($this->_schema . '.view_user');
        
        if ($id != '') {
            $sql->where('id = ?', $id);
        }
        
        $sql->where('id_user_role = 2');
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectTable($_primary = null) {
        $sql = $this->getAdapter()->select()->from('on_user');
        
        $sql->where('id_company_session = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        if ($_primary != '') {
            $sql->where($this->_primary . ' = ?', $_primary);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function insertTable($data = null) {
        try {
            $this->insert($data);
            return array(status => 'success', message => 'O registro foi salvo!');
        } catch (Exception $ex) {
            return array(status => 'warning', message => 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato com o suporte técnico!', error => $ex->getMessage());
        }
    }
    
    public function updateTable($id_user = null, $data = null) {
        try {
            $where = $this->getAdapter()->quoteInto('id_user = ?', $id_user);        
            $this->update($data, $where);  
            return array(status => 'success', message => 'O registro foi salvo');
        } catch (Exception $ex) {
            return array(status => 'warning', message => 'Ocorreu um erro inesperado ' . $ex);
        }         
    }
    
    public function updateTableReset($reset_password = null, $data = null) {   
        try {
            $this->getAdapter()->query('SET on_session.on_cookie_key = "' . $_COOKIE['on_cookie_key'] . '"');   
            $where = $this->getAdapter()->quoteInto('reset_password = ?', $reset_password);      
            $this->update($data, $where);   
            return array(status => 'success', message => 'O registro foi salvo');
        } catch (Exception $ex) {
            return array(status => 'warning', message => 'Ocorreu um erro inesperado ' . $ex);
        }         
    }
    
    public function updateTableForgot($id = null, $data = null) {   
        try {
            $this->getAdapter()->query('SET on_session.on_cookie_key = "' . $_COOKIE['on_cookie_key'] . '"');  
            $where = $this->getAdapter()->quoteInto('email = ?', $id);        
            $this->update($data, $where);   
            return array(status => 'success', message => 'O registro foi salvo');
        } catch (Exception $ex) {
            return array(status => 'warning', message => 'Ocorreu um erro inesperado ' . $ex);
        }              
    }
    
    public function deleteTable($id = null) { 
        try {
            $where = $this->getAdapter()->quoteInto('id_user in(?)', $id);        
            $this->delete($where);
            return array(status => 'success', message => 'O registro foi salvo');
        } catch (Exception $ex) {
            return array(status => 'warning', message => 'Ocorreu um erro inesperado ' . $ex);
        }     
    }
    
}
