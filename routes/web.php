<?php

$router->get('/', 'HomeController@index');

//CRUD
$router->post('/pessoas/create', ['as' => 'create', 'uses' => 'HomeController@create']);
$router->post('/pessoas/read', ['as' => 'read', 'uses' => 'HomeController@read']);
$router->post('/pessoas/update', ['as' => 'update', 'uses' => 'HomeController@update']);
$router->post('/pessoas/delete', ['as' => 'delete', 'uses' => 'HomeController@delete']);
