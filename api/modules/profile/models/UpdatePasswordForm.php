<?php

namespace api\modules\profile\models;

use common\models\User;
use soft\helpers\ArrayHelper;
use Yii;
use yii\base\Model;

/**
 * UpdatePasswordForm model
 *
 * @property string $old_password
 * @property string $new_password
 * @property string $repeat_password
 * @property-read null|string $firstErrorMessage
 * @property-read User $user
 */
class UpdatePasswordForm extends Model
{

    /**
     * @var
     */
    public $old_password;
    /**
     * @var
     */
    public $new_password;

    /**
     * @var
     */
    public $repeat_password;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['old_password', 'new_password', 'repeat_password'], 'required'],
            [['old_password', 'new_password', 'repeat_password'], 'string', 'min' => 5],
            ['old_password', 'findPasswords'],
            ['repeat_password', 'compare', 'compareAttribute' => 'new_password', 'message' => t('The re-entered password does not match')],
        ];
    }

    //matching the old password with your existing password.

    /**
     * @param $attribute
     * @param $params
     * @return bool
     */
    public function findPasswords($attribute, $params)
    {
        $user = User::findOne(Yii::$app->user->id);
        if (!$user->validatePassword($this->old_password)) {
            $this->addError($attribute, "Eski parol noto'g'ri");
        }
        return true;
    }

    /**
     * @return bool
     */
    public function changePassword()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        $user = User::findOne(Yii::$app->user->id);
        if ($this->validate()) {
            $user->password = $user->setPassword($this->new_password);
            $user->save(false);
        } else {
            return false;
        }
        return true;
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

}