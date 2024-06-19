<?php

namespace backend\modules\usermanager\models;

use Yii;

class User extends \common\models\User
{

    public function assignRole(string  $roleName)
    {
        $auth = Yii::$app->authManager;
        $workerRole = $auth->getRole($roleName);
        if ($workerRole == null) {
            $workerRole = $auth->createRole($roleName);
            $auth->add($workerRole);
        }
        $auth->assign($workerRole, $this->id);
    }

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        $auth = Yii::$app->authManager;
        $permissions = ['admin', 'editor','director'];
        foreach ($permissions as $permissionName) {
            $role = $auth->getRole($permissionName);
            if ($role){
                $auth->revoke($role, $this->getId());
            }
        }
        return true;
    }


    public function getfullname()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

}