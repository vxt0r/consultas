<?php

namespace app;

use MF\init\Bootstrap;

class Route extends Bootstrap{

    protected function initRoutes(){

        $routes['home'] = array(
            'route' => '/',
            'controller' => 'indexController',
            'action' =>  'index'
        );

        $routes['appointments'] = array(
            'route' => '/appointment',
            'controller' => 'indexController',
            'action' =>  'appointment'
        );

        $routes['email'] = array(
            'route' => '/email',
            'controller' => 'indexController',
            'action' =>  'email'
        );

        $this->setRoutes($routes);
    }



}
