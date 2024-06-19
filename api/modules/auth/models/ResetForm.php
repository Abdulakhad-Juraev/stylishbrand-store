<?php

namespace api\modules\auth\models;

use api\models\User;
use common\modules\tempUser\models\TempUser;
use common\modules\user\models\UserDevice;
use soft\base\SoftModel;
use soft\helpers\PhoneHelper;
use Yii;

class ResetForm extends SoftModel
{
    /**
     * Tasdiqlash kodining amal muddati (sekund)
     */
    const VERIFICATION_DURATION = 120;

    /**
     * Tasdiqlash kodini qayta jo'natish vaqti (sekund)
     */
    const RESEND_CODE_AFTER = 60;

    const SCENARIO_PHONE = 'phone';
    const SCENARIO_VERIFY = 'verify';
    const SCENARIO_RESET_PASSWORD = 'reset_password';
    const PHONE_PATTERN = '/[9][9][8]\d{9}/';

    public $phone;
    public $code;
    public $password;
    public $password_repeat;
    public $device_id;
    public $device_name;
    public $device_token;
    public $signature;
    private $_clearPhoneNumber;

    //<editor-fold desc="Overritten methods" defaultstate="collapsed">

    public function rules()
    {
        return [
            [['password'], 'required', 'message' => t('Password is required.')],
            [['password_repeat'], 'required', 'message' => t('Repeat the password')],
            ['password', 'string', 'min' => 6],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => t('The re-entered password does not match')],

            ['phone', 'trim'],
            ['phone', 'required'],
//            ['phone', 'match', 'pattern' => '/\+998\(\d{2}\) \d{3}\-\d{2}\-\d{2}/', 'message' => t('Incorrect phone number')],

            ['phone', 'checkPhone', 'on' => self::SCENARIO_PHONE],

            ['code', 'required', 'message' => t('Enter the code')],
            ['code', 'integer'],
            ['code', 'checkCode', 'on' => self::SCENARIO_VERIFY],

            [['device_id', 'device_name', 'device_token', 'signature'], 'string'],
            [['device_id', 'device_name'], 'required'],


        ];
    }

    public function attributeLabels(): array
    {
        return [
            'phone' => t('Your phone number'),
            'password' => t('Password'),
            'password_repeat' => t('Enter the password again'),
            'code' => t('Code'),
        ];
    }

    public function attributeHints(): array
    {
        return [
            'phone' => t('Enter your phone number to register'),
        ];
    }

    /**
     * @return array|array[]
     */
    public function scenarios(): array
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_PHONE] = ['phone', 'signature'];
        $scenarios[self::SCENARIO_VERIFY] = ['code', 'phone'];
        $scenarios[self::SCENARIO_RESET_PASSWORD] = ['password', 'password_repeat', 'code', 'phone', 'device_id', 'device_name', 'device_token'];
        return $scenarios;
    }

    // </editor-fold>

    //<editor-fold desc="Custom methods" defaultstate="collapsed">

    /**
     * Telefon raqamni qo'shimcha belgilardan tozalash,
     * masalan: +998(99) 123-45-67 => 998991234567
     * @return string cleared phone number
     */
    public function getClearPhone(): string
    {
        if ($this->_clearPhoneNumber === null) {
            $this->_clearPhoneNumber = PhoneHelper::fullPhoneNumber($this->phone);
        }
        return $this->_clearPhoneNumber;
    }

    // </editor-fold>

    //<editor-fold desc="Step 1: Enter and check phone number, send code via sms" defaultstate="collapsed">

    /**
     * Kiritilgan tel. raqam bazada bor yoki yo'qligini tekshirish
     * Agar mavjud bo'lsa, false qaytaradi
     */
    public function checkPhone(): bool
    {
        $phone = $this->getClearPhone();


        if (!empty($phone)) {

            if (!preg_match(static::PHONE_PATTERN, $phone)) {
                $this->addError('phone', t('Incorrect phone number'));
                return false;
            }

            $user = User::findOne(['username' => $phone]);
            if ($user == null) {
                $this->addError('phone', 'Bunday telefon raqam ro\'yhatga olinmagan!');
            } else {
                return true;
            }
        }
        return false;
    }


    public function generateCode()
    {
        if ($this->getClearPhone() == '998112223344') {
            return 1122;
        }
        if (Yii::$app->params['is_sms_test']) {
            return 7777;
        }
        return mt_rand(1000, 9999);
    }

    /**
     * Tel. raqam to'g'ri kiritilgandan keyin, ixtiyoriy kodni generatsiya qilish
     *  va kodni sms orqali jo'natish
     * @throws \yii\db\Exception
     */
    public function saveTempUser()
    {
        $code = $this->generateCode();
        $transaction = Yii::$app->db->beginTransaction();

        $phone = $this->getClearPhone();

        $tempUser = TempUser::find()
            ->andWhere([
                'phone' => $phone,
                'is_verified' => false,
                'is_registered' => false,
            ])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        if ($tempUser) {

            // agar user sms kodni qayta jonatishni bosgan bo'lsa va kodni qayta jo'natish vaqti hali
            // tugamagan bo'lsa, kodni qayta jonatish shart emas.
            // user shunchaki avvalgi yuborilgan kodni kiritaveradi
            if (!$tempUser->needResendCode(self::VERIFICATION_DURATION, self::RESEND_CODE_AFTER)) {

//                TelegramService::log("User {$phone} is trying to resend code, but not needed");

                return true;
            }

        }

        $tempUser = new TempUser([
            'code' => (string)$code,
            'phone' => $this->getClearPhone(),
            'expire_at' => time() + self::VERIFICATION_DURATION
        ]);

        if ($tempUser->save() && $this->sendCodeViaSms($code)) {
            $transaction->commit();
            return true;
        } else {
            $transaction->rollBack();
        }

        return false;
    }

    /**
     * Kodni sms orqali jo'natish
     */
    public function sendCodeViaSms($code)
    {
        $domain = 'Dosmetov';

        $message = Yii::t('app', 'signup_verify_sms_message', [
                'domain' => $domain,
                'code' => $code
            ]) . '. ' . $this->signature;

        if ($this->getClearPhone() == '998112223344') {
            return true;
        }

        if (Yii::$app->params['is_sms_test']) {
            return true;
        }

        return Yii::$app->sms->send($this->getClearPhone(), $message);
    }

    // </editor-fold>

    //<editor-fold desc="Step 2: Verify phone number via code" defaultstate="collapsed">

    /**
     * @return bool
     */
    public function checkCode()
    {

        $tempUser = TempUser::find()
            ->andWhere([
                'phone' => $this->getClearPhone(),
                'code' => $this->code,
                'is_verified' => false,
                'is_registered' => false,
            ])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        if (!$tempUser) {
            $this->addError('code', t('Invalid verification code'));
            return false;
        }

        if ($tempUser->isExpired) {
            $this->addError('code', t('Verification code has been expired'));
            return false;
        }

        $tempUser->is_verified = true;
        return $tempUser->save(false);
    }

    // </editor-fold>

    //<editor-fold desc="Step 3: Signup user" defaultstate="collapsed">


    /**
     * @throws \yii\base\Exception
     */
    public function resetPassword()
    {
        if (!$this->validate()) {
            return false;
        }

        $tempUser = TempUser::find()
            ->andWhere([
                'phone' => $this->getClearPhone(),
                'code' => $this->code,
                'is_verified' => true,
                'is_registered' => false,
            ])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        if (!$tempUser) {
            $this->addError('firstname', t('An error occurred'));
            return false;
        }

        $user = User::findOne(['username' => $this->getClearPhone()]);

        $transaction = Yii::$app->db->beginTransaction();

        $user->setPassword($this->password);
        $user->generateAuthKey();


        if (!$user->save(false)) {
            $transaction->rollBack();
            $this->addError('firstname', "Xatolik yuz berdi. " . $user->firstErrorMessage);
            return false;
        }

        $tempUser->is_registered = true;
        if (!$tempUser->save(false, ['is_registered'])) {
            $transaction->rollBack();
            $this->addError('firstname', "Xatolik yuz berdi. " . $tempUser->firstErrorMessage);
            return false;
        }

        $activeDevices = $user->activeDevices;

        // userning aktiv devicelarini nofaol qilish


        foreach ($activeDevices as $device) {
            if ($device->device_id != $this->device_id) {
                $device->status = UserDevice::STATUS_INACTIVE;
                $device->generateToken();

                if (!$device->save(false)) {
                    $transaction->rollBack();
                    $this->addError('firstname', "Xatolik yuz berdi. " . $device->firstErrorMessage);
                    return false;
                }
            }

        }

        $attrs = [
            'device_id' => $this->device_id,
            'user_id' => $user->id,
        ];

        $currentDevice = UserDevice::findOne($attrs);

        if (!$currentDevice) {
            $currentDevice = new UserDevice($attrs);
        }

        $currentDevice->status = UserDevice::STATUS_ACTIVE;
        $currentDevice->generateToken();
        $currentDevice->device_name = $this->device_name;
        $currentDevice->firebase_token = $this->device_token;

        if (!$currentDevice->save(false)) {
            $transaction->rollBack();
            $this->addError('firstname', "Xatolik yuz berdi. " . $currentDevice->firstErrorMessage);
            return false;
        }

        $transaction->commit();
        return [
            'user' => $user,
            'device' => $currentDevice
        ];

    }
}