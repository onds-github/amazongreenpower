<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class OrderConciliation extends Zend_Db_Table {

    protected $_name = 'on_order_conciliation';
    protected $_primary = 'id_order_conciliation';
    protected $_rowClass = 'OrderConciliation';
    
    public function selectTableAccountDropdown($query= null) {
        $sql = $this->getAdapter()->select()->from('on_account');

        if ($query != '') {
            $sql->where('name_account ilike ?', '%' . $query . '%');
        }
        
        $sql->where('id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectOrderConciliation() {
        $sql = $this->getAdapter()->select()->from(array('_or' => 'on_order_conciliation'), array('_or.id_order_conciliation', '_or.description_order_conciliation as description_order', '_or.date_order_conciliation as date_order', '_or.price_order_conciliation as price_order', '_or.token_conciliation', '_or.id_type_order'))
        ;

        $sql->where('_or.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        $sql->where('_or.token_conciliation IS NULL');
        
        $sql->where('_or.date_order_conciliation '
                . 'BETWEEN \'' . Zend_Auth::getInstance()->getIdentity()->filter_conciliation_period_min . '\' '
                . 'AND \'' . Zend_Auth::getInstance()->getIdentity()->filter_conciliation_period_max . '\'');
        
        if (Zend_Auth::getInstance()->getIdentity()->filter_conciliation_id_account != '') {
            $sql->where('_or.id_account = ?', Zend_Auth::getInstance()->getIdentity()->filter_conciliation_id_account);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
   
    public function selectOrder() {
        $sql = $this->getAdapter()->select()->from(array('_or' => 'on_order'), array('_or.id_order', '_or.description_order', '_or.id_type_order', '_or.date_payment_order as date_order', '_or.situation_order', '_or.price_down', '_or.token_conciliation'))
                ;

        if ($_primary != '') {
            $sql->where('_or.id_order = ?', $_primary);
        }
        
        $sql->where('_or.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        $sql->where('_or.situation_order = ?', 1);
        
        $sql->where('_or.cashier_order_check = ?', 1);
        
        $sql->where('_or.date_payment_order '
                . 'BETWEEN \'' . Zend_Auth::getInstance()->getIdentity()->filter_conciliation_period_min . '\' '
                . 'AND \'' . Zend_Auth::getInstance()->getIdentity()->filter_conciliation_period_max . '\'');
        
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
    
    public function updateTable($_primary = null, $data = null) {    
        try {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $_primary);        
            $this->update($data, $where);
            return array(status => 'success', message => 'O registro foi salvo!');
        } catch (Exception $ex) {
            return array(status => 'warning', message => 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato com o suporte técnico!', error => $ex->getMessage());
        }
    }
    
    public function updateTableConciliation($token_conciliation = null, $data = null) {    
        try {
            $where = $this->getAdapter()->quoteInto('token_conciliation = ?', $token_conciliation);        
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
