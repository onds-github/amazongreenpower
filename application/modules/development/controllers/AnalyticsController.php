<?php

class development_AnalyticsController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function insertAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        try {
            Zend_Loader::loadClass('Analytics');
            $model = new Analytics();

            $localize = $this->_ip_api($_SERVER['REMOTE_ADDR']);
            $array = array(
                device_analytics => $_SERVER['HTTP_USER_AGENT'],
                url_analytics => $this->_request->getParam("url"),
                ip_analytics => $localize->query,
                country_analytics => $localize->countryCode,
                state_analytics => $localize->region,
                city_analytics => $localize->city,
                long_lat_analytics => $localize->lat . ',' . $localize->lon,
                cookie_key => $this->_request->getParam('cookie_key'),
                mobile_analytics => $localize->mobile == 'true' ? 't' : 'f'
            );

            $result = $model->insertTable($array);

            echo Zend_Json::encode(array(status => 'success', message => 'Monitoramento em andamento'));
        } catch (Exception $exc) {
            echo Zend_Json::encode(array(status => 'danger', message => 'O Monitoramento falhou: ' . $exc->getMessage()));
        }
    }

    private function _ip_api($remote_addr) {
        return json_decode(file_get_contents("http://ip-api.com/json/{$remote_addr}?fields=countryCode,region,city,lat,lon,mobile,query"));
    }

}
