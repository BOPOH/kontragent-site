<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Transaction;

/**
 * TransactionSearch represents the model behind the search form of `common\models\Transaction`.
 */
class TransactionSearch extends Transaction
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'invoice_id', 'type', 'amount', 'balance_after'], 'integer'],
            [['stamp'], 'datetime', 'format' => 'yyyy-M-d H:m:s'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Transaction::find();
        $query->joinWith('invoice');
        $query->joinWith('user');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'invoice_id',
                'user_id' => [
                    'asc' => ['user.username' => SORT_ASC],
                    'desc' => ['user.username' => SORT_DESC],
                ],
                'type',
                'amount',
                'balance_after',
                'stamp',
            ],
        ]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'transaction.id' => $this->id,
            'invoice.id' => $this->invoice_id,
            'type' => $this->type,
            'amount' => $this->amount,
            'balance_after' => $this->balance_after,
        ]);
        if ($this->stamp) {
            $date =  new \DateTime($this->stamp);
            $start = $date->format('Y-m-d');
            $date->modify('+1 day');
            $date->modify('-1 seconds');
            $end = $date->format('Y-m-d H:i:s');
            $query->andFilterWhere(['between', 'stamp', $start, $end]);
        }
        $query->andFilterWhere(['like', 'user.username', $this->user_id]);

        return $dataProvider;
    }
}
