<?php

namespace soft\grid;

use yii\helpers\Html;

class DataColumn extends \kartik\grid\DataColumn
{

    public $vAlign = 'middle';

    /**
     * @return string[]
     */
    public static function booleanFilter(): array
    {
        return [
            1 => 'Ҳа',
            0 => 'Йўқ',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->normalizeFilter();
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    protected function renderFilterCellContent()
    {

        if (is_array($this->filter)) {
            Html::addCssClass($this->filterInputOptions, 'custom-select');
            if (!isset($this->filterInputOptions['prompt'])) {
                $this->filterInputOptions['prompt'] = '---';
            }
        }

        return parent::renderFilterCellContent();
    }

    /**
     * Normalize the filter input options.
     *
     * @return void
     */
    private function normalizeFilter()
    {

        if ($this->grid->filterModel == null){
            return;
        }

        if ($this->format == 'bool' && $this->filter === null) {
            $this->filter = self::booleanFilter();
        }

        if (is_array($this->filter)) {

            $attribute = $this->attribute;
            $value = $this->grid->filterModel->$attribute;

            if ($value === '') {
                $this->grid->filterModel->$attribute = null;
            }
        }
    }

}
