<?php
return [
    'viewUsers' => [
        'type' => 2,
        'description' => 'View users',
    ],
    'importTransactions' => [
        'type' => 2,
        'description' => 'Import transactions',
        'children' => [
            'viewTransactions',
        ],
    ],
    'viewTransactions' => [
        'type' => 2,
        'description' => 'View transactions',
    ],
    'viewOwnTransaction' => [
        'type' => 2,
        'description' => 'View own transaction',
        'ruleName' => 'isTransactionOwner',
        'children' => [
            'viewTransactions',
        ],
    ],
    'viewInvoices' => [
        'type' => 2,
        'description' => 'View invoices',
    ],
    'viewOwnInvoice' => [
        'type' => 2,
        'description' => 'View own invoice',
        'ruleName' => 'isInvoiceOwner',
        'children' => [
            'viewInvoices',
        ],
    ],
    'kontragent' => [
        'type' => 1,
        'children' => [
            'viewOwnInvoice',
            'viewOwnTransaction',
        ],
    ],
    'admin' => [
        'type' => 1,
        'children' => [
            'importTransactions',
            'viewTransactions',
            'viewInvoices',
            'viewUsers',
        ],
    ],
];
