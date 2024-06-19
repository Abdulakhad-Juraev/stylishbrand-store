<?php

namespace common\traits;

use common\modules\user\models\UserDevice;

/**
 *
 * Trait UserDeviceTrait
 *
 * @property UserDevice[] $devices
 * @property UserDevice[] $activeDevices
 * @property int $allowedActiveDevicesCount
 * @property int $devicesCount
 * @property int $activeDevicesCount
 */
trait UserDeviceTrait
{

    private $_devicesCount;
    private $_activeDevicesCount;

    /**
     * @return \soft\db\ActiveQuery|\yii\db\ActiveQuery
     */
    public function getDevices()
    {
        return $this->hasMany(UserDevice::class, ['user_id' => 'id']);
    }

    /**
     * @return \soft\db\ActiveQuery
     */
    public function getActiveDevices()
    {
        return $this->getDevices()->active();
    }

    /**
     * @return int
     */
    public function getAllowedActiveDevicesCount(): int
    {
        if ($this->username == '998916700607') {
            return 10;
        }
        return 2; //todo
    }

    /**
     * @param $deviceId mixed
     * @return bool
     */
    public function hasDeviceById($deviceId)
    {
        return $this->getDevices()->andWhere(['device_id' => $deviceId])->exists();
    }

    /**
     * @return int
     */
    public function getDevicesCount()
    {
        if ($this->_devicesCount === null) {
            $this->_devicesCount = (int)$this->getDevices()->count();
        }
        return $this->_devicesCount;
    }

    /**
     * @param mixed $devicesCount
     */
    public function setDevicesCount($devicesCount): void
    {
        $this->_devicesCount = (int)$devicesCount;
    }

    /**
     * @return int
     */
    public function getActiveDevicesCount()
    {
        if ($this->_activeDevicesCount === null) {
            $this->_activeDevicesCount = (int)$this->getActiveDevices()->count();
        }
        return $this->_activeDevicesCount;
    }

    /**
     * @param mixed $activeDevicesCount
     */
    public function setActiveDevicesCount($activeDevicesCount): void
    {
        $this->_activeDevicesCount = (int)$activeDevicesCount;
    }

}
