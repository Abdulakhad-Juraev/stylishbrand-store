<?php

namespace backend\modules\click\controllers;

/**
 * @author Olimjon Gofurov
 * Date: 26/05/21
 * Time: 11:25
 */

use common\modules\order\models\Order;
use soft\web\SoftController;
use Yii;
use backend\modules\click\components\ClickData;
use backend\modules\click\models\ClickTransactions;
use yii\web\Response;

class ClickController extends SoftController
{

    public function init()
    {
        parent::init();
        Yii::$app->response->format = Response::FORMAT_JSON;
    }

    private $reqData = [];

    public $writeLogs = true;

    private function validateData(): array
    {
        //check complete parameters: Unknown Error
        if (!isset($this->reqData['click_trans_id'], $this->reqData['service_id'], $this->reqData['click_paydoc_id'], $this->reqData['merchant_trans_id'], $this->reqData['amount'], $this->reqData['action'], $this->reqData['sign_time'], $this->reqData['sign_string'], $this->reqData['error'])
        ) {
            return [
                'isValidated' => false,
                'message' => ClickData::getMessage(ClickData::ERROR_ERROR_REQUEST_CLICK)
            ];
        }

        // Confirming the authenticity of the submitted query
        $sign_string_verified = md5(
            $this->reqData['click_trans_id'] .
            $this->reqData['service_id'] .
            ClickData::$secretKey .
            $this->reqData['merchant_trans_id'] .
            (($this->reqData['action'] == 1) ? $this->reqData['merchant_prepare_id'] : '') .
            $this->reqData['amount'] .
            $this->reqData['action'] .
            $this->reqData['sign_time']
        );

        if ($this->reqData['sign_string'] != $sign_string_verified) {
            return [
                'isValidated' => false,
                'message' => ClickData::getMessage(ClickData::ERROR_FAILED_SIGN)
            ];
        }

        // Check Actions: Action not found
        if (!in_array($this->reqData['action'], [ClickData::ACTION_PREPARE, ClickData::ACTION_COMPLETE], false)) {
            return [
                'isValidated' => false,
                'message' => ClickData::getMessage(ClickData::ERROR_ACTION_NOT_FOUND)
            ];
        }

        // Check sum: Incorrect parameter amount
        if (($this->reqData['amount'] < ClickData::$minAmount) || ($this->reqData['amount'] > ClickData::$maxAmount)) {
            return [
                'isValidated' => false,
                'message' => ClickData::getMessage(ClickData::ERROR_INCORRECT_AMOUNT)
            ];
        }

        return [
            'isValidated' => true,
            'message' => "Created by Ulugbek Muhammadjonov muhammadjonovulugbek98@gmail.com Tg: @U_Muhammadjonov"
        ];
    }

    public function actionPrepare()
    {
        $this->reqData = Yii::$app->request->post();

        /**
         * For Debugging purpose
         */
        if ($this->writeLogs) {
            $this->writeLogs("Prepare", json_encode($this->reqData));
        }

        $validate = $this->validateData();
        if (!$validate['isValidated']) {
            return $validate['message'];
        }

        $checkExists = ClickTransactions::find()->where(['click_trans_id' => $this->reqData['click_trans_id']])->one();
        if (!is_null($checkExists)) {
            if ($checkExists->status == ClickTransactions::STATUS_CANCEL) {
                //Transaction cancelled
                return ClickData::getMessage(ClickData::ERROR_TRANSACTION_CANCELLED);
            }
            // Already paid
            return ClickData::getMessage(ClickData::ERROR_ALREADY_PAID);
        }

        //Error in request from click
        if ($this->reqData['error'] != ClickData::ERROR_SUCCESS) {
            return ClickData::getMessage(ClickData::ERROR_ERROR_REQUEST_CLICK);
        }

        $newTransaction = new ClickTransactions();

        $newTransaction->user_id = $this->reqData['merchant_trans_id'];
        $newTransaction->click_trans_id = $this->reqData['click_trans_id'];
        $newTransaction->service_id = $this->reqData['service_id'];
        $newTransaction->amount = $this->reqData['amount'];
        $newTransaction->sign_time = $this->reqData['sign_time'];
        $newTransaction->click_paydoc_id = $this->reqData['click_paydoc_id'];
        $newTransaction->create_time = time();
        $newTransaction->status = ClickTransactions::STATUS_INACTIVE;

        if ($newTransaction->save(false)) {
            $merchant_prepare_id = $newTransaction->id;
            $return_array = array(
                'click_trans_id' => $this->reqData['click_trans_id'],        // Payment ID in CLICK system
                'merchant_trans_id' => $this->reqData['merchant_trans_id'],  // Order ID (for online shopping) / personal account / login in the billing of the supplier
                'merchant_prepare_id' => $merchant_prepare_id                // Payment ID in the billing system of the supplier
            );
            return array_merge(ClickData::getMessage(ClickData::ERROR_SUCCESS), $return_array);
        }
        // other case report: Unknown Error
        return array_merge(ClickData::getMessage(ClickData::ERROR_UNKNOWN), [
            'error_note' => "Filed to save transaction prepare in database"
        ]);
    }

    public function actionComplete()
    {
        $this->reqData = Yii::$app->request->post();

        /**
         * For Debugging purpose
         */
        if ($this->writeLogs) {
            $this->writeLogs("Complete", json_encode($this->reqData));
        }

        $validate = $this->validateData();
        if (!$validate['isValidated']) {
            return $validate['message'];
        }

        //Error in request from click
        if (empty($this->reqData['merchant_prepare_id'])) {
            return ClickData::getMessage(ClickData::ERROR_ERROR_REQUEST_CLICK);
        }

        if ($this->reqData['error'] != ClickData::ERROR_SUCCESS || $this->reqData['error'] < ClickData::ERROR_SUCCESS) {
            return ClickData::getMessage($this->reqData['error']);
        }
        // Start transaction DB
        $transaction = ClickTransactions::findOne([
                'id' => $this->reqData['merchant_prepare_id'],
                'user_id' => $this->reqData['merchant_trans_id'],
                'click_trans_id' => $this->reqData['click_trans_id'],
                'click_paydoc_id' => $this->reqData['click_paydoc_id'],
                'service_id' => $this->reqData['service_id'],
            ]
        );

        if (is_null($transaction)) {
            return ClickData::getMessage(ClickData::ERROR_TRANSACTION_NOT_FOUND);
        }

        if ($transaction->status == ClickTransactions::STATUS_ACTIVE) {
            return ClickData::getMessage(ClickData::ERROR_ALREADY_PAID);
        }

        if ($transaction->status == ClickTransactions::STATUS_CANCEL) {
            return ClickData::getMessage(ClickData::ERROR_TRANSACTION_CANCELLED);
        }

        if ($transaction->amount != $this->reqData['amount']) {
            return ClickData::getMessage(ClickData::ERROR_INCORRECT_AMOUNT);
        }

        $db_transaction = Yii::$app->db->beginTransaction();

        try {

            $transaction->status = ClickTransactions::STATUS_ACTIVE;
            if (!$transaction->save(false)) {
                $db_transaction->rollback();
                return array_merge(ClickData::getMessage(ClickData::ERROR_UNKNOWN), [
                    'error_note' => "Filed to save transaction complete in database"
                ]);
            }

            /**
             * Your Code Here!
             * After transaction completed successfully, you can manipulate your access.
             * Your code begin ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
             */

            $order = Order::findOne($transaction->user_id);
            if (is_null($order)) {
                $db_transaction->rollback();
                return ClickData::getMessage(ClickData::ERROR_TRANSACTION_CANCELLED);
            }

            if (!$order->pay($transaction->amount, Order::$type_click, $transaction->id)) {
                $db_transaction->rollback();
                return ClickData::getMessage(ClickData::ERROR_TRANSACTION_CANCELLED);
            }

            /**
             * Your code finish +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
             */

            $db_transaction->commit();
        } catch (\Exception $e) {
            $db_transaction->rollback();
            return ClickData::getMessage(ClickData::ERROR_TRANSACTION_CANCELLED);
        }


        $return_array = [
            'click_trans_id' => $transaction->click_trans_id,
            'merchant_trans_id' => $transaction->user_id,
            'merchant_confirm_id' => $transaction->id,
        ];

        return array_merge(ClickData::getMessage(ClickData::ERROR_SUCCESS), $return_array);
    }

    public function actionGetData(): array
    {
        return [
            'merchant_id' => ClickData::MERCHANT_ID,
            'merchant_user_id' => ClickData::MERCHANT_USER_ID,
            'service_id' => ClickData::SERVICE_ID,
        ];
    }

    private function writeLogs($action, $content)
    {
        $dir = Yii::getAlias('@backend') . '/modules/click/logs/';
        $file = $dir . $action . '_' . time() . '.txt';
        $content = $action . "\n" . $content;
        file_put_contents($file, $content);
    }
}
