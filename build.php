<?php
$phar = new Phar('tool.phar', 0, 'tool.phar');
$phar->buildFromDirectory(__DIR__ . '/src');

$stub = <<<PHP
#!/usr/bin/env php
<?php
Phar::mapPhar();
include 'phar://tool.phar/index.php';
__HALT_COMPILER();
PHP;
$phar->setStub($stub);