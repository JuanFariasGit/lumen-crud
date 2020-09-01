<?php

$router->get('/', 'HomeController@index');

//CRUD
$router->post('/pessoas/create', 'HomeController@create');
$router->post('/pessoas/read', 'HomeController@read');
$router->post('/pessoas/update', 'HomeController@update');
$router->post('/pessoas/delete', 'HomeController@delete');