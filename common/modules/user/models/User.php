<?php
/*
 * @author Shukurullo Odilov
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 06.07.2021, 15:07
 */

namespace common\modules\user\models;

use mohorev\file\UploadImageBehavior;
use soft\helpers\ArrayHelper;
use Yii;

class User extends \common\models\User
{
    const TYPE_IS_SYSTEM_USER = 1;

    /**
     * Assigns roles to user. If role does not exist it will be created.
     * @param string $roleName
     * @throws \Exception
     */
    public function assignRole(string $roleName)
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($roleName);
        if ($role == null) {
            $role = $auth->createRole($roleName);
            $auth->add($role);
        }
        $auth->assign($role, $this->id);
    }

    /**
     * {@inheritdoc}
     * Deletes all assigned roles before deleting the user.
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        $auth = Yii::$app->authManager;
        $roles = $auth->getRolesByUser($this->id);
        foreach ($roles as $role) {
            $auth->revoke($role, $this->getId());
        }
        return true;
    }

    /**
     * Assigns roles to this user
     * @param string[] $roleNames List of role names to be assigned
     * @throws \Exception
     */
    public function assignNewRoles($roleNames)
    {
        foreach ($roleNames as $roleName) {
            if ($roleNames != 'admin') {
                $this->assignRole($roleName);
            }
        }
    }

    /**
     * Updates existing roles of the user
     * @param string[] $roleNames List of role names to be updated
     * @throws \Exception
     */
    public function updateRoles($roleNames)
    {
        $auth = Yii::$app->authManager;
        $oldRoles = $auth->getRolesByUser($this->id);
        $oldRoleNames = array_keys($oldRoles);

        $diffRoles = array_values(array_diff($oldRoleNames, $roleNames));
        $newRoles = array_values(array_diff($roleNames, $oldRoleNames));

        if (!empty($diffRoles)) {
            $this->revokeUserRoles($diffRoles);
        }
        if (!empty($newRoles)) {
            $this->assignNewRoles($newRoles);
        }
    }

    /**
     * Revokes roles to this user
     * @param string[] $roleNames List of role names to be revoked
     * @throws \Exception
     */
    public function revokeUserRoles($roleNames)
    {
        foreach ($roleNames as $roleName) {
            if ($roleName != 'admin') {
                $this->revokeRole($roleName);
            }
        }
    }
}
