<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "app_version".
 *
 * @property int $id
 * @property int|null $apk_version
 * @property string|null $apk_url
 * @property int|null $ios_version
 * @property string|null $ios_url
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class AppVersion extends \soft\db\ActiveRecord
{
    //<editor-fold desc="Parent" defaultstate="collapsed">

    /**
    * {@inheritdoc}
    */
    public static function tableName()
    {
        return 'app_version';
    }

    /**
    * {@inheritdoc}
    */
    public function rules()
    {
        return [
            [['apk_version', 'ios_version', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['apk_url', 'ios_url'], 'string', 'max' => 1024],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
    * {@inheritdoc}
    */
    public function behaviors()
    {
        return [
            'yii\behaviors\TimestampBehavior',
            'yii\behaviors\BlameableBehavior',
        ];
    }

    /**
    * {@inheritdoc}
    */
    public function labels()
    {
        return [
            'id' => 'ID',
            'apk_version' => 'Apk Version',
            'apk_url' => 'Apk Url',
            'ios_version' => 'Ios Version',
            'ios_url' => 'Ios Url',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    //</editor-fold>

    //<editor-fold desc="Relations" defaultstate="collapsed">
    
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
    
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
    
    //</editor-fold>
}
