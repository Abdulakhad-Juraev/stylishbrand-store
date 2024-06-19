<?php
/*
 * @author Shukurullo Odilov
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 29.12.2021, 14:36
 */

namespace common\components\payme;

use common\models\User;
use common\modules\order\models\Order;
use common\models\PaymeTransaction;
use common\modules\tariff\models\Tariff;
use common\modules\video\models\Video;
use common\services\TelegramService;
use shoxabbos\paymeuz\AbstractPayme;
use soft\helpers\ArrayHelper;
use Yii;

class Wallet extends AbstractPayme
{

    const MODE_PROD = 1;
    const MODE_TEST = 2;

    /**
     * List fields
     *
     * @var array $accounts
     */
    protected $accounts = ["order_id"];

    /**
     * Table of transactions
     *
     * @var string $tableName
     */
    protected $tableName = "paycom_transactions";

    /**
     * Min summ
     *
     * @var int $minSum
     */
    protected $minSum = 1000;

    /**
     * Max summ
     *
     * @var int $maxSum
     */
    protected $maxSum = 100000000;

    /**
     * Transaction timeout
     *
     * @var int $timeout
     */
    protected $timeout = 600 * 1000;

    /**
     * @var bool $canCancelSuccessTransaction
     */
    protected $canCancelSuccessTransaction = false;

    /**
     * @var \common\components\payme\DbTransactionProvider
     */
    protected $provider;

    public $login = 'Paycom';
    public $key = 'qypyWx8CjcmnkH6@8DjrAKHIkAXHOmeEZQnU';
    public $testKey = '&P9OE0be7PXzA?qp9sVo5Zp7m5mN&tIiemra';

    public $mode = self::MODE_PROD;

    /**
     * Wallet constructor.
     * @param string $request JSON request
     */
    public function __construct($request)
    {
        parent::__construct($request, new DbTransactionProvider($this->tableName));
        $this->response = new PaymeResponse($this->request);

        if ($this->mode === null) {
            $this->mode = YII_DEBUG ? self::MODE_TEST : self::MODE_PROD;
        }
    }


    /**
     * Transaksiya otkazib bolish imkoniyatini tekshiradi
     *
     * @return array
     * @throws \Exception
     */
    protected function checkPerformTransaction()
    {
        // Check account fields

        if (!$this->hasAuth()) {
            return $this->noAuthRespone();
        }

        if (!$this->hasOrderIdAccount()) {
            return $this->response->error(PaymeResponse::JSON_RPC_ERROR);
        }

        if (!$this->request->hasParam(["amount"])) {
            return $this->response->error(PaymeResponse::JSON_RPC_ERROR);
        }

        // Get vars
        $accounts = $this->request->getParam('account');
        $amount = $this->request->getParam("amount");

        /**
         * Your code here starts
         * Check your order/user or smthing exists
         **/

        // Check order

        $order = $this->checkOrderExists($accounts);

        if (!$order) {
            return $this->response->error(PaymeResponse::USER_NOT_FOUND);
        }

        if ($order->getIsPayed()) {
            return $this->response->error(PaymeResponse::ORDER_IS_ALREADY_PAID);
        }

        $mustPayedSum = (int)$order->getMustPayedSum();
        $amount = (int)($amount / 100);

        if ($amount != $mustPayedSum) {
            return $this->response->error(PaymeResponse::WRONG_AMOUNT);
        }

        /**
         * End your code
         * */

        // Check amount
        if ($amount < $this->minSum || $amount > $this->maxSum) {
            return $this->response->error(PaymeResponse::WRONG_AMOUNT);
        }

        // Success
        return $this->response->successCheckPerformTransaction($order->id);
    }

    /**
     * Transaksiya yaratadi
     *
     * @return array
     * @throws \Exception
     */
    protected function createTransaction()
    {

        if (!$this->hasAuth()) {
            return $this->noAuthRespone();
        }

        if (!$this->hasOrderIdAccount()) {
            return $this->response->error(PaymeResponse::JSON_RPC_ERROR);
        }

        $accounts = $this->request->getParam('account');

        $order = $this->checkOrderExists($accounts);

        if (!$order) {
            return $this->response->error(PaymeResponse::USER_NOT_FOUND);
        }

        // Check account fields
        if (!$this->request->hasParam(["amount", "time", "id"])) {
            return $this->response->error(PaymeResponse::JSON_RPC_ERROR);
        }

        $amount = (int)($this->request->getParam("amount") / 100); // convert tiyin to sum
        $transId = $this->request->getParam("id");
        $time = $this->request->getParam("time");

        // Check amount
        if ($amount < $this->minSum || $amount > $this->maxSum) {
            return $this->response->error(PaymeResponse::WRONG_AMOUNT);
        }

        // Check order

        $order = $this->checkOrderExists($accounts);

        if (!$order) {
            return $this->response->error(PaymeResponse::USER_NOT_FOUND);
        }

        $mustPayedSum = $order->getMustPayedSum();

        if ($amount != $mustPayedSum) {
            return $this->response->error(PaymeResponse::WRONG_AMOUNT);
        }

//        Buyurtma uchun allaqachon to'lov qilinganligini tekshirish
        if ($order->getIsPayed()) {
            return $this->response->error(PaymeResponse::ORDER_IS_ALREADY_PAID);
        }

        // Buyurtma uchun avval transaksiya yaratilgan va shu transaksiya kutish rejimida ekanligini tekshirish

        if ($this->checkIfHasWaitingTrans($order->id, $transId)) {
            return $this->response->error(PaymeResponse::ORDER_IS_WAITING);
        }

        // Transaksiyani statusini tekshirish

        if ($trans = $this->provider->getByTransId($transId)) {
            if ($trans['state'] != 1) {
                return $this->response->error(PaymeResponse::CANT_PERFORM_TRANS);
            }
            return $this->response->successCreateTransaction($trans['create_time'], $trans['id'], $trans['state']);
        }


        // Add new transaction
        try {

            $this->provider->insert([
                'paycom_transaction_id' => $transId,
                'time' => (string)$time,
                'amount' => $amount,
                'state' => 1,
                'create_time' => (string)$this->microtime(),
                'order_id' => $accounts['order_id'],
            ]);

            $trans = $this->provider->getByTransId($transId);

            return $this->response->successCreateTransaction($trans['create_time'], $trans['id'], $trans['state']);

        } catch (\Exception $e) {
            return $this->response->error(PaymeResponse::SYSTEM_ERROR);
        }
    }

    /**
     * Transaksiyani utqazish va buyurtma holatini to'langan qilib o'gartirish
     *
     * @return array
     */
    protected function performTransaction()
    {

        if (!$this->hasAuth()) {
            return $this->noAuthRespone();
        }

        // Check fields
        if (!$this->request->hasParam(["id"])) {
            return $this->response->error(PaymeResponse::JSON_RPC_ERROR);
        }

        // Search by id
        $transId = $this->request->getParam('id');
        $trans = $this->provider->getByTransId($transId);

        if (!$trans) {
            return $this->response->error(PaymeResponse::TRANS_NOT_FOUND);
        }

        if ($trans['state'] != 1) {
            if ($trans['state'] == 2) {
                return $this->response->successPerformTransaction($trans['state'], $trans['perform_time'], $trans['id']);
            } else {
                return $this->response->error(PaymeResponse::CANT_PERFORM_TRANS);
            }
        }

        // Check timeout
        if (!$this->checkTimeout($trans['create_time'])) {
            $this->provider->update($transId, [
                "state" => -1,
                "reason" => 4
            ]);

            return $this->response->error(PaymeResponse::CANT_PERFORM_TRANS, [
                "uz" => "Vaqt tugashi o'tdi",
                "ru" => "Тайм-аут прошел",
                "en" => "Timeout passed"
            ]);
        }

        try {

            /**
             * Your code here
             **/
            $order = Order::findOne($trans['order_id']);

            if (!$order) {
                return $this->response->error(PaymeResponse::USER_NOT_FOUND);
            }

            $mustPayedSum = (int)$order->getMustPayedSum();
            $amount = (int)$trans['amount'];

            if ($amount < $mustPayedSum) {
                return $this->response->error(PaymeResponse::AMOUNT_NOT_ENOUGH);
            }

            $order->transaction_id = $trans['id'];
            $order->status = Order::STATUS_ACTIVE;

            $user = User::findOne($order->user_id);

            // kitob uchun tekshirish shart emas, order tabledan olinaveradi ma'lumotlar
            $owner_id = $order->book_id;

            if ($order->type_id == Order::$type_id_tariff) {
                $tariff = Tariff::findOne($order->tariff_id);
                $owner_id = $tariff->id;
                $user->purchaseTariff($tariff, $order->id, $order->payment_type_id);
            }


            // maxsus kursni sotib olish
            if ($order->type_id == Order::$type_id_special_course) {
                $course = Video::findOne($order->video_id);
                $owner_id = $course->id;
                $user->purchaseCourse($course, $order, $order->payment_type_id);
            }

            $user->addBalance($order->price, $order->payment_type_id, $order->type_id, $order->transaction_id, $owner_id, null);

            if (!$order->save(false)) {
                return $this->response->error(PaymeResponse::ERROR_WHILE_SAVING_ORDER);

            }

            /**
             * End your code
             * */

            $performTime = $this->microtime();
            $this->provider->update($transId, [
                "state" => 2,
                "perform_time" => $performTime
            ]);

            return $this->response->successPerformTransaction(2, $performTime, $trans['id']);
        } catch (\Exception $e) {
            return $this->response->error(PaymeResponse::CANT_PERFORM_TRANS);
        }
    }


    /**
     * Transaksiyani statusini tekshiradi
     *
     * @return array
     */
    protected function checkTransaction()
    {
        if (!$this->hasAuth()) {
            return $this->noAuthRespone();
        }


        // Check fields
        if (!$this->request->hasParam(["id"])) {
            return $this->response->error(PaymeResponse::NO_AUTH);
        }

        $transId = $this->request->getParam("id");
        $trans = $this->provider->getByTransId($transId);

        if ($trans) {
            return $this->response->successCheckTransaction(
                $trans['create_time'],
                $trans['perform_time'],
                $trans['cancel_time'],
                $trans['id'],
                $trans['state'],
                $trans['reason']
            );
        } else {
            return $this->response->error(PaymeResponse::TRANS_NOT_FOUND);
        }
    }


    /**
     * Transaksiyani qaytarish va foydalanuvchi hisobidan yechib olish
     *
     * @return array
     */
    protected function cancelTransaction()
    {
        if (!$this->hasAuth()) {
            return $this->noAuthRespone();
        }

        // Check fields
        if (!$this->request->hasParam(["id", "reason"])) {
            return $this->response->error(PaymeResponse::NO_AUTH);
        }

        $transId = $this->request->getParam("id");
        $reason = $this->request->getParam("reason");
        $trans = $this->provider->getByTransId($transId);

        if (!$trans) {
            $this->response->error(PaymeResponse::TRANS_NOT_FOUND);
        }

        if ($trans['state'] == 1) {
            $cancelTime = $this->microtime();
            $this->provider->update($transId, [
                "state" => -1,
                "cancel_time" => $cancelTime,
                "reason" => $reason
            ]);

            return $this->response->successCancelTransaction(-1, $cancelTime, $trans['id']);
        }

        if ($trans['state'] != 2) {
            return $this->response->successCancelTransaction($trans['state'], $trans['cancel_time'], $trans['id']);
        }

        try {
            $this->cancelOrderPayment($trans['order_id'], $trans['amount']);

            $cancelTime = $this->microtime();
            $this->provider->update($transId, [
                "state" => -2,
                "cancel_time" => $cancelTime,
                "reason" => $reason
            ]);

            return $this->response->successCancelTransaction(-2, $cancelTime, $trans['id']);
        } catch (\Exception $e) {
            return $this->response->error(PaymeResponse::CANT_CANCEL_TRANSACTION);
        }
    }


    /**
     * Hozircha bu metod hech narsa qilmaydi, lekin keyin albatta qilaman
     */
    public function getStatement()
    {
        if (!$this->hasAuth()) {
            return $this->noAuthRespone();
        }

        if (!$this->request->hasParam(["from", "to"])) {
            return $this->response->error(PaymeResponse::NO_AUTH);
        }

        $from = $this->request->getParam('from');
        $to = $this->request->getParam('to');

//        $transactions = $this->provider->getByRange($from, $to);
        $transactions = PaymeTransaction::find()
            ->andWhere(['>=', 'create_time', $from])
            ->andWhere(['<=', 'create_time', $to])
            ->all();

        $data = [];

        foreach ($transactions as $transaction) {
            $data[] = [
                'id' => $transaction->paycom_transaction_id,
                'time' => $transaction->time,
                'amount' => $transaction->amount * 100,
                'account' => [
                    'order_id' => $transaction->order_id
                ],
                'create_time' => (int)$transaction->create_time,
                'perform_time' => (int)$transaction->perform_time,
                'cancel_time' => (int)$transaction->cancel_time,
                'transaction' => $transaction->paycom_transaction_id,
                'state' => $transaction->state,
                'reason' => $transaction->reason,
                'receivers' => null
            ];
        }

        return [
            "result" => [
                'transactions' => $data
            ],
        ];
    }

    /**
     * Bu metod parolni uzgartirish uchun kk
     */
    protected function changePassword()
    {
        if (!$this->hasAuth()) {
            return $this->noAuthRespone();
        }


        // TODO: Implement ChangePassword() method.
    }

    /**
     * Transaksiyani tekshiradi timeoutga qarab
     *
     * @param $created_time
     * @return bool
     */
    private function checkTimeout($created_time)
    {
        return $this->microtime() <= ($created_time + $this->timeout);
    }

    /**
     * @param $accounts array
     * @return \backend\modules\ordermanager\models\Order|bool|Order
     * @throws \Exception
     */
    private function checkOrderExists($accounts)
    {
        $orderId = ArrayHelper::getValue($accounts, 'order_id');
        if (!$orderId) {
            return false;
        }
        $order = Order::findOne($orderId);
        if ($order != null) {
            return $order;
        }
        return false;
    }

    /**
     * Order uchun to'langan pulni bekor qilish
     * @param $order_id
     * @param $amount
     * @return bool
     * @throws \Exception
     */
    private function cancelOrderPayment($order_id, $amount)
    {
        $order = Order::findOne($order_id);
        if ($order && $order->price >= $amount) {

//            $order->payed_sum -= $amount;
//            $order->payed_at = null;
            $order->transaction_id = null;
//            $order->payed_by = null;
            $order->status = Order::STATUS_INACTIVE;
//            $order->payment_type_id = Order::$payment_status_no_payed;
            return $order->save(false);

        } else {
            throw new \Exception("Can't withdraw balance");
        }
    }

    /**
     * @param $order_id int|string
     * @return array|bool
     */
    private function checkIfHasWaitingTrans($order_id, $transId)
    {
        return $this->provider->checkIfIssetWaitingTransactionByOrderId($order_id, $transId);
    }

    /**
     * @return bool
     */
    private function hasOrderIdAccount()
    {
        return $this->request->hasAccounts(['order_id']);
    }

    /**
     * @return bool
     */
    private function hasAuth()
    {

        $username = Yii::$app->request->getAuthUser();
        $password = Yii::$app->request->getAuthPassword();
        $key = $this->mode == self::MODE_PROD ? $this->key : $this->testKey;
        return $username == $this->login && $password == $key;

    }

    /**
     * @return array
     */
    private function noAuthRespone()
    {
        return $this->response->error(PaymeResponse::NO_AUTH);
    }

}
