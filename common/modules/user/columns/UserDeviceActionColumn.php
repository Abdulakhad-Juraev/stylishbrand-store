<?php
/*
 *  @author Shukurullo Odilov <shukurullo0321@gmail.com>
 *  @link telegram: https://t.me/yii2_dasturchi
 *  @date 23.05.2022, 13:45
 */

namespace common\modules\user\columns;

use common\modules\user\models\UserDevice;
use soft\grid\MyActionColumn;
use soft\widget\button\ConfirmButton;

class UserDeviceActionColumn extends MyActionColumn
{

    public $controller = 'user-device';

    public $viewOptions = ['role' => 'modal-remote'];

    public $updateOptions = ['role' => 'modal-remote'];

    public $template = " {view} {update} {delete}";

    public function buttons(): array
    {
        return [
            're-generate-token' => function ($url, UserDevice $model) {

                return ConfirmButton::widget([
                    'icon' => 'sync-alt',
                    'url' => $url,
                    'title' => 'Tokenni qayta generatsiya qilish',
                    'confirmMessage' => 'Ushbu qurilma uchun tokenni qayta generatsiya qilishni istaysizmi?',
                ]);


            }
        ];
    }


}
