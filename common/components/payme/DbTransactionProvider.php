<?php
/*
 * @author Shukurullo Odilov
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 29.12.2021, 16:11
 */

namespace common\components\payme;

use common\models\PaymeTransaction;

class DbTransactionProvider extends \shoxabbos\paymeuz\yii2\DbTransactionProvider
{


    public function getByTransId($transId)
    {
        $trans = $this->db->select("*")->from($this->tableName)->where(['paycom_transaction_id' => $transId])->one();

        if ($trans) {
            /**
             * Karoche kakoyta gluk v PDO
             * u vsex polyax tip znacheniya string
             * Hotya v db vse ok
             */
            $trans['create_time'] = intval($trans['create_time']);
            $trans['cancel_time'] = intval($trans['cancel_time']);
            $trans['perform_time'] = intval($trans['perform_time']);
            $trans['time'] = intval($trans['time']);
            $trans['state'] = is_null($trans['state']) ? null : intval($trans['state']);
            $trans['amount'] = is_null($trans['amount']) ? null : intval($trans['amount']);
            $trans['reason'] = is_null($trans['reason']) ? null : intval($trans['reason']);
        }

        return $trans;
    }

    public function getByOrderId($orderId)
    {
        $trans = $this->db->select("*")->from($this->tableName)->where(['order_id' => $orderId])->one();

        if ($trans) {
            /**
             * Karoche kakoyta gluk v PDO
             * u vsex polyax tip znacheniya string
             * Hotya v db vse ok
             */
            $trans['create_time'] = intval($trans['create_time']);
            $trans['cancel_time'] = intval($trans['cancel_time']);
            $trans['perform_time'] = intval($trans['perform_time']);
            $trans['time'] = intval($trans['time']);
            $trans['state'] = is_null($trans['state']) ? null : intval($trans['state']);
            $trans['amount'] = is_null($trans['amount']) ? null : intval($trans['amount']);
            $trans['reason'] = is_null($trans['reason']) ? null : intval($trans['reason']);
        }

        return $trans;
    }

    /**
     * @param $orderId
     * @return array|bool
     */
    public function checkIfIssetWaitingTransactionByOrderId($orderId, $transId)
    {
        return $this->db->select("*")
            ->from($this->tableName)
            ->where(['order_id' => $orderId, 'state' => 1])
            ->andWhere(['!=', 'paycom_transaction_id', $transId])
            ->exists();
    }

    public function update($transId, array $fields)
    {
        return $this->db->createCommand()
            ->update($this->tableName, $fields, ['paycom_transaction_id' => $transId])
            ->execute();
    }

    /**
     * @param $from
     * @param $to
     * @return array
     */
    public function getByRange($from, $to)
    {

        $transactions = PaymeTransaction::find()
            ->andWhere(['>=', 'create_time', $from])
            ->andWhere(['<=', 'create_time', $to])
            ->all();

        $result = [];

        if (!empty($transactions)) {
            foreach ($transactions as $transaction) {
                $result[] = [
                    'id' => $transaction->id,
                    'time' => $transaction->time,
                    'amount' => $transaction->amount,
                    'account' => [
                        'order_id' => $transaction->order_id
                    ]
                ];

            }
        }

        return $result;
    }

}
