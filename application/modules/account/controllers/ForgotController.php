<?php

class Account_ForgotController extends Zend_Controller_Action {

    public function init() {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector->gotoRoute(array('module' => 'account', 'controller' => 'index', 'action' => 'index'));
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_blank');
        $this->view->title = 'Esqueceu sua senha';
        $this->view->description = 'Informe o e-mail da sua conta para enviar a solicitação de redefinição de senha.';

        $this->view->headLink();

        $this->view->headScript()
                ->appendFile($this->view->baseUrl('public/modules/account/js/forgot.js'));
        
    }
    
    public function existsEmailAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass("User");
        $model = new User();

        $result = $model->viewUserExistsEmail($this->_request->getParam("field"));

        echo Zend_Json::encode($result ? true : false);
    }
    
    public function updateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('User');
        $model_user = new User();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);
        
        $senha = substr(md5(uniqid(rand(), true)), 0, 6);

        $array = array(
            password => MD5($senha)
        );
        
        $result_user = $model_user->viewUserExistsEmail($data['email']);
        
        $model_user->updateTableForgot($data['email'], $array);
        
        $template['title'] = 'Redefinir senha';
        $template['description'] ='Esqueceu sua senha? Não tem problema!<br class="em_hide" />'
                . 'Sua nova senha é <strong>' . $senha . '</strong><br class="em_hide" />'
                . 'Agora é só digitar essa senha para entrar no site.<br class="em_hide" />'
                . '<a href="https://beta-publiclick.com.br/account" class="btn-primary" itemprop="url" style="font-family: "Helvetica Neue",Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2em; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #348eda; margin: 0; border-color: #348eda; border-style: solid; border-width: 10px 20px;">Ir para o site</a>.<br class="em_hide" />'
                . 'Importante: depois de acessar o site, entre no <strong>menu segurança</strong> e escolha uma nova senha. Assim, fica mais fácil de lembrar!';

        $mail_array = array(
            setFromName => 'Publiclick',
            setReplyToName => 'Publiclick',
            setReplyToEmail => 'contato@beta-publiclick.com.br',
            addToName => $result_user[0]['name'],
            addToEmail => $result_user[0]['email'],
            setSubject => $template['title'],
            setHtml => $template
        );

        $result = $this->_mail($mail_array);

        $email = substr_replace($result_user[0]['email'], str_repeat('*', strpos($result_user[0]['email'], '@') - 2), 1, strpos($result_user[0]['email'], '@') - 2);
        
        switch ($result) {
            case true:
                echo Zend_Json::encode(array(status => 'success', message => 'As instruções de redefinição foram enviadas para: ' . $email . ', verifique seu e-mail.'));
                break;
            case false:
                echo Zend_Json::encode(array(status => 'danger', message => 'Ocorreu um erro inesperado, tente novamente ou informe nossa equipe de suporte.'));
                break;
        }

    }

}
