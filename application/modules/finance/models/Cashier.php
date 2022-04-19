<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Cashier extends Zend_Db_Table {

    protected $_name = 'on_order';
    protected $_primary = 'id_order';
    protected $_rowClass = 'Cashier';
    
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
    
    public function viewDashboardOrderInBillsToReceive($id_company = null) {
        $this->getAdapter()->query('set lc_numeric to \'pt_BR\'');
        $this->getAdapter()->query('set lc_monetary to \'pt_BR\'');
        
        $sql = $this->getAdapter()->select()->from($this->_schema . '.view_dashboard_order_in_bills_to_receive');

        $sql->where('id_company = ?', $id_company);
        
        $sql->limit(3);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function viewDashboardOrderInLate($id_company = null) {
        $this->getAdapter()->query('set lc_numeric to \'pt_BR\'');
        $this->getAdapter()->query('set lc_monetary to \'pt_BR\'');
        
        $sql = $this->getAdapter()->select()->from($this->_schema . '.view_dashboard_order_in_late');

        $sql->where('id_company = ?', $id_company);
        
        $sql->limit(3);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function viewCashierDetails($_primary = null) {
        
        $sql = $this->getAdapter()->select()->from(array('_out' => 'on_order_out'), array('_out.id_order_out', '_out.reference_order_out', '_out.description_order_out', '_out.situation_order_out', '_out.date_due_order_out', '_out.price_order_out'))
                ->joinLeft(array('_co' => 'on_contact'), '_out.id_contact = _co.id_contact', array('_co.nickname_contact', '_co.document_contact'))
                ->joinLeft(array('_ac' => 'on_account'), '_out.id_account = _ac.id_account', array('_ac.name_account as name_account'))
            ;


        $sql->where('_out.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        $sql->where('_out.transfer_order_out = ?', false);
        
        $sql->where('_out.cashier_order_out = ?', true);
        
        if ($_primary != '') {
            $sql->where('_out.id_order_out_cashier = ?', $_primary);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectTableOrder($cashier_id_order = null) {
        $sql = $this->getAdapter()->select()->from(array('_or' => 'on_order'), array('_or.id_order', '_or.reference_order', '_or.description_order', '_or.id_type_order', '_or.date_issue_order', '_or.date_competence_order', '_or.date_due_order', '_or.date_payment_order', '_or.situation_order', '_or.price_discount_order', '_or.price_addition_order', '_or.price_fees_order', '_or.price_order', '_or.id_contact', '_or.id_account', '_or.id_chart_accounts', '_or.id_cost_center', '(_or.price_order - _or.price_discount_order + _or.price_fees_order + _or.price_addition_order) AS total_order', '(select count(_fi.id_file) from on_file _fi where _fi.id_table_parent = _or.id_order AND _fi.name_table_parent = \'on_order\') as files_order', '_or.cashier_order_check'))
                ->joinLeft(array('_co' => 'on_contact'), '_or.id_contact = _co.id_contact', array('_co.nickname_contact', '_co.document_contact'))
                ->joinLeft(array('_cc' => 'on_cost_center'), '_or.id_cost_center = _cc.id_cost_center', array('_cc.name_cost_center', '_cc.icon_cost_center', '_cc.color_cost_center'))
                ->joinLeft(array('_ca' => 'on_chart_accounts'), '_or.id_chart_accounts = _ca.id_chart_accounts', array('_ca.name_chart_accounts'))
                ->joinLeft(array('_ac' => 'on_account'), '_or.id_account = _ac.id_account', array('_ac.name_account'))
            ;

        if ($cashier_id_order != '') {
            $sql->where('_or.cashier_id_order = ?', $cashier_id_order);
        }
        
        $sql->where('_or.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        $sql->where('_or.cashier_order = ?', 1);
//        
        $sql->where('_or.cashier_id_order IS NOT NULL');
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectTable($_primary = null) {
        $sql = $this->getAdapter()->select()->from(array('_or' => 'on_order'), array('_or.id_order', '_or.reference_order', '_or.description_order', '_or.situation_order', '_or.date_due_order', '_or.price_order', '(select sum(price_order) from on_order where cashier_id_order = _or.id_order) as sum_order', '(_or.price_order - (select sum(price_order) from on_order where cashier_id_order = _or.id_order)) as troco', '_or.cashier_order_check'))
                ->joinLeft(array('_co' => 'on_contact'), '_or.id_contact = _co.id_contact', array('_co.nickname_contact'))
                ->joinLeft(array('_ac' => 'on_account'), '_or.id_account = _ac.id_account', array('_ac.id_account', '_ac.name_account as name_account'))
            ;


        $sql->where('_or.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        $sql->where('_or.cashier_order = ?', 1);
        $sql->where('_or.cashier_id_order IS NULL');
        
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
    
    public function updateTableCheck($cashier_id_order = null, $data = null) {    
        try {
            $where = $this->getAdapter()->quoteInto('cashier_id_order = ?', $cashier_id_order);        
            $this->update($data, $where);
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
