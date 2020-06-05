<?php
use mr\helper\HCli;
use mr\base\Container;

//bootstrap
require __DIR__.'/Autoload.php';

spl_autoload_register('Autoload::autoload');

$params = HCli::params();

HCli::info('run with params : {params}', [
    'params' => implode(' ', $params)
]);

/**
 * @var \mr\base\Application
 */
$app = Container::insure([
    'class' => 'mr\base\Application',
    'params' => $params
]);

/**
 * 装入容器
 */
Container::set('app', $app);

$app->run();