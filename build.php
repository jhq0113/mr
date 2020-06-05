#!/usr/bin/env php
<?php
$phar = new Phar('tool.phar', 0, 'tool.phar');
$phar->buildFromDirectory(__DIR__ . '/src');
$phar->setDefaultStub('index.php', null);