<?php

namespace common\models;

use backend\modules\regionmanager\models\Region;
use common\models\query\UserQuery;
use common\modules\book\models\Book;
use common\modules\book\models\BookPromoCode;
use common\modules\order\models\Order;
use common\modules\tariff\models\Tariff;
use common\modules\user\models\Enroll;
use common\modules\user\models\UserPayment;
use common\modules\video\models\Video;
use common\traits\UserBookOrderTrait;
use common\traits\UserBookPromoCodeTrait;
use common\traits\UserDeviceTrait;
use common\traits\UserEnrollTrait;
use common\traits\UserTariffTrait;
use soft\helpers\ArrayHelper;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property-read string $authKey
 * @property string $firstname [varchar(255)]
 * @property string $lastname [varchar(255)]
 *
 * @property-read mixed $statusName
 * @property-read string $statusBadge
 * @property-read string $fullname
 * @property integer $notice_status
 *
 *
 * @property string $Host [char(60)]
 * @property string $User [char(32)]
 * @property string $Select_priv [enum('N', 'Y')]
 * @property string $Insert_priv [enum('N', 'Y')]
 * @property string $Update_priv [enum('N', 'Y')]
 * @property string $Delete_priv [enum('N', 'Y')]
 * @property string $Create_priv [enum('N', 'Y')]
 * @property string $Drop_priv [enum('N', 'Y')]
 * @property string $Reload_priv [enum('N', 'Y')]
 * @property string $Shutdown_priv [enum('N', 'Y')]
 * @property string $Process_priv [enum('N', 'Y')]
 * @property string $File_priv [enum('N', 'Y')]
 * @property string $Grant_priv [enum('N', 'Y')]
 * @property string $References_priv [enum('N', 'Y')]
 * @property string $Index_priv [enum('N', 'Y')]
 * @property string $Alter_priv [enum('N', 'Y')]
 * @property string $Show_db_priv [enum('N', 'Y')]
 * @property string $Super_priv [enum('N', 'Y')]
 * @property string $Create_tmp_table_priv [enum('N', 'Y')]
 * @property string $Lock_tables_priv [enum('N', 'Y')]
 * @property string $Execute_priv [enum('N', 'Y')]
 * @property string $Repl_slave_priv [enum('N', 'Y')]
 * @property string $Repl_client_priv [enum('N', 'Y')]
 * @property string $Create_view_priv [enum('N', 'Y')]
 * @property string $Show_view_priv [enum('N', 'Y')]
 * @property string $Create_routine_priv [enum('N', 'Y')]
 * @property string $Alter_routine_priv [enum('N', 'Y')]
 * @property string $Create_user_priv [enum('N', 'Y')]
 * @property string $Event_priv [enum('N', 'Y')]
 * @property string $Trigger_priv [enum('N', 'Y')]
 * @property string $Create_tablespace_priv [enum('N', 'Y')]
 * @property string $ssl_type [enum('', 'ANY', 'X509', 'SPECIFIED')]
 * @property string $ssl_cipher [blob]
 * @property string $x509_issuer [blob]
 * @property string $x509_subject [blob]
 * @property int $max_questions [int(11) unsigned]
 * @property int $max_updates [int(11) unsigned]
 * @property int $max_connections [int(11) unsigned]
 * @property int $max_user_connections [int(11) unsigned]
 * @property string $plugin [char(64)]
 * @property string $authentication_string
 * @property string $password_expired [enum('N', 'Y')]
 * @property int $password_last_changed [timestamp]
 * @property int $password_lifetime [smallint(5) unsigned]
 * @property int $deleted_at [smallint(5) unsigned]
 * @property int $deleted_by [smallint(5) unsigned]
 * @property string $account_locked [enum('N', 'Y')]
 * @property-read null|string $firstErrorMessage
 * @property-read User $deletedBy
 *
 * @property-read string $image
 * @property-read Region[] $region
 */
class User extends ActiveRecord implements IdentityInterface
{

    use UserDeviceTrait;
    use UserTariffTrait;
    use UserEnrollTrait;
    use UserBookOrderTrait;
    use UserBookPromoCodeTrait;

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 9;

    public static $extensions = ['jpeg', 'jpg', 'png', 'psd'];

    /**
     * Yangi user qo'shishda kerak bo'ladi
     */
    const SCENARIO_REGISTER = 'register';

    const SCENARIO_REGISTER_EMAIL = 'register_email';

    /**
     * Userni parolni o'zgartirishda kerak bo'ladi
     */
    const SCENARIO_RESET_PASSWORD = 'reset_password';

    const SCENARIO_UPDATE_NAME = 'update_name';

    const SCENARIO_UPDATE_IMAGE = 'update_image';

    const SCENARIO_UPDATE_USERNAME = 'update_username';

    public $password;

    public $paymentSum;

    public $userTariffSum;

    //<editor-fold desc="Parent methods" defaultstate="collapsed">

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'unique', 'message' => 'Ushbu telefon raqam avvalroq band qilingan.', 'on' => self::SCENARIO_REGISTER],
            [['username', 'firstname'], 'required'],
            [['username', 'firstname', 'lastname'], 'string', 'max' => 255],
            ['password', 'string', 'min' => 5],
            ['password', 'trim'],
            ['image', 'trim'],
            [['image'], 'image', 'maxSize' => 1024 * 1024 * 3],
            [['deleted_at', 'deleted_by'], 'integer'],
            ['username', 'string', 'on' => self::SCENARIO_REGISTER_EMAIL],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['username', 'unique', 'message' => 'Ushbu telefon raqam avvalroq band qilingan.', 'on' => self::SCENARIO_UPDATE_USERNAME],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * @throws \Exception
     */

    /**
     * @return string[]
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Telefon raqam',
            'firstname' => 'Ism',
            'lastname' => 'Familiya',
            'fullname' => 'Foydalanuvchi F.I.O',
            'password' => 'Parol',
            'created_at' => "Yaratildi",
            'updated_at' => "Tahrirlandi",
            'deleted_at' => "O'chirilgan vaqti",
            'deleted_by' => "O'chirdi",
            'devicesCount' => 'Qurilmalar soni',
            'activeDevicesCount' => 'Faol qurilmalar soni',
            'status' => 'Holati'
        ];
    }

    /**
     * @return array
     */
    public function scenarios(): array
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_REGISTER] = ['firstname', 'lastname', 'username', 'password', 'image'];
        $scenarios[self::SCENARIO_REGISTER_EMAIL] = ['firstname', 'lastname', 'username', 'password'];
        $scenarios[self::SCENARIO_RESET_PASSWORD] = ['password'];
        $scenarios[self::SCENARIO_UPDATE_IMAGE] = [];
        $scenarios[self::SCENARIO_UPDATE_NAME] = ['firstname', 'lastname', 'image'];
        $scenarios[self::SCENARIO_UPDATE_USERNAME] = ['username'];
        return $scenarios;
    }

    /**
     * @return UserQuery|ActiveQuery
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * @param $insert
     * @param $changedAttributes
     * @return bool|void
     */
    public function afterSave($insert, $changedAttributes)
    {
        $token = explode(':', $this->auth_key);

        if (!is_numeric($token[0])) {
            $this->auth_key = $this->id . ':' . Yii::$app->security->generateRandomString(60);
            return $this->save(false);
        }

        return parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        $this->username = $this->username . '_' . $this->id;
        $this->status = self::STATUS_DELETED;
        $this->deleted_at = time();
        $this->deleted_by = user('id');
        $this->save(false);
    }

    /**
     * @return string[]
     */
    public function attributeHints()
    {
        return [
            'username' => 'Telefon formati uchun namuna: 998911234567'
        ];
    }
    //</editor-fold>

    //<editor-fold desc="Required methods" defaultstate="collapsed">

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
//        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
        return static::findOne(['auth_key' => $token, 'status' => static::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = ':' . Yii::$app->security->generateRandomString(60);
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString(60);
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    //</editor-fold>

    //<editor-fold desc="Additional" defaultstate="collapsed">

    /**
     * @param string $permissionName
     * @param array $params
     * @return bool
     */
    public function can($permissionName, $params = [])
    {
        return Yii::$app->authManager->checkAccess($this->getId(), $permissionName, $params);
    }

    /**
     * @return string
     */
    public function getFullname()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    /**
     * @return string the first error text of the model after validating
     * */
    public function getFirstErrorMessage()
    {
        $firstErrors = $this->firstErrors;
        if (empty($firstErrors)) {
            return null;
        }
        $array = array_values($firstErrors);
        return ArrayHelper::getArrayValue($array, 0, null);
    }


    //</editor-fold>

    //<editor-fold desc="Status" defaultstate="collapsed">

    /**
     * @return string[]
     */
    public static function statuses()
    {
        return [
            self::STATUS_ACTIVE => 'Faol',
            self::STATUS_INACTIVE => 'Nofaol',
            self::STATUS_DELETED => "O'chirilgan",
        ];
    }

    /**
     * @return mixed|null
     */
    public function getStatusName()
    {
        return ArrayHelper::getArrayValue(self::statuses(), $this->status, $this->status);
    }

    /**
     * @return string
     */
    public function getStatusBadge(): string
    {
        switch ($this->status) {
            case self::STATUS_ACTIVE:
                return '<span class="badge badge-success">Faol</span>';
            case self::STATUS_INACTIVE:
                return '<span class="badge badge-danger">Nofaol</span>';
            case self::STATUS_DELETED:
                return '<span class="badge badge-default">O\'chirilgan</span>';
            default:
                return '<span class="badge badge-default">' . $this->status . '</span>';
        }
    }

    //</editor-fold>


    /**
     * @return array
     */
    public static function users()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'username');
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {

        return $this->image ? Yii::$app->request->hostInfo . '/uploads/user/' . $this->image : Yii::$app->urlManager->hostInfo . '/template/img/Avatar.png';
    }

    /**
     * @return ActiveQuery
     */
    public function getUserPayments()
    {
        return $this->hasMany(UserPayment::class, ['user_id' => 'id']);
    }

    /**
     * @param Tariff $tariff
     * @return Order|false
     */
    public function tariffOrderCreate(Tariff $tariff, $payment_type)
    {
        $order = new Order([
            'user_id' => user('id'),
            'price' => $tariff->price,
            'type_id' => Order::$type_id_tariff,
            'tariff_id' => $tariff->id,
            'payment_type_id' => $payment_type,
            'status' => Order::STATUS_INACTIVE
        ]);

        return $order->save() ? $order : false;
    }

    /**
     * @param Book $book
     * @return Order|false
     */
    public function bookOrderCreate(Book $book, $payment_type)
    {
        $order = new Order([
            'user_id' => user('id'),
            'price' => $book->pre_order_price,
            'type_id' => Order::$type_id_book,
            'book_id' => $book->id,
            'payment_type_id' => $payment_type,
            'status' => Order::STATUS_INACTIVE
        ]);

        return $order->save() ? $order : false;
    }

    /**
     * @param Video $course
     * @return Order|false
     */
    public function courseOrderCreate(Video $course, $payment_type)
    {
        $price = $course->price;

        if (Yii::$app->user->identity->hasActiveTariff && $course->price_for_subscribers) {
            $price = $course->price_for_subscribers;
        }
        $order = new Order([
            'user_id' => user('id'),
            'price' => $price,
            'type_id' => Order::$type_id_special_course,
            'video_id' => $course->id,
            'payment_type_id' => $payment_type,
            'status' => Order::STATUS_INACTIVE
        ]);

        return $order->save() ? $order : false;
    }

    /**
     * @param int $amount Amount of money in uzs
     * @param string $typeId one of UserPayment::TYPE_* constants
     * @return bool
     */
    public function addBalance(int $amount, int $paymentTypeId, int $typeId, $transactionId = null, $owner_id = null, $table_id = null)
    {
        $payment = new UserPayment();
        $payment->user_id = $this->id;
        $payment->amount = $amount;
        $payment->type_id = $typeId;
        $payment->payment_type_id = $paymentTypeId;
        $payment->transaction_id = $transactionId;
        $payment->owner_id = $owner_id;
        $payment->table_id = $table_id;
        if ($payment->save()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return array
     */
    public static function map()
    {
        return ArrayHelper::map(self::find()->andWhere(['!=', 'status', self::STATUS_DELETED])->all(), 'id', function (User $model) {
            return $model->id . ' | ' . $model->username . ' | ' . $model->getFullname();
        });
    }

    /**
     * @return string[]
     */
    public static function formStatuses()
    {
        return [
            self::STATUS_ACTIVE => 'Faol',
            self::STATUS_INACTIVE => 'Nofaol',
        ];
    }

    /**
     * @return bool
     */
    public function getIsDeleted()
    {
        return $this->status === self::STATUS_DELETED;
    }

    //<editor-fold desc="relations">

    /**
     * @return ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(User::class, ['id' => 'deleted_by']);
    }

    //</editor-fold>

    /**
     * @return string
     */
    public function getAllExtensionsName()
    {
        $text = '';

        foreach (self::$extensions as $extension) {
            $text .= $extension . ' ';
        }

        return $text;
    }
}
