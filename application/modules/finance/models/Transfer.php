<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Transfer extends Zend_Db_Table {

    protected $_schema = 'on_finance';
    protected $_name = 'on_order_out';
    protected $_primary = 'id_order_out';
    protected $_rowClass = 'Transfer';
    
    public function selectTable($_primary = null) {
        $sql = $this->getAdapter()->select()->from(array('_oi' => 'on_order'), array('_oi.id_order', '_oi.description_order', '_oi.date_due_order', '_oi.price_order', '_oi.transfer_id_order'))
                ->joinLeft(array('_out' => 'on_order'), '_out.id_order = _oi.transfer_id_order', array('_out.id_account'))
                ->joinLeft(array('_cc_in' => 'on_account'), '_oi.id_account = _cc_in.id_account', array('_cc_in.name_account as name_account_in'))
                ->joinLeft(array('_cc_out' => 'on_account'), '_out.id_account = _cc_out.id_account', array('_cc_out.name_account as name_account_out'))
            ;


        $sql->where('_oi.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        $sql->where('_oi.transfer_order = 1');
        
        $sql->where('_oi.id_type_order = 1');
        
        if ($_primary != '') {
            $sql->where('_oi.id_order = ?', $_primary);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function viewTransfer($id_company = null) {
        $this->getAdapter()->query('set lc_numeric to \'pt_BR\'');
        $this->getAdapter()->query('set lc_monetary to \'pt_BR\'');
        
        $sql = $this->getAdapter()->select()->from($this->_schema . '.on_view_transfer');

        $sql->where('id_company = ?', $id_company);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function deleteTable($id_order_in = null) {        
//        $this->getAdapter()->query('SET on_session.on_cookie_key = "' . $_COOKIE['on_cookie_key'] . '"');  
        $where = $this->getAdapter()->quoteInto('id_order_in = ?', $id_order_in);        
        return $this->delete($where);         
    }
    
}
