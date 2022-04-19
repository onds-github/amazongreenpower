<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class OrderOut extends Zend_Db_Table {

    protected $_name = 'on_order_out';
    protected $_primary = 'id_order_out';
    protected $_rowClass = 'OrderOut';
    
    public function selectTableContactDropdown($query = null) {
        $sql = $this->getAdapter()->select()->from('on_contact');

        if ($query != '') {
            $sql->where('nickname_contact ilike ?', '%' . $query . '%');
        }
        
        $sql->where('provider_contact = 1');
        
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
    
    public function viewOrderOutDashboard($id_company = null) {
        $this->getAdapter()->query('set lc_numeric to \'pt_BR\'');
        $this->getAdapter()->query('set lc_monetary to \'pt_BR\'');
        
        $sql = $this->getAdapter()->select()->from($this->_schema . '.on_view_order_out');

        $sql->where('id_company = ?', $id_company);
        
        $sql->where("situation_filter = 'bills_to_pay' OR situation_filter = 'late'");
        
        $sql->limit(3);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectTable($_primary = null) {
        $sql = $this->getAdapter()->select()->from(array('_out' => 'on_order_out'), array('_out.id_order_out', '_out.reference_order_out', '_out.description_order_out', '_out.date_issue_order_out', '_out.date_competence_order_out', '_out.date_due_order_out', '_out.date_payment_order_out', '_out.situation_order_out', '_out.price_discount_order_out', '_out.price_addition_order_out', '_out.price_fees_order_out', '_out.price_order_out', '_out.id_contact', '_out.id_account', '_out.id_chart_accounts', '_out.id_cost_center', '(_out.price_order_out - _out.price_discount_order_out + _out.price_fees_order_out + _out.price_addition_order_out) AS total_order_out'))
                ->joinLeft(array('_co' => 'on_contact'), '_out.id_contact = _co.id_contact', array('_co.nickname_contact', '_co.document_contact'))
                ->joinLeft(array('_cc' => 'on_cost_center'), '_out.id_cost_center = _cc.id_cost_center', array('_cc.name_cost_center', '_cc.icon_cost_center', '_cc.color_cost_center'))
                ->joinLeft(array('_ca' => 'on_chart_accounts'), '_out.id_chart_accounts = _ca.id_chart_accounts', array('_ca.name_chart_accounts'))
                ->joinLeft(array('_ac' => 'on_account'), '_out.id_account = _ac.id_account', array('_ac.name_account'))
            ;


        $sql->where('_out.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        $sql->where('_out.transfer_order_out = ?', false);
        
        if ($_primary != '') {
            $sql->where('_out.id_order_out = ?', $_primary);
        }
        
        $sql->where('_out.date_due_order_out '
                . 'BETWEEN \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_min . '\' '
                . 'AND \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_max . '\'');
        
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
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $_primary);        
            $this->delete($where);
            return array(status => 'success', message => 'O registro foi excluído!');
        } catch (Exception $ex) {
            return array(status => 'warning', message => 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato com o suporte técnico!', error => $ex->getMessage());
        }   
    }
    
}
