<?php

class Settings_UsersController extends Zend_Controller_Action {

    public function init() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->redirect('/account/access?redirect=' . 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('layout_webapp');
        $this->view->id_module = 999;
        $this->view->title_page = 'Controle de Usuários';
        $this->view->description_page = 'Gerenciamento de contas de acesso';

        $this->view->headScript()
                ->appendFile('/public/modules/settings/script.users.js');
    }

    public function ajaxAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('User');
        $model = new User();

        $result = $model->selectTable();

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('User');
        $model = new User();

        $data = array();
        parse_str($this->_request->getParam('data'), $data);

        $pwd = substr(md5(uniqid(rand(), true)), 0, 6);

        $data['password_user'] = md5($pwd);

        $data['id_company_session'] = Zend_Auth::getInstance()->getIdentity()->id_company_session;

        $result = $model->insertTable($data);
        
        switch ($result['status']) {
            case 'success':
                $data['password_user'] = $pwd;
                $this->_mail($data);
                break;
        }

        echo Zend_Json::encode($result);
    }

    public function updateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('User');
        $model = new User();

        $data = array();
        parse_str($this->_request->getParam("data"), $data);

        $result = $model->updateTable($this->_request->getParam("q"), $data);

        echo Zend_Json::encode($result);
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('User');
        $model = new User();

        $data = json_decode($this->_request->getParam("data"), true);

        $result = $model->deleteTable($data);

        echo Zend_Json::encode($result);
    }

    public function ajaxContractItemAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('ContractItem');
        $model = new ContractItem();

        Zend_Loader::loadClass('Permission');
        $model_permission = new Permission();

        $array = $model->viewContractItem(Zend_Auth::getInstance()->getIdentity()->id_company_session);

        $id_user = $this->_request->getParam('id_user');

        foreach ($array as $value) {
            $result[] = array(
                $value['id_contract_item'],
                $value['name_module'],
                $value['empty'],
                $value['empty'],
                $value['empty'],
                $model_permission->viewPermission($id_user, $value['id_contract_item'])[0]['id_permission']
            );
        }

        echo Zend_Json::encode(array(data => ($result == null ? [] : $result)));
    }

    public function deletePermissionAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Permission');
        $model = new Permission();

        $result = $model->deleteTable($this->_request->getParam('id_permission'));

        echo Zend_Json::encode($result);
    }

    public function insertPermissionAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        Zend_Loader::loadClass('Permission');
        $model = new Permission();

        $data = array(
            id_contract_item => $this->_request->getParam('id_contract_item'),
            id_user => $this->_request->getParam('id_user')
        );

        $result = $model->insertTable($data);

        echo Zend_Json::encode($result);
    }

    public function _mail($data = null) {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        $_fields = array(
            from => array(
                email => 'noreply@onds.com.br'
            ),
            to => array(
                array(
                    email => $data['email_user'],
                    name => $data['name_user']
                )
            ),
            subject => 'Sua conta foi criada!',
            template_id => 'yzkq340yo0ld7961',
            variables => array(
                array(
                    email => $data['email_user'],
                    substitutions => array(
                        array(
                            'var' => 'name_user',
                            'value' => $data['name_user']
                        ),
                        array(
                            'var' => 'email_user',
                            'value' => $data['email_user']
                        ),
                        array(
                            'var' => 'password_user',
                            'value' => $data['password_user']
                        )
                    )
                )
            )
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.mailersend.com/v1/email');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($_fields));

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'X-Requested-With: XMLHttpRequest';
        $headers[] = 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMzc0ZTgxMTBlMmQxYmIwMTNkYTMxMmE4ODQ5OWViMTliYzM5YjI0ODQxZTM2MzVhN2JhMDE2MDFiY2RmMzBjZmFiOWY0MDYwODY4ZGM1NDciLCJpYXQiOjE2MTYwMzk5MzUsIm5iZiI6MTYxNjAzOTkzNSwiZXhwIjo0NzcxNzEzNTM1LCJzdWIiOiIxODY2Iiwic2NvcGVzIjpbImVtYWlsX2Z1bGwiLCJkb21haW5zX2Z1bGwiLCJhY3Rpdml0eV9mdWxsIiwiYW5hbHl0aWNzX2Z1bGwiLCJ0b2tlbnNfZnVsbCIsIndlYmhvb2tzX2Z1bGwiLCJ0ZW1wbGF0ZXNfZnVsbCJdfQ.Dl7SRVCp68J2_-XPmILWrhchfKRFtr5_YIpmFvcYZLgjy8oVaDHWTV5ArjWUslVNidL4-c_2cXqOEEveTAfb5Pjc6fcm0C13AHn56CT_RSsdmqzTicFiHprFQESwaVkJ2oCylW2su9mO8Lw7-ccJBv63bXkKCgvRRSOxkgl0X1VOLEQszXuRl5ZPaQZRSFnEyIXP589bw3_YVvqKigtJBPexnZLbQel8IYCb_yC2v-k-yCxrf9TySNsediFtpR4iK1UPik8_r9C_UiqlWEPozJ1wtsH39CexSkaIYPDQ7FTHT0dnOB7GE2sEgQa9azpPadA1TpykmQUceb42aSqFnOZJUveVHBXFZNEYM2zK83jnagxln5JuqtBT5E28grWDs5YLDJm7APZgmqL5KGc1P-l7YMc50aMsLbn4LWUGWg9hCtGmRBsZv5J24LS-b7acb0G1aA86OZh2idfk0FUe7k48A65rH6iIEem1HmVOzh13BqDrCk-QIC5gYYHFw4D6CES6KpNDGIX5-40YHfQUp6scHKVtg3mBc41JcsdwrNBP8K0a1XlgDb3UelhM6AA5GNy66f8NQkLcYMHOOEPLvLC3dhoziQjiLA-kyVyWy1RK5B8SzzmmfNcsCWOHCdnLD6tdU0I-F2ya-C64q9y5g1CDW6ygvyv54OUEcF8XNtA';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            
        } else {
            
        }

        curl_close($ch);
    }

}
