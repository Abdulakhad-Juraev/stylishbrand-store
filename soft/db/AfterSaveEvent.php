<?php

namespace soft\db;

/**
 * AfterSave represents the parameter needed by [[ActiveRecord]] events.
 *
 * @author Shukurullo Odilov
 */
class AfterSaveEvent extends \yii\db\AfterSaveEvent
{

    /**
     * @var bool whether the model is in valid status. Defaults to true.
     * A model is in valid status if it passes validations or certain checks.
     */
    public $isValid = true;

}
