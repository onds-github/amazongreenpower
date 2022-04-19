<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Zend_View_Helper_Menu extends Zend_View_Helper_Abstract {
    
    public function menu($menu = null, $order = null) {
        foreach ($this->session() as $active) {
            foreach ($this->item() as $value) {
                if ($value['menu'] == $active['menu']) {
                    $itens.= '<a class="' . ($value['menu'] == $menu ? 'active' : '') . ' item" href="' . $this->view->baseUrl($value['href']) . '"><i class="material-icons">' . $value['icon'] . '</i> ' . $value['label'] . '</a>';
                }
            }
        }
        return $itens;
    }
    
    protected function session() {
        Zend_Loader::loadClass("Menu");
        $model = new Menu();
        
        $array = $model->selectViewWebapp(Zend_Auth::getInstance()->getIdentity()->id_business);
        
        foreach ($array as $value) {
            $itens[] = array(
                menu => $value['id_webapp']
            );
        }
        
        return $itens;
    }
     
    protected function item() {
        $itens = array();
        
        array_push($itens, array(href => 'client/payment', label => 'PAGAMENTOS', icon => 'attach_money', menu => 1));
        array_push($itens, array(href => 'client/drive', label => 'DRIVE', icon => 'storage', menu => 2));
        array_push($itens, array(href => 'client/ticket', label => 'CHAMADOS', icon => 'confirmation_number', menu => 3));
        array_push($itens, array(href => 'client/task', label => 'TAREFAS', icon => 'assignment_turned_in', menu => 4));
        array_push($itens, array(href => 'client/process', label => 'PROCESSOS', icon => 'find_in_page', menu => 5));
        array_push($itens, array(href => 'client/contract', label => 'CONTRATOS', icon => 'description', menu => 7));
        return $itens;
    }
     
}
