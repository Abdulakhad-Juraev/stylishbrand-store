<?php


namespace soft\widget\kartik;

use kartik\builder\FormGrid as KartikFormGrid;
use soft\widget\adminlte3\Card;
use Yii;

/**
 *
 * @property-read string $gridOutput
 */
class FormGrid extends KartikFormGrid
{

    public $initCard;

    public $autoGenerateColumns = false;

    public function init()
    {
        parent::init();

        if ($this->initCard === null) {
            $this->initCard = !Yii::$app->request->isAjax;
        }

        if ($this->initCard) {
            Card::begin();
        }

    }

    public function run()
    {
        parent::run();
        if ($this->initCard) {
            Card::end();
        }
    }

    protected function getGridOutput()
    {
        $output = '';
        foreach ($this->rows as $row) {
            $defaults = [
                'model' => $this->model,
                'form' => $this->form,
                'formName' => $this->formName,
                'columns' => $this->columns,
                'attributeDefaults' => $this->attributeDefaults,
                'autoGenerateColumns' => $this->autoGenerateColumns,
                'columnSize' => $this->columnSize,
                'columnOptions' => $this->columnOptions,
                'rowOptions' => $this->rowOptions,
                'options' => $this->fieldSetOptions,
                'initCard' => false,
            ];
            $config = array_replace_recursive($defaults, $row);
            $output .= Form::widget($config) . "\n";
        }
        return $output;
    }
}
