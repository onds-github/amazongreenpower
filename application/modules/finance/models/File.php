<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class File extends Zend_Db_Table {

    protected $_name = 'on_file';
    protected $_primary = 'id_file';
    protected $_rowClass = 'File';
    
    public function selectTable($_primary = null, $id_table_parent = null, $name_table_parent = null) {
        $sql = $this->getAdapter()->select()->from('on_file');

        $sql->where('id_company = ?', Zend_Auth::getInstance()->getIdentity()->id_company_session);
        
        if ($_primary != '') {
            $sql->where('id_file = ?', $_primary);
        }
        
        if ($id_table_parent != '') {
            $sql->where('id_table_parent = ?', $id_table_parent);
        }
        
        if ($name_table_parent != '') {
            $sql->where('name_table_parent = ?', $name_table_parent);
        }
        
        return $this->getAdapter()->fetchAll($sql);   
    }
    
    public function insertTable($data = null) {
        try {
            $this->insert($data);
            return array(status => 'success', message => 'O registro foi salvo!', link_file => $data['link_file']);
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
