<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Conciliation extends Zend_Db_Table {

    public function selectTableAccountDropdown($query= null) {
        $sql = $this->getAdapter()->select()->from('on_account');

        if ($query != '') {
            $sql->where('name_account ilike ?', '%' . $query . '%');
        }
        
        $sql->where('id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectOrderInConciliation() {
        $sql = $this->getAdapter()->select()->from(array('_in' => 'on_order_in_conciliation'), array('_in.id_order_in_conciliation', '_in.description_order_in_conciliation', '_in.date_order_in_conciliation', '_in.price_order_in_conciliation', '_in.id_order_in'))
        ;

        $sql->where('_in.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        $sql->where('_in.date_order_in_conciliation '
                . 'BETWEEN \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_min . '\' '
                . 'AND \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_max . '\'');
        
        if (Zend_Auth::getInstance()->getIdentity()->filter_account != '') {
            $sql->where('_in.id_account = ?', Zend_Auth::getInstance()->getIdentity()->filter_account);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectOrderOutConciliation() {
        $sql = $this->getAdapter()->select()->from(array('_out' => 'on_order_out_conciliation'), array('_out.id_order_out_conciliation', '_out.description_order_out_conciliation', '_out.date_order_out_conciliation', '_out.price_order_out_conciliation', '_out.id_order_out'))
        ;

        $sql->where('_out.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        $sql->where('_out.date_order_out_conciliation '
                . 'BETWEEN \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_min . '\' '
                . 'AND \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_max . '\'');
        
        if (Zend_Auth::getInstance()->getIdentity()->filter_id_account != '') {
            $sql->where('_out.id_account = ?', Zend_Auth::getInstance()->getIdentity()->filter_id_account);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectOrderInPosConciliation() {
        $sql = $this->getAdapter()->select()->from(array('_in' => 'on_order_in_conciliation'), array('_in.id_order_in_conciliation', '_in.description_order_in_conciliation', '_in.date_order_in_conciliation', '_in.price_order_in_conciliation'))
        ;

        $sql->where('_in.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        $sql->where('_in.date_order_in_conciliation '
                . 'BETWEEN \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_min . '\' '
                . 'AND \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_max . '\'');
        
        if (Zend_Auth::getInstance()->getIdentity()->filter_account != '') {
            $sql->where('_in.id_account = ?', Zend_Auth::getInstance()->getIdentity()->filter_account);
        }
        
        $sql->where('_in.id_order_in IS NOT NULL');
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectOrderOutPosConciliation() {
        $sql = $this->getAdapter()->select()->from(array('_out' => 'on_order_out_conciliation'), array('_out.id_order_out_conciliation', '_out.description_order_out_conciliation', '_out.date_order_out_conciliation', '_out.price_order_out_conciliation'))
        ;

        $sql->where('_out.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        $sql->where('_out.date_order_out_conciliation '
                . 'BETWEEN \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_min . '\' '
                . 'AND \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_max . '\'');
        
        if (Zend_Auth::getInstance()->getIdentity()->filter_account != '') {
            $sql->where('_out.id_account = ?', Zend_Auth::getInstance()->getIdentity()->filter_account);
        }
        
        $sql->where('_out.id_order_out IS NOT NULL');
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectOrderIn() {
        $sql = $this->getAdapter()->select()->from(array('_in' => 'on_order_in'), array('_in.id_order_in', '_in.description_order_in', '_in.date_payment_order_in', '(_in.price_order_in - _in.price_discount_order_in + _in.price_fees_order_in + _in.price_addition_order_in) AS total_order_in'))
        ->joinLeft(array('_oic' => 'on_order_in_conciliation'), '_oic.id_order_in = _in.id_order_in', array('_oic.fitid_order_in_conciliation'))
;

        $sql->where('_oic.fitid_order_in_conciliation IS NULL');

        $sql->where('_in.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        $sql->where('_in.date_payment_order_in '
                . 'BETWEEN \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_min . '\' '
                . 'AND \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_max . '\'');
        
        if (Zend_Auth::getInstance()->getIdentity()->filter_id_account != '') {
            $sql->where('_in.id_account = ?', Zend_Auth::getInstance()->getIdentity()->filter_id_account);
        }
        
        $sql->where("_in.situation_order_in = 1");
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectOrderOut() {
        $sql = $this->getAdapter()->select()->from(array('_out' => 'on_order_out'), array('_out.id_order_out', '_out.description_order_out', '_out.date_payment_order_out', '(_out.price_order_out - _out.price_discount_order_out + _out.price_fees_order_out + _out.price_addition_order_out) AS total_order_out'))
        ->joinLeft(array('_ooc' => 'on_order_out_conciliation'), '_ooc.id_order_out = _out.id_order_out', array('_ooc.fitid_order_out_conciliation'))
;

        $sql->where('_ooc.fitid_order_out_conciliation IS NULL');

        $sql->where('_out.id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        $sql->where('_out.date_payment_order_out '
                . 'BETWEEN \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_min . '\' '
                . 'AND \'' . Zend_Auth::getInstance()->getIdentity()->filter_period_max . '\'');
        
        if (Zend_Auth::getInstance()->getIdentity()->filter_id_account != '') {
            $sql->where('_out.id_account = ?', Zend_Auth::getInstance()->getIdentity()->filter_id_account);
        }
        
        $sql->where("_out.situation_order_out = 1");
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
}
