<?php


namespace soft\db;

use Yii;

/**
 *  MultilingualQueryTrait for ActiveQuery
 * @package soft\db
 * @var ActiveQuery $this
 */
trait MultilingualQueryTrait
{
    /**
     * @var string the name of the lang field of the translation table. Default to 'language'.
     */
    public $languageField = 'language';

    /**
     * Scope for querying by languages.
     *
     * @param bool $forceLocalized
     * @param $language
     * @return $this
     */
    public function localized($forceLocalized = true, $language = null)
    {
        if (!$language) {
            $language = Yii::$app->language;
        }

        if ($forceLocalized){
            $this->without('translations');
        }

        $this->with(['translation' => function ($query) use ($language) {
            $query->where([$this->languageField => $language]);
        }]);

        return $this;
    }

    /**
     * Scope for querying by all languages
     * @return $this
     */
    public function multilingual($indexBy = false)
    {

        $this->without('translation');
        $this->without('translations');

        if ($indexBy) {
            $this->with(['translations' => function ($query) {
                return $query->indexBy($this->languageField);
            }]);
        } else {
            $this->with(['translations']);
        }
        return $this;
    }

}
