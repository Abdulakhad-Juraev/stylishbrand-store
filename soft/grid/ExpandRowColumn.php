<?php

namespace soft\grid;

class ExpandRowColumn extends \kartik\grid\ExpandRowColumn
{

    public $value;

    public function init()
    {
        if ($this->value === null) {
            $this->value = function ($model, $key, $index, $column) {
                return \kartik\grid\GridView::ROW_COLLAPSED;
            };
        }

        $this->normalizeIcons();

        parent::init();
    }

    private function normalizeIcons()
    {
        if($this->expandIcon === null) {
            $this->expandIcon = '<i class="far fa-plus-square"></i>';
        }
        if($this->collapseIcon === null) {
            $this->collapseIcon = '<i class="far fa-minus-square"></i>';
        }
    }

}
