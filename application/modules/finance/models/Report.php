<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Report extends Zend_Db_Table {

    protected $_name = 'on_order_in';
    protected $_primary = 'id_order_in';
    protected $_rowClass = 'OrderIn';
    
    public function selectTableAccountDropdown($query= null) {
        $sql = $this->getAdapter()->select()->from('on_account');

        if ($query != '') {
            $sql->where('name_account ilike ?', '%' . $query . '%');
        }
        
        $sql->where('id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectPreviousBalance($date_due_order = null) {
        $sql = $this->getAdapter()->select()->from(
                array('_co' => 'on_company'), 
                array(
                    'total_accounts' => 
                    '('
                    . 'COALESCE((SELECT sum(_in.price_down) FROM on_order _in '
                    . 'WHERE _in.id_company = _co.id_company '
                    . (Zend_Auth::getInstance()->getIdentity()->filter_report_extract_id_account ? 'AND _in.id_account = ' . Zend_Auth::getInstance()->getIdentity()->filter_report_extract_id_account . ' ' : '')
                    . 'AND _in.situation_order = 1 '
                    . 'AND _in.id_type_order = 1 '
                    . 'AND _in.date_payment_order <= \'' . $date_due_order . '\'), 0) - '
                    . 'COALESCE((SELECT sum(_out.price_down) FROM on_order _out '
                    . 'WHERE _out.id_company = _co.id_company '
                    . (Zend_Auth::getInstance()->getIdentity()->filter_report_extract_id_account ? 'AND _out.id_account = ' . Zend_Auth::getInstance()->getIdentity()->filter_report_extract_id_account . ' ' : '')
                    . 'AND _out.situation_order = 1 '
                    . 'AND _out.id_type_order = 2 '
                    . 'AND _out.cashier_order_check = 1 '
                    . 'AND _out.date_payment_order <= \'' . $date_due_order . '\'), 0)'
                    . ')'
                    )
                );
        
        
        $sql->where('_co.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectReportExtract() {
        $sql = $this->getAdapter()->select()->from(array('_in' => 'on_order'), array('_in.id_order', '_in.description_order', '_in.date_payment_order', '_in.price_order', '_in.transfer_order', '_in.price_down', '_in.id_type_order'))
                ->joinLeft(array('_co' => 'on_contact'), '_in.id_contact = _co.id_contact', array('_co.nickname_contact', '_co.document_contact'))
                ->joinLeft(array('_ac' => 'on_account'), '_in.id_account = _ac.id_account', array('_ac.name_account'))
            ;

        $sql->where('_in.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        $sql->where('_in.situation_order = 1');
        
        $sql->where('_in.cashier_order_check = 1');
        
        $sql->where('_in.date_payment_order '
                . 'BETWEEN \'' . Zend_Auth::getInstance()->getIdentity()->filter_report_extract_period_min . '\' '
                . 'AND \'' . Zend_Auth::getInstance()->getIdentity()->filter_report_extract_period_max . '\'');
        
        if (Zend_Auth::getInstance()->getIdentity()->filter_report_extract_id_account != '') {
            $sql->where('_in.id_account = ?', Zend_Auth::getInstance()->getIdentity()->filter_report_extract_id_account);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
}
