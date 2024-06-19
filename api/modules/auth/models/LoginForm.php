<?php

namespace api\modules\auth\models;

use api\models\User;
use common\modules\user\models\UserDevice;
use soft\base\SoftModel;
use soft\helpers\ArrayHelper;
use soft\helpers\PhoneHelper;
use Yii;
use yii\base\Model;

/**
 * Login form
 *
 * @property-read string $clearPhone
 * @property-read null|\common\models\User $user
 */
class LoginForm extends SoftModel
{

    const PHONE_PATTERN = '/[9][9][8]\d{9}/';

    public $username;
    public $password;
    public $rememberMe = false;

    public $device_id;
    public $device_name;
    public $firebase_token;

    private $_user = false;

    /**
     * @var UserDevice
     */
    public $device;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'device_id', 'device_name'], 'required'],
            ['username', 'validatePhone'],
            ['password', 'validatePassword'],
            ['device_id', 'validateDevice'],
            ['firebase_token', 'string'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Telefon raqam',
            'password' => 'Parol',
            'rememberMe' => 'Eslab qolish',
            'device_id' => 'Qurilma ID',
            'device_name' => 'Qurilma nomi',
            'firebase_token' => 'FireBase token',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->getClearPhone());
        }
        return $this->_user;
    }

    /**
     * Telefon raqamni qo'shimcha belgilardan tozalash,
     * masalan: +998(99) 123-45-67 => 998991234567
     * @return string cleared phone number
     */
    public function getClearPhone()
    {
        return PhoneHelper::fullPhoneNumber($this->username);
    }

    /**
     * Validates phone number
     */
    public function validatePhone()
    {
        if (!preg_match(static::PHONE_PATTERN, $this->username)) {
            $this->addError('phone', t('Incorrect phone number'));
        }
    }


    /**
     * Validates device
     * @throws \yii\base\Exception
     */
    public function validateDevice(): bool
    {
        if ($this->hasErrors()) {
            return false;
        }

        $user = $this->user;
        $activeDevices = $user->activeDevices;
        $activeDeviceIds = ArrayHelper::getColumn($activeDevices, 'device_id');

        $device = UserDevice::findOne(['device_id' => $this->device_id, 'user_id' => $this->user->id]);

        if (in_array($this->device_id, $activeDeviceIds)) {

            $device->device_name = $this->device_name;
            $device->firebase_token = $this->firebase_token;
            $device->generateToken();
            $device->save(false);
            $this->device = $device;
            return true;
        }

        $activeDevicesCount = count($activeDevices);

        if ($activeDevicesCount >= $user->allowedActiveDevicesCount) {

            if ($device) {

                $firstOldDevice = UserDevice::find()
                    ->andWhere(['!=', 'id', $device->id])
                    ->andWhere(['user_id' => $user->id, 'status' => UserDevice::STATUS_ACTIVE])
                    ->orderBy(['created_at' => SORT_ASC])
                    ->one();

                if ($firstOldDevice) {
                    $firstOldDevice->status = UserDevice::STATUS_INACTIVE;
                    $firstOldDevice->save(false);
                }

                $device->device_name = $this->device_name;
                $device->firebase_token = $this->firebase_token;
                $device->status = UserDevice::STATUS_ACTIVE;
                $device->generateToken();

                if ($device->save(false)) {
                    $this->device = $device;
                    return true;
                }
            } else {
                $firstOldDevice = UserDevice::find()
                    ->andWhere(['user_id' => $user->id, 'status' => UserDevice::STATUS_ACTIVE])
                    ->orderBy(['created_at' => SORT_ASC])
                    ->one();

                if ($firstOldDevice) {
                    $firstOldDevice->status = UserDevice::STATUS_INACTIVE;
                    $firstOldDevice->save(false);
                }
            }
            $device = new UserDevice([
                'device_id' => $this->device_id,
                'device_name' => $this->device_name,
                'user_id' => $user->id,
                'status' => UserDevice::STATUS_ACTIVE,
                'firebase_token' => $this->firebase_token,
                'token' => ':' . Yii::$app->security->generateRandomString(60),
            ]);

            if ($device->save(false)) {
                $this->device = $device;
                return true;
            }

            $this->addError('phone', $device->getFirstErrorMessage());

//            $this->addError('device_id', Yii::t('app', 'You can not add more than {max} devices', ['max' => $user->allowedActiveDevicesCount]));
            return false;
        }

        if ($device == null) {

            // agar ushbu device bazada mavjud bo'lmasa, yangi qo'shiladi
            $device = new UserDevice([
                'device_id' => $this->device_id,
                'user_id' => $user->id,
            ]);
        }

        $device->device_name = $this->device_name;
        $device->firebase_token = $this->firebase_token;
        $device->status = UserDevice::STATUS_ACTIVE;
        $device->generateToken();

        if ($device->save(false)) {

            $this->device = $device;
            return true;
        } else {
            $this->addError('device_id', $device->getFirstErrorMessage());
            return false;
        }

    }
}