
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Tutorial extends Zend_Db_Table {

    protected $_schema = 'help';
    protected $_name = 'wo_tutorial';
    protected $_primary = 'id_tutorial_check';
    protected $_rowClass = 'Tutorial';
    
    public function selectView($id_user = null, $request_uri = null) {
        $sql = $this->getAdapter()->select()->from('help.view_tutorial', array('intro', 'element', 'position'));
        
        if ($request_uri != null) {
            $sql->where('request_uri = ?', $request_uri);
        }  
        
        if ($id_user != null) {
            $sql->where('id_tutorial NOT IN (SELECT id_tutorial FROM help.wo_tutorial_check WHERE id_user = ?)', $id_user);
        }
        
        $sql->group(array('request_uri', 'order_tutorial', 'intro', 'element', 'position'));
        
        return $this->getAdapter()->fetchAll($sql);   
    } 
    
    
    public function insertTable($id_user = null, $request_uri = null) {
                return $this->getAdapter()->fetchAll('INSERT INTO help.wo_tutorial_check (id_tutorial, id_user) (SELECT a.id_tutorial, ' . $id_user . ' FROM help.wo_tutorial a left join help.wo_tutorial_group b on a.id_tutorial_group = b.id_tutorial_group WHERE a.id_tutorial NOT IN (SELECT b.id_tutorial FROM help.wo_tutorial_check b WHERE b.id_user = ' . $id_user . ') AND b.request_uri = \'' . $request_uri . '\')');
    }
    
}