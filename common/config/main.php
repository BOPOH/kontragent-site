<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<alias:\w+>' => 'site/<alias>',
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'itemFile' => implode(DIRECTORY_SEPARATOR, [__DIR__, 'rbac', 'items.php']),
            'assignmentFile' => implode(DIRECTORY_SEPARATOR, [__DIR__, 'rbac', 'assignments.php']),
            'ruleFile' => implode(DIRECTORY_SEPARATOR, [__DIR__, 'rbac', 'rules.php']),
        ],
    ],
];
