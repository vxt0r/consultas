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

        $routes['email'] = array(
            'route' => '/email',
            'controller' => 'indexController',
            'action' =>  'email'
        );

        $this->setRoutes($routes);
    }



}
