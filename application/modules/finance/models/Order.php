<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Order extends Zend_Db_Table {

    protected $_name = 'on_order';
    protected $_primary = 'id_order';
    protected $_rowClass = 'Order';
    
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
    
    public function selectTableTransferIdOrder($id_order = null) {
        $sql = $this->getAdapter()->select()->from('on_order');

        $sql->where('id_order = ?', $id_order);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    
    public function selectTableDuplicate($_primary = null) {
        $sql = $this->getAdapter()->select()->from(array('_or' => 'on_order'), array('_or.id_company', '_or.reference_order', '_or.description_order', '_or.id_type_order', '_or.date_issue_order', '_or.date_competence_order', '_or.date_due_order', '_or.id_contact', '_or.id_account', '_or.id_chart_accounts', '_or.id_cost_center'));
        
        $sql->where('_or.id_order = ?', $_primary);
            
        return $this->getAdapter()->fetchAll($sql);   
    }

    
    public function selectTable($_primary = null) {
        $sql = $this->getAdapter()->select()->from(array('_or' => 'on_order'), array('_or.id_order', '_or.reference_order', '_or.description_order', '_or.id_type_order', '_or.date_issue_order', '_or.date_competence_order', '_or.date_due_order', '_or.date_payment_order', '_or.situation_order', '_or.price_discount_order', '_or.price_addition_order', '_or.price_fees_order', '_or.price_order', '_or.price_down', '_or.id_contact', '_or.id_account', '_or.id_chart_accounts', '_or.id_cost_center', 'COALESCE(_or.price_down, _or.price_order) AS total_order', '(select count(_fi.id_file) from on_file _fi where _fi.id_table_parent = _or.id_order AND _fi.name_table_parent = \'on_order\') as files_order', '_or.id_order_conciliation'))
                ->joinLeft(array('_co' => 'on_contact'), '_or.id_contact = _co.id_contact', array('_co.nickname_contact', '_co.document_contact'))
                ->joinLeft(array('_cc' => 'on_cost_center'), '_or.id_cost_center = _cc.id_cost_center', array('_cc.name_cost_center', '_cc.icon_cost_center', '_cc.color_cost_center'))
                ->joinLeft(array('_ca' => 'on_chart_accounts'), '_or.id_chart_accounts = _ca.id_chart_accounts', array('_ca.name_chart_accounts'))
                ->joinLeft(array('_ac' => 'on_account'), '_or.id_account = _ac.id_account', array('_ac.name_account'))
            ;

        if ($_primary != '') {
            $sql->where('_or.id_order = ?', $_primary);
        }
        
        $sql->where('_or.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        $sql->where('_or.transfer_order = ?', 0);
        
        $sql->where('_or.cashier_order = ?', 0);
        
        #filtro por tipo de lançamento
        switch (Zend_Auth::getInstance()->getIdentity()->filter_id_type_order) {
            #contas a receber
            case 1:
                $sql->where('_or.id_type_order = 1');
                $sql->where('_or.situation_order = 0');
//                $sql->where('_or.date_due_order >= ?', date('Y-m-d'));
                break;
            #Contas a receber atrasadas
//            case 2:
//                $sql->where('_or.id_type_order = 1');
//                $sql->where('_or.situation_order = 0');
//                $sql->where('_or.date_due_order < ?', date('Y-m-d'));
//                break;
            #receitas
            case 3:
                $sql->where('_or.id_type_order = 1');
                $sql->where('_or.situation_order = 1');
                break;
            #contas a pagar
            case 4:
                $sql->where('_or.id_type_order = 2');
                $sql->where('_or.situation_order = 0');
//                $sql->where('_or.date_due_order >= ?', date('Y-m-d'));
                break;
            #contas a pagar atrasadas
//            case 5:
//                $sql->where('_or.id_type_order = 2');
//                $sql->where('_or.situation_order = 0');
//                $sql->where('_or.date_due_order < ?', date('Y-m-d'));
//                break;
            #despesas
            case 6:
                $sql->where('_or.id_type_order = 2');
                $sql->where('_or.situation_order = 1');
                break;
        }
        
        switch (Zend_Auth::getInstance()->getIdentity()->filter_id_type_period) {
            case 1:
                Zend_Auth::getInstance()->getIdentity()->filter_period_min = date("Y-m-d");
                Zend_Auth::getInstance()->getIdentity()->filter_period_max = date("Y-m-d");
                break;
            case 2:
                Zend_Auth::getInstance()->getIdentity()->filter_period_min = date("Y-m-d", strtotime("monday this week"));
                Zend_Auth::getInstance()->getIdentity()->filter_period_max = date("Y-m-d", strtotime("sunday this week"));
                break;
            case 3:
                Zend_Auth::getInstance()->getIdentity()->filter_period_min = date("Y-m-d", strtotime("first day of this month"));
                Zend_Auth::getInstance()->getIdentity()->filter_period_max = date("Y-m-d", strtotime("last day of this month"));
                break;
            case 4:
                Zend_Auth::getInstance()->getIdentity()->filter_period_min = date("Y-m-d", strtotime("first day of january"));
                Zend_Auth::getInstance()->getIdentity()->filter_period_max = date("Y-m-d", strtotime("last day of December"));
                break;
        }
        
        switch (Zend_Auth::getInstance()->getIdentity()->filter_id_type_date) {
            case 1:
                $sql->where('_or.date_issue_order '
                        . 'BETWEEN \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_min . '\' '
                        . 'AND \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_max . '\'');
                break;
            case 2:
                $sql->where('_or.date_due_order '
                        . 'BETWEEN \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_min . '\' '
                        . 'AND \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_max . '\'');
                break;
            case 3:
                $sql->where('_or.date_payment_order '
                        . 'BETWEEN \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_min . '\' '
                        . 'AND \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_max . '\'');
                break;
        }
        
        if (Zend_Auth::getInstance()->getIdentity()->filter_id_contact != 0) {
            $sql->where('_or.id_contact = ?', Zend_Auth::getInstance()->getIdentity()->filter_id_contact);
        }
        
        if (Zend_Auth::getInstance()->getIdentity()->filter_id_account != 0) {
            $sql->where('_or.id_account = ?', Zend_Auth::getInstance()->getIdentity()->filter_id_account);
        }
        
        if (Zend_Auth::getInstance()->getIdentity()->filter_id_chart_accounts != 0) {
            $sql->where('_or.id_chart_accounts = ?', Zend_Auth::getInstance()->getIdentity()->filter_id_chart_accounts);
        }
        
        if (Zend_Auth::getInstance()->getIdentity()->filter_id_cost_center != 0) {
            $sql->where('_or.id_cost_center = ?', Zend_Auth::getInstance()->getIdentity()->filter_id_cost_center);
        }
        
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
