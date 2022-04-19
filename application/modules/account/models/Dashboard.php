<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Dashboard extends Zend_Db_Table {

    public function selectTotalDashboard() {
        $sql = $this->getAdapter()->select()->from(
                array('_co' => 'on_company'), 
                array(
                    'total_accounts' => 
                    '('
                    . '(SELECT COALESCE(SUM(_in.price_down), 0) FROM on_order _in '
                    . 'WHERE _in.id_company = _co.id_company AND _in.situation_order = 1 AND _in.id_type_order = 1) - '
                    . '(SELECT COALESCE(SUM(_out.price_down), 0) FROM on_order _out '
                    . 'WHERE _out.id_company = _co.id_company AND _out.situation_order = 1 AND _out.id_type_order = 2 AND _out.cashier_order_check = 1)'
                    . ')'
                    )
                );
        
        $sql->where('_co.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectAccountDashboard() {
        $sql = $this->getAdapter()->select()->from(
                array('_as' => 'on_account'), 
                array(
                    '_as.id_account',
                    '_as.name_account',
                    '('
                    . '(SELECT COALESCE(SUM(_in.price_down), 0) FROM on_order _in '
                    . 'WHERE '
                    . '_in.id_account = _as.id_account '
                    . 'AND _in.situation_order = 1 '
                    . 'AND _in.id_type_order = 1) - '
                    . '(SELECT COALESCE(SUM(_out.price_down), 0) FROM on_order _out '
                    . 'WHERE '
                    . '_out.id_account = _as.id_account '
                    . 'AND _out.situation_order = 1 '
                    . 'AND _out.id_type_order = 2 '
                    . 'AND _out.cashier_order_check = 1)'
                    . ') as total_order'
                    )
                );

        $sql->where('_as.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectCostCenterDashboard() {
        $sql = $this->getAdapter()->select()->from(
            array('_cc' => 'on_cost_center'), 
            array(
                'id_cost_center',
                'icon_cost_center',
                'color_cost_center',
                'name_cost_center',
                '(SELECT COALESCE(SUM(_out.price_down), 0) FROM on_order _out '
                . 'WHERE _out.id_company = _cc.id_company '
                . 'AND _out.id_cost_center = _cc.id_cost_center '
                . 'AND _out.situation_order = 1 '
                . 'AND _out.id_type_order = 2 AND _out.cashier_order_check = 1'
                . ') as total_order'
                )
            );

        $sql->where('_cc.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
            
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectInOutDashboard($year = null, $month = null) {
        $sql = $this->getAdapter()->select()->from(
                array('_co' => 'on_company'), 
                array(
                    '(SELECT COALESCE(SUM(_in.price_down), 0) FROM on_order _in '
                    . 'WHERE _co.id_company = _in.id_company '
                    . 'AND YEAR(_in.date_due_order) = ' . $year . ' AND MONTH(_in.date_due_order) = ' . $month . ' '
                    . 'AND _in.situation_order = 1 AND _in.id_type_order = 1'
                    . ') as total_order_in',
                    '(SELECT COALESCE(SUM(_out.price_down), 0) FROM on_order _out '
                    . 'WHERE _co.id_company = _out.id_company '
                    . 'AND YEAR(_out.date_due_order) = ' . $year . ' AND MONTH(_out.date_due_order) = ' . $month . ' '
                    . 'AND _out.situation_order = 1 AND _out.id_type_order = 2 AND _out.cashier_order_check = 1 '
                    . ') as total_order_out'
                    )
                );

        $sql->where('_co.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectOrderDashboard($id_type_order = null) {
        $sql = $this->getAdapter()->select()->from(array('_in' => 'on_order'), array('_in.id_order', '_in.date_due_order', '_in.price_order'))
                ->joinLeft(array('_co' => 'on_contact'), '_in.id_contact = _co.id_contact', array('_co.nickname_contact'));
        
        $sql->where('_in.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        $sql->where('_in.situation_order = 0');
        
        $sql->where('_in.date_due_order BETWEEN "' . date("Y-m-d", strtotime("monday this week")) . '" AND "' . date("Y-m-d", strtotime("sunday this week")) . '"');
        
        $sql->order('_in.date_due_order asc');
        
        $sql->where('_in.id_type_order = ?', $id_type_order);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
}
