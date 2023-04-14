<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->exclude('var')
    ->exclude('config')
    ->exclude('migrations')
    ->exclude('public')
    ->in(__DIR__)
;

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@Symfony' => true,
    'strict_param' => true,
    'array_syntax' => ['syntax' => 'short'],
])
    ->setFinder($finder)
    ;