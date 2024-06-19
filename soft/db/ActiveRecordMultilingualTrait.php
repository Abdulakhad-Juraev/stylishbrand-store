<?php

namespace soft\db;

use odilov\multilingual\behaviors\MultilingualBehavior;
use Yii;
use yii\base\InvalidArgumentException;

/**
 * @property \odilov\multilingual\behaviors\MultilingualBehavior|null $multilingualBehavior
 * @property-read  bool $hasMultilingualBehavior
 * @property-read  bool $hasMultilingualAttributes
 * @property-read  array $multilingualAttributes
 */
trait ActiveRecordMultilingualTrait
{

    //<editor-fold desc="Multilingual behavior" defaultstate="collapsed">

    /**
     * Languages list for multilingual behavior. For more details refer
     *  [[yeesoft/multilingual]] extension
     * @return array
     */
    public function languages(): array
    {
        return Yii::$app->params['languages'];
    }

    /**
     * @return MultilingualBehavior|null
     */
    public function getMultilingualBehavior()
    {
        return $this->getBehavior('multilingual');
    }

    /**
     * @return bool
     */
    public function getHasMultilingualBehavior(): bool
    {
        return $this->getMultilingualBehavior() != null;
    }

    /**
     * @return array
     */
    public function getMultilingualAttributes(): array
    {
        return $this->getHasMultilingualBehavior() ? $this->getMultilingualBehavior()->attributes : [];
    }

    /**
     * @return bool
     */
    public function getHasMultilingualAttributes(): bool
    {
        return !empty($this->getMultilingualAttributes());
    }

    /**
     * Check if $attribute is multilingual attribute
     * @param string $attribute
     * @return bool
     */
    public function isMultilingualAttribute(string $attribute): bool
    {
        return in_array($attribute, $this->multilingualAttributes);
    }

    /**
     * Check if $name is attribute
     * @param $name string
     * @return bool
     */
    public function isAttribute(string $name): bool
    {
        if ($name) {
            return parent::hasAttribute($name) || $this->isMultilingualAttribute($name);
        } else return false;
    }

    /**
     * Generates multilingual attributes with language prefix by given attribute.
     * For instance, if $attribute value is 'name', result would be ['name_uz', 'name_en', ...]
     * @param string $attribute multilingual attribute
     * @return array|false multilingual attributes with language prefix
     * @throws \Exception
     */

    public function generateMultilingualAttributes(string $attribute)
    {
        if (!$this->isMultilingualAttribute($attribute)) {
            throw new InvalidArgumentException("Attribute '" . $attribute . "' is not multilingual attribute");
        }
        $result = [];
        foreach ($this->languages() as $key => $value) {
            $result[] = $attribute . "_" . $key;
        }
        return $result;
    }

    //</editor-fold>

}
