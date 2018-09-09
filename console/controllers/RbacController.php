<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $this->createUserPermissions();
        $this->createTransactionPermissions();
        $this->createInvoicePermissions();

        $viewUsers = $auth->getItem('viewUsers');
        $viewInvoices = $auth->getItem('viewInvoices');
        $viewOwnInvoice = $auth->getItem('viewOwnInvoice');
        $importTransactions = $auth->getItem('importTransactions');
        $viewTransactions = $auth->getItem('viewTransactions');
        $viewOwnTransaction = $auth->getItem('viewOwnTransaction');

        $kontragent = $auth->createRole('kontragent');
        $auth->add($kontragent);
        $auth->addChild($kontragent, $viewOwnInvoice);
        $auth->addChild($kontragent, $viewOwnTransaction);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $importTransactions);
        $auth->addChild($admin, $viewTransactions);
        $auth->addChild($admin, $viewInvoices);
        $auth->addChild($admin, $viewUsers);
    }

    protected function createUserPermissions()
    {
        $auth = Yii::$app->authManager;

        $viewUsers = $auth->createPermission('viewUsers');
        $viewUsers->description = 'View users';
        $auth->add($viewUsers);
    }

    protected function createInvoicePermissions()
    {
        $auth = Yii::$app->authManager;

        $viewInvoices = $auth->createPermission('viewInvoices');
        $viewInvoices->description = 'View invoices';
        $auth->add($viewInvoices);

        $rule = new \common\rbac\InvoiceOwnerRule();
        $auth->add($rule);

        $viewOwnInvoice = $auth->createPermission('viewOwnInvoice');
        $viewOwnInvoice->description = 'View own invoice';
        $viewOwnInvoice->ruleName = $rule->name;
        $auth->add($viewOwnInvoice);

        $auth->addChild($viewOwnInvoice, $viewInvoices);
    }

    protected function createTransactionPermissions()
    {
        $auth = Yii::$app->authManager;

        $importTransactions = $auth->createPermission('importTransactions');
        $importTransactions->description = 'Import transactions';
        $auth->add($importTransactions);

        $viewTransactions = $auth->createPermission('viewTransactions');
        $viewTransactions->description = 'View transactions';
        $auth->add($viewTransactions);

        $auth->addChild($importTransactions, $viewTransactions);

        $rule = new \common\rbac\TransactionOwnerRule();
        $auth->add($rule);

        $viewOwnTransaction = $auth->createPermission('viewOwnTransaction');
        $viewOwnTransaction->description = 'View own transaction';
        $viewOwnTransaction->ruleName = $rule->name;
        $auth->add($viewOwnTransaction);

        $auth->addChild($viewOwnTransaction, $viewTransactions);
    }
}
