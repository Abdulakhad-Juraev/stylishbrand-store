<?php
/*
 * @author Shukurullo Odilov
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 06.07.2021, 18:27
 */

namespace soft\widget\dynamicform;


class DynamicFormAsset extends \wbraganca\dynamicform\DynamicFormAsset
{

    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('js', ['yii2-dynamic-form']);
    }

}