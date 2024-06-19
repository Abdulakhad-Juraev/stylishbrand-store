<?php
/*
 * @author Shukurullo Odilov
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 11.01.2022, 14:24
 */

namespace soft\grid;

use common\models\User;

class UserIdColumn extends Select2Column
{

    public $attribute = 'user_id';

    public $value = 'user.fullname';

    public function init()
    {

        if ($this->data === null){
            $this->data = User::map();
        }

        parent::init();
    }

}
