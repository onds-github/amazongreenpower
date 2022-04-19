<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SendMail extends Zend_Db_Table {

    public function send($subject = null, $key_from = null, $key_to = null, $email = null, $name = null) {        
        $transport = new Zend_Mail_Transport_Smtp('mail.weone.com.br', $this->config());
        Zend_Mail::setDefaultTransport($transport);
        $send = new Zend_Mail('UTF-8');
        $message = str_replace($key_from, $key_to, $this->model());
        $send->setSubject($subject);
        $send->setBodyHtml($message);
        $send->setFrom('noreplay@weone.com.br', 'Mensagem automática');
        $send->addTo($email, $name);
        
        return $send->send($transport);
    }
    
    private function config() {
        return array(
            'auth'     => 'login',
            'username' => 'noreplay@weone.com.br',
            'password' => 'wo@2905.aZ'
        );
    }
    
    private function model() {
        return '<table width="100%" cellpadding="0" cellspacing="0" style="padding:0; margin:0"> <tbody> <tr> <td style="font-size:0"><span></span></td><td valign="top" align="left" style="width:640px; max-width:640px"> <table width="100%" bgcolor="#FFFFFF" cellpadding="0" cellspacing="0" style="padding:0; margin:0; border:0"> <tbody> <tr> <td align="left" id="x_main-pad" style="padding:0px 63px 0 0px"> <p style="font-size:16px; line-height:20px; color:#333333; padding:0; margin:0 0 20px 0; text-align:left; font-family:Helvetica,Arial,sans-serif"> O usuário com o e-mail <span style="color: #1875f0; font-family: Helvetica, Arial, sans-serif, serif, EmojiFont;">{EMAIL_REMETENTE}</span> enviou um convite para que você se junte a ele. </p><p style="font-family:Helvetica,Arial,sans-serif; font-size:16px; line-height:20px; color:#666666; font-style:italic; margin:0; padding:0; margin:0 0 20px 0; text-align:left"> "{MESSAGE}" </p><p style="font-family:Helvetica,Arial,sans-serif; font-size:16px; line-height:20px; color:#333333; margin:0; padding:0; margin:0 0 13px 0; text-align:left"> Junte-se ao <span style="color: #1875f0; font-family: Helvetica, Arial, sans-serif, serif, EmojiFont;">{EMAIL_REMETENTE}</span> clicando abaixo: </p><table cellpadding="0" cellspacing="0" style="padding:0; margin:0; border:0; width:163px"> <tbody> <tr> <td id="x_bottom-button-bg" valign="top" align="center" style="border-radius:3px; padding:6px 10px 6px 10px; background-color:#1875f0"> <a href="{LINK_BUTTON}" target="_blank" style="font-family:Helvetica,Arial,sans-serif; font-size:12px; color:#FFFFFF; background-color:#1875f0; -moz-border-radius:3px; -webkit-border-radius:3px; border-radius:3px; text-align:center; text-decoration:none; display:block; margin:0">{NAME_BUTTON}</a> </td></tr></tbody> </table> <p style="font-family:Helvetica,Arial,sans-serif; font-size:16px; line-height:20px; color:#777777; padding:0; margin:13px 0 20px 0; text-align:left"> Não funcionou? Copie o link abaixo no seu navegador: </p><p style="font-family:Helvetica,Arial,sans-serif; font-size:16px; line-height:20px; color:#333333; padding:0; margin:0 0 13px 0; text-align:left"> <a href="{LINK_BUTTON}" target="_blank" style="font-size:16px; line-height:20px; color:#1875f0; text-decoration:none; overflow:hidden; text-overflow:ellipsis; word-wrap:break-word; word-break:break-all">{LINK_BUTTON}</a> </p><p style="font-family:Helvetica,Arial,sans-serif; font-size:16px; line-height:20px; color:#333333; padding:0; margin:44px 0 0 0; text-align:left"> Atenciosamente,<br/> - Equipe Weone. </p></td></tr></tbody> </table> </td><td style="font-size:0"><span></span></td></tr></tbody></table>';
    }
    
}
