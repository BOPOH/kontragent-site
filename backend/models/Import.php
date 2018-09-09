<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use common\models\User;
use common\models\Invoice;
use common\models\Transaction;

class Import extends Model
{
    public $file;

    protected $errorsCount = 0;
    protected $successCount = 0;

    public function rules(){
        return [
            [['file'], 'required'],
            [['file'], 'file', 'extensions' => 'csv, xls', 'maxSize' => 1024 * 1024 * 5],
        ];
    }
    
    public function attributeLabels() {
        return [
            'file' => 'Select File',
        ];
    }

    public function process()
    {
        $this->errorsCount = 0;
        $this->successCount = 0;

        $file = UploadedFile::getInstance($this, 'file');
        $filename = Yii::getAlias('@runtime/import.' . $file->extension);
        $upload = $file->saveAs($filename);
        if ($upload) {
            $reader = new \SpreadsheetReader($filename);
            foreach ($reader as $row) {
                if (!$reader->key()) {
                    continue;
                }
                try {
                    $transaction = Yii::$app->db->beginTransaction();
                    $this->importRow($row);
                    $transaction->commit();
                    $this->successCount++;
                } catch (\Exception $e) {
                    $transaction && $transaction->rollBack();
                    $this->errorsCount++;                    
                }
            }
        }
        unlink($filename);
    }

    public function getErrorsCount()
    {
        return $this->errorsCount;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    protected function importRow($row)
    {
        $keys = [
            'invoice-id',
            'transaction-type',
            'transaction-amount',
            'transaction-stamp',
            'kontragent-name',
            'kontragent-email',
        ];
        $row = array_combine($keys, $row);
        $row['transaction-type'] = ($row['transaction-type'] == 'deposit') ? Transaction::TYPE_DEPOSIT : Transaction::TYPE_WITHDRAWAL;

        // list($invoice_id, $type, $amount, $stamp, $kontragent_name, $kontragent_email) = $row;
        if ($this->isTransactionExists($row)) {
            throw new \Exception("Integrity is violeted" . var_export($row, true));
        }

        $user = $this->importUser($row);
        $invoice = $this->importInvoice($user, $row);
        $transaction = $this->importTransaction($user, $invoice, $row);
    }

    protected function isTransactionExists($row)
    {
        return Transaction::find()
            ->with('user')
            ->where(['amount' => $row['transaction-amount']])
            ->andWhere(['stamp' => $row['transaction-stamp']])
            ->andWhere(['type' => $row['transaction-type']])
            ->andWhere(['invoice_id' => $row['invoice-id']])
            ->andWhere(['user.email' => $row['kontragent-email']])
            ->exists();
    }

    protected function importUser($row)
    {
        $userCount = User::find()
            ->where(['email' => $row['kontragent-email']])
            ->orWhere(['username' => $row['kontragent-name']])
            ->count();
        if ($userCount > 1) {
            throw new \Exception('There are many users with given params');
        }
        if ($userCount) {
            $user = User::find()
                ->where(['email' => $row['kontragent-email']])
                ->orWhere(['username' => $row['kontragent-name']])
                ->one();
        } else {
            $user = new User();
            $user->username = $row['kontragent-name'];
            $user->email = $row['kontragent-email'];
            $user->setPassword(Yii::$app->security->generateRandomString());
            $user->generateAuthKey();
            $user->balance = 0;
        }

        if ($row['transaction-type'] == Transaction::TYPE_DEPOSIT) {
            $user->balance -= $row['transaction-amount'];
        } elseif ($row['transaction-type'] == Transaction::TYPE_WITHDRAWAL) {
            $user->balance += $row['transaction-amount'];
        }

        $user->save()
        $auth = Yii::$app->authManager;
        $kontragentRole = $auth->getRole('kontragent');
        $auth->assign($kontragentRole, $user->getId());

        return $user;
    }

    protected function importInvoice(User $user, $row)
    {
        $invoice = Invoice::find()
            ->where(['id' => $row['invoice-id']])
            ->one();
        if (!$invoice) {
            $invoice = new Invoice();
            $invoice->id = $row['invoice-id'];
            $invoice->user_id = $user->ID;
        }
        if ($row['transaction-type'] == Transaction::TYPE_DEPOSIT) {
            $invoice->balance += $row['transaction-amount'];
        } elseif ($row['transaction-type'] == Transaction::TYPE_WITHDRAWAL) {
            $invoice->balance -= $row['transaction-amount'];
        }
        $invoice->save();
        return $invoice;
    }

    protected function importTransaction(User $user, Invoice $invoice, $row)
    {
        $transaction = new Transaction();
        $transaction->invoice_id = $invoice->id;
        $transaction->type = $row['transaction-type'];
        $transaction->amount = $row['transaction-amount'];
        $transaction->stamp = $row['transaction-stamp'];
        $transaction->balance_after = $user->balance;
        $transaction->save();
    }
}
