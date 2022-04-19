<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class OrderIn extends Zend_Db_Table {

    protected $_name = 'on_order_in';
    protected $_primary = 'id_order_in';
    protected $_rowClass = 'OrderIn';
    
    public function selectTableContactDropdown($query = null) {
        $sql = $this->getAdapter()->select()->from('on_contact');

        if ($query != '') {
            $sql->where('nickname_contact ilike ?', '%' . $query . '%');
        }
        
        $sql->where('client_contact = 1');
        
        $sql->where('id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectTableAccountDropdown($query= null) {
        $sql = $this->getAdapter()->select()->from('on_account');

        if ($query != '') {
            $sql->where('name_account ilike ?', '%' . $query . '%');
        }
        
        $sql->where('id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectTableCostCenterDropdown($query= null) {
        $sql = $this->getAdapter()->select()->from('on_cost_center');

        if ($query != '') {
            $sql->where('name_cost_center ilike ?', '%' . $query . '%');
        }
        
        $sql->where('id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectTableChartAccountsDropdown($query= null) {
        $sql = $this->getAdapter()->select()->from('on_chart_accounts');

        if ($query != '') {
            $sql->where('name_chart_accounts ilike ?', '%' . $query . '%');
        }
        
        $sql->where('id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function viewOrderInDashboard($id_company = null) {
        $sql = $this->getAdapter()->select()->from('on_order_in');

        $sql->where('id_company = ?', $id_company);
        
        $sql->where("situation_filter = 'bills_to_receive' OR situation_filter = 'late'");
        
        $sql->limit(3);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectTable($_primary = null) {
        $sql = $this->getAdapter()->select()->from(array('_in' => 'on_order_in'), array('_in.id_order_in', '_in.reference_order_in', '_in.description_order_in', '_in.date_issue_order_in', '_in.date_competence_order_in', '_in.date_due_order_in', '_in.date_payment_order_in', '_in.situation_order_in', '_in.price_discount_order_in', '_in.price_addition_order_in', '_in.price_fees_order_in', '_in.price_order_in', '_in.id_contact', '_in.id_account', '_in.id_chart_accounts', '_in.id_cost_center', '(_in.price_order_in - _in.price_discount_order_in + _in.price_fees_order_in + _in.price_addition_order_in) AS total_order_in'))
                ->joinLeft(array('_co' => 'on_contact'), '_in.id_contact = _co.id_contact', array('_co.nickname_contact', '_co.document_contact'))
                ->joinLeft(array('_cc' => 'on_cost_center'), '_in.id_cost_center = _cc.id_cost_center', array('_cc.name_cost_center', '_cc.icon_cost_center', '_cc.color_cost_center'))
                ->joinLeft(array('_ca' => 'on_chart_accounts'), '_in.id_chart_accounts = _ca.id_chart_accounts', array('_ca.name_chart_accounts'))
                ->joinLeft(array('_ac' => 'on_account'), '_in.id_account = _ac.id_account', array('_ac.name_account'))
            ;


        $sql->where('_in.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        $sql->where('_in.transfer_order_in = ?', false);
        
        if ($_primary != '') {
            $sql->where('_in.id_order_in = ?', $_primary);
        }
        
        $sql->where('_in.date_due_order_in '
                . 'BETWEEN \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_min . '\' '
                . 'AND \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_max . '\'');
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function insertTable($data = null) {
        try {
            $returning = $this->insert($data);
            return array('status' => 'success', 'message' => 'O registro foi salvo!', 'returning' => $returning);
        } catch (Exception $ex) {
            return array('status' => 'warning', 'message' => 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato com o suporte técnico!', error => $ex->getMessage());
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
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $_primary);        
            $this->delete($where);
            return array(status => 'success', message => 'O registro foi excluído!');
        } catch (Exception $ex) {
            return array(status => 'warning', message => 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato com o suporte técnico!', error => $ex->getMessage());
        }   
    }
    
}
