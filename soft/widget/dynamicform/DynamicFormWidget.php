<?php

namespace soft\widget\dynamicform;

use kartik\form\ActiveForm;
use soft\db\ActiveRecord;
use soft\widget\kartik\Form;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\JuiAsset;
use yii\web\View;

/**
 *
 * @property-read ActiveRecord[] $childModels
 * @property-read ActiveRecord $firstChildModel
 * @property-read ActiveRecord $parentModel
 */
class DynamicFormWidget extends \wbraganca\dynamicform\DynamicFormWidget
{

    /**
     * @var ActiveForm
     */
    public $form;

    /**
     * @var DynamicFormModel
     */
    public $data;

    /**
     * @var array attributes to render active form
     * @see Form
     */
    public $columns = [];

    public $renderTable = true;

    public $tableOptions = ['class' => 'table table-condensed table-bordered'];

    public $tableResponsiveClass;

    public $renderTableHeader = true;

    public $widgetContainer = 'dynamicform_wrapper';
    public $widgetBody = '.form-options-body';
    public $widgetItem = '.form-options-item';
    public $min = 1;
    public $insertButton = '.add-item';
    public $deleteButton = '.delete-item';
    public $formId = 'dynamic-form';

    public $sortable;

    public $isBs4;

    public $renderAddButton = true;

    public function init()
    {
        if ($this->data == null || !($this->data instanceof DynamicFormModel)) {
            throw  new InvalidConfigException("`data` property must be instance of \soft\widget\dynamicform\DynamicFormModel");
        }

        if ($this->form == null || !($this->form instanceof ActiveForm)) {
            throw  new InvalidConfigException('`form` property must be instance of kartik\form\ActiveForm');
        }

        if ($this->model == null) {
            $this->model = $this->getFirstChildModel();
        }

        if ($this->sortable === null) {
            $this->sortable = $this->hasSortableAttribute();
        }

        if ($this->isBs4 === null) {
            $this->sortable = $this->hasSortableAttribute();
        }

        if (!isset($this->tableOptions['id'])) {

            $this->tableOptions['id'] = $this->getId() . "-table";

        }

        $this->normalizeColumns();

        parent::init();

        if ($this->renderTable) {

            $this->renderTable();
        }

    }

    /**
     * @return false|ActiveRecord
     */
    private function getFirstChildModel()
    {
        $array = $this->getChildModels();
        return reset($array);
    }

    /**
     * @return ActiveRecord[]
     */
    private function getChildModels()
    {
        return $this->data->models;
    }

    /**
     * @return bool
     */
    private function hasSortableAttribute()
    {
        return !empty($this->data->sortAttribute);
    }

    /**
     * @throws InvalidConfigException
     */
    public function normalizeColumns()
    {
        $result = [];

        foreach ($this->columns as $attributeName => $attributeConfigs) {

            if (is_array($attributeConfigs)) {
                $result[$attributeName] = $attributeConfigs;
            } else {
                if (!preg_match('/^([^:]+)(:(\w*))?(:(.*))?$/', $attributeConfigs, $matches)) {
                    throw new InvalidConfigException('The attribute given in column must be specified in the format of "attribute", "attribute:format" or "attribute:format:label"');
                }

                $result[$matches[1]] = [
                    'type' => isset($matches[3]) ? $matches[3] : null,
                    'label' => isset($matches[5]) ? $matches[5] : null,
                ];
            }

        }
        $this->columns = $result;
    }

    /**
     * Renders table
     */
    private function renderTable()
    {
        if ($this->tableResponsiveClass != null) {
            echo Html::beginTag('div', ['class' => $this->tableResponsiveClass]);
        }

        echo Html::beginTag('table', $this->tableOptions);

        if ($this->renderTableHeader) {
            $this->renderTableHeader();
        }

        $this->renderTableBody();
        $this->renderTableFooter();

        echo "</thead>";
        echo Html::endTag('table');

        if ($this->tableResponsiveClass != null) {
            echo Html::endTag('div');
        }
    }

    /**
     * Renders table header
     * @throws \Exception
     */
    private function renderTableHeader()
    {

        echo "<thead>";
        echo "<tr>";

        if ($this->sortable) {
            echo '<th style="width: 50px;"></th>'; // sortable column header
        }

        echo '<th style="width: 50px;">#</th>'; // serial column header

        foreach ($this->columns as $name => $column) {

            $label = ArrayHelper::getValue($column, 'label');
            if ($label == null) {
                $label = $this->model->getAttributeLabel($name);
            }

            $width = ArrayHelper::getValue($column, 'width');
            if ($width == null) {
                echo "<th>$label</th>";
            } else {
                echo "<th style='width: $width'>$label</th>";
            }
        }

        echo "<th></th>"; // action column header
        echo "</tr>";
        echo "</thead>";

    }

    private function renderTableBody()
    {
        $bodyClass = substr($this->widgetBody, 1);
        echo Html::beginTag('tbody', ['class' => $bodyClass]);

        $index = 0;
        foreach ($this->getChildModels() as $model) {

            $formOptionsItemClass = substr($this->widgetItem, 1);
            echo Html::beginTag('tr', ['class' => $formOptionsItemClass]);

            if ($this->sortable) {
                echo "<td class='sortable-handle' style='cursor: move;'>
                        <i class='fa fa-arrows-alt'></i>
                    </td>";
            }

            echo "<td class='index'>";
            echo Html::tag('span', $index + 1, ['class' => 'index-content']);
            if (!$model->isNewRecord) {
                echo Html::activeHiddenInput($model, "[{$index}]id");
            }
            echo "</td>";

            foreach ($this->columns as $name => $configs) {
                echo "<td>";
                $attribute = "[{$index}]{$name}";

                if (isset($configs['field'])) {

                    $field = $configs['field'];

                    if (is_callable($field)) {
                        echo call_user_func($field, $this->form, $model, $attribute);
                    } else {
                        echo $field;
                    }

                } else {

                    echo $this->form->field($model, "[{$index}]{$name}")->label(false);
                }

                echo "</td>";

            }
            echo "<td>";
            echo $this->removeButton();
            echo "</td>";

            echo Html::endTag('tr');
            $index++;
        }


        echo Html::endTag('tbody');
    }

    private function removeButton()
    {
        $deleteButtonClass = substr($this->deleteButton, 1);
        $options['class'] = "$deleteButtonClass btn btn-danger btn-sm";
        if (!$this->data->deleteOldModels) {
            $options['style'] = 'display:none';
        }
        $content = "<i class='fa fa-times'></i>";

        return Html::button($content, $options);
    }

    private function renderTableFooter()
    {
        echo Html::beginTag('tfoot');
        echo Html::beginTag('tr');
        echo Html::beginTag('td', ['colspan' => '100%']);

        echo $this->renderAddButton();
        echo " ";
        echo $this->renderSubmitButton();

        echo Html::endTag('tr');
        echo Html::endTag('td');
        echo Html::endTag('tfoot');

    }

    private function renderAddButton()
    {
        if ($this->renderAddButton) {
            $addItemClass = substr($this->insertButton, 1);
            $options['class'] = "btn btn-primary $addItemClass";
            $icon = "<i class='fa fa-plus'></i>";
            $content = $icon . " " . Yii::t('site', 'Create a new');
            return Html::button($content, $options);
        }

        return '';
    }

    private function renderSubmitButton()
    {
        return Html::submitButton(Yii::t('site', 'Save'), ['class' => 'btn btn-success']);
    }

    /**
     * @param View $view
     */
    public function registerAssets($view)
    {
        DynamicFormAsset::register($view);

        $hashVar = $this->getHashVarName();

        // add a click handler for the clone button
        $js = 'jQuery("#' . $this->formId . '").on("click", "' . $this->insertButton . '", function(e) {' . "\n";
        $js .= "    e.preventDefault();\n";
        $js .= '    jQuery(".' . $this->widgetContainer . '").triggerHandler("beforeInsert", [jQuery(this)]);' . "\n";
        $js .= '    jQuery(".' . $this->widgetContainer . '").yiiDynamicForm("addItem", ' . $hashVar . ", e, jQuery(this));\n";
        $js .= "});\n";
        $view->registerJs($js, $view::POS_READY);

        // add a click handler for the remove button
        $js = 'jQuery("#' . $this->formId . '").on("click", "' . $this->deleteButton . '", function(e) {' . "\n";
        $js .= "    e.preventDefault();\n";
        $js .= '    jQuery(".' . $this->widgetContainer . '").yiiDynamicForm("deleteItem", ' . $hashVar . ", e, jQuery(this));\n";
        $js .= "});\n";
        $view->registerJs($js, $view::POS_READY);

        $js = 'jQuery("#' . $this->formId . '").yiiDynamicForm(' . $hashVar . ');' . "\n";
        $view->registerJs($js, $view::POS_LOAD);

        JuiAsset::register($view);

        $js = '
            var fixHelperSortable = function(e, ui) {
            ui.children().each(function() {
                $(this).width($(this).width());
                });
                return ui;
            };
            
            $("' . $this->widgetBody . '").sortable({
                items: "tr",
                cursor: "move",
                opacity: 0.6,
                axis: "y",
                handle: ".sortable-handle",
                helper: fixHelperSortable,
                update: function(ev){
                    $(".' . $this->widgetContainer . '").yiiDynamicForm("updateContainer");
                    reOrder()       
                }
            }).disableSelection();
            
            $(".' . $this->widgetContainer . '").on("afterInsert", function(e, item) {
                 $("' . $this->widgetItem . '").last().find("' . $this->deleteButton . '").show()
                 $("' . $this->widgetItem . '").last().find("input[type=text]").val("")
                 $("' . $this->widgetItem . '").last().find("input[type=hidden]").val("")
                 $("' . $this->widgetItem . '").last().find("textarea").val("")
                reOrder()
             });
              
              $(".' . $this->widgetContainer . '").on("afterDelete", function(e, item) {
                reOrder()
             });
            
            function reOrder()
            {
                $(".index").each(function(index) {
                    $(this).find(".index-content").html(index + 1);
                });
            }
            
            ';

        $view->registerJs($js);

        $tableId = $this->tableOptions['id'];
        $css = '
            #' . $tableId . ' td, #' . $tableId . ' th{
                text-align: center;
                vertical-align: middle;
            }
        ';
        $view->registerCss($css);
    }

    /**
     * @return ActiveRecord
     */
    private function getParentModel()
    {
        return $this->data->model;
    }

    /**
     * @return bool
     */
    private function isBs4()
    {
        $v = ArrayHelper::getValue(Yii::$app->params, 'bsVersion', '3');
        $ver = (string)$v;
        return substr(trim($ver), 0, 1) == '4';
    }

}
