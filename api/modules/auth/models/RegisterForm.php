<?php

namespace api\modules\auth\models;

use api\models\User;
use common\modules\tempUser\models\TempUser;
use common\modules\user\models\UserDevice;
use mohorev\file\UploadImageBehavior;
use soft\base\SoftModel;
use soft\helpers\FileHelper;
use soft\helpers\PhoneHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;

class RegisterForm extends SoftModel
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
    const SCENARIO_REGISTER = 'register';

    const PHONE_PATTERN = '/[9][9][8]\d{9}/';

    public $username;

    public $firstname;

    public $lastname;

    public $password;

    public $password_repeat;

    public $phone;

    public $code;

    public $img;

    public $device_id;

    public $device_name;

    public $device_token;

    public $signature;

    /**
     * @var bool
     */
    private $_user = false;

    /**
     * @var UserDevice
     */
    public $device;

    //<editor-fold desc="Overritten methods" defaultstate="collapsed">

    /**
     * @return array
     */
    public function rules()
    {
        return [

            [['firstname', 'lastname'], 'string', 'min' => 2, 'max' => 255],
            [['firstname'], 'required', 'message' => t('Firstname is required.')],

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
//            [['device_id', 'device_name'], 'required'],
//            [['device_id'], 'required'],
            [['img'], 'image'],

        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'phone' => t('Your phone number'),
            'username' => t('Login'),
            'firstname' => t('Your firstname'),
            'lastname' => t('Your lastname'),
            'password' => t('Password'),
            'password_repeat' => t('Enter the password again'),
            'code' => t('Code'),
            'device_id' => t('Device ID'),
            'device_name' => t('Device name'),
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
        $scenarios[self::SCENARIO_REGISTER] = ['firstname', 'lastname', 'password', 'password_repeat', 'code', 'phone'];
        return $scenarios;
    }
    // </editor-fold>

    //<editor-fold desc="Custom methods" defaultstate="collapsed">

    /**
     * Telefon raqamni qo'shimcha belgilardan tozalash,
     * masalan: +998(99) 123-45-67 => 998991234567
     * @return string cleared phone number
     */
    public function getClearPhone()
    {
        return PhoneHelper::fullPhoneNumber($this->phone);
    }

    // </editor-fold>

    //<editor-fold desc="Step 1: Enter and check phone number, send code via sms" defaultstate="collapsed">

    /**
     * Kiritilgan tel. raqam bazada bor yoki yo'qligini tekshirish
     * Agar mavjud bo'lsa, false qaytaradi
     */
    public function checkPhone()
    {
        $phone = $this->getClearPhone();


        if (!empty($phone)) {

            if (!preg_match(static::PHONE_PATTERN, $phone)) {
                $this->addError('phone', t('Incorrect phone number'));
                return false;
            }
            $user = User::findOne(['username' => $phone]);
            if ($user == null) {
                return true;
            } else {
                $this->addError('phone', 'Ushbu telefon raqam avvalroq band qilingan');
            }
            return true;
        }
        return false;
    }


    /**
     * @return int
     */
    public function generateCode()
    {
        if ($this->getClearPhone() == '998123456789') {
            return 7777;
        }
        if ($this->getClearPhone() == '998112223344') {
            return 7777;
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
                return true;
            }

        }

        $tempUser = new TempUser([
            'code' => (string)$code,
            'phone' => $this->clearPhone,
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

        $domain = 'dosmetov.uz';

        $message = Yii::t('app', 'signup_verify_sms_message', [
                'domain' => $domain,
                'code' => $code
            ]) . '. ' . $this->signature;

        if ($this->getClearPhone() == '998123456789') {
            return true;
        }
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
        return $tempUser->update(false);
    }

    // </editor-fold>


    /**
     * @return false|mixed
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function register()
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
//            TelegramService::log('Register form::register() - TempUser not found' . PHP_EOL . "Phone: {$this->getClearPhone()}" . PHP_EOL . "Code: {$this->code}");
            return false;
        }

        $user = new User([
            'scenario' => User::SCENARIO_REGISTER,
        ]);


        $user->username = $this->getClearPhone();
        $user->setPassword($this->password);
        $user->firstname = strip_tags($this->firstname);
        $user->lastname = strip_tags($this->lastname);
        $user->status = User::STATUS_ACTIVE;
        $user->generateAuthKey();
//        $user->image = $this->registerUploadImg();

        $transaction = Yii::$app->db->beginTransaction();

        if (!$user->save()) {
            $transaction->rollBack();

            $this->addError('firstname', $user->getFirstErrorMessage());
            return false;
        }

        $tempUser->is_registered = true;
        if (!$tempUser->update(false)) {
            $transaction->rollBack();
            $this->addError('firstname', $tempUser->getFirstErrorMessage());
            return false;
        }

//        $userDevice = new UserDevice();
//        $userDevice->user_id = $user->id;
//        $userDevice->device_id = $this->device_id;
//        $userDevice->device_name = $this->device_name;
//        $userDevice->firebase_token = $this->device_token;
//        $userDevice->status = UserDevice::STATUS_ACTIVE;
//        $userDevice->generateToken();
//        if (!$userDevice->save()) {
//            $transaction->rollBack();
//            $this->addError('firstname', $userDevice->getFirstErrorMessage());
//            return false;
//        }

        $transaction->commit();

        return [
            'user' => $user,
//            'device' => $userDevice
        ];

    }

    /**
     * @return string
     */
    public function registerUploadImg()
    {

        $this->img = UploadedFile::getInstanceByName('img');

        if (!is_dir(Yii::getAlias('@frontend/web/uploads/user'))) {
            mkdir(Yii::getAlias('@frontend/web/uploads/user'), 0775, true);
        }

        if ($this->img) {

            if ($this->img->size / (1024 * 1024) > 3) {
                return $this->addError('img', 'The photo was larger than 3 MB. Please upload a smaller photo.');
            }

            if (in_array($this->img->extension, User::$extensions)) {
                $this->img->saveAs('@frontend/web/uploads/user/' . 'user' . time() . '.' . $this->img->extension);
                $this->img = 'user' . time() . '.' . $this->img->extension;
                return $this->img;
            } else {
                $this->addError('img', "Invalid picture");
                return  false;
            }
        }

    }
}