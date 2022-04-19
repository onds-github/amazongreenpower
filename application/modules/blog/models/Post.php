<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Post extends Zend_Db_Table {

    protected $_name = 'on_post';
    protected $_primary = 'id_post';
    protected $_rowClass = 'Post';
    
    public function selectTablePublic($slug_post = null) {
        $sql = $this->getAdapter()->select()->from($this->_name);

        if ($slug_post != '') {
            $sql->where('slug_post = ?', $slug_post);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectTableLast($_primary = null) {
        $sql = $this->getAdapter()->select()->from($this->_name);

        $sql->order('date_post', 'desc');
        
        $sql->limit(3);
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function selectTable($_primary = null) {
        $sql = $this->getAdapter()->select()->from($this->_name);

        if ($_primary != '') {
            $sql->where($this->_primary . ' = ?', $_primary);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function insertTable($data = null) {
        try {
            $returning = $this->insert($data);
            return array('status' => 'success', 'message' => 'O registro foi salvo!', 'returning' => $returning);
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
            $where = $this->getAdapter()->quoteInto($this->_primary . ' in(?)', $_primary);        
            $this->delete($where);
            return array(status => 'success', message => 'O registro foi excluído!');
        } catch (Exception $ex) {
            return array(status => 'warning', message => 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato com o suporte técnico!', error => $ex->getMessage());
        }   
    }
    
}
