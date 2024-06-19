<?php

use common\models\Section;

/**
 * @return string
 */
function section()
{
    return Yii::$app->params['section'];
}

/**
 * @return bool
 */
function isStoreSection()
{
    return section() == Section::STORE;
}

/**
 * @return bool
 */
function isSupplySection()
{
    return section() == Section::SUPPLY;
}

/**
 * @return bool
 */
function isAdminSection()
{
    return section() == Section::ADMIN;
}

/**
 * @return bool
 */
function isSaleSection()
{
    return section() == Section::SALE;
}

/**
 * @return bool
 */
function isFinanceSection()
{
    return section() == Section::FINANCE;
}

/**
 * @return bool
 */
function isFactorySection()
{
    return section() == Section::FACTORY;
}

/**
 * @param string|array $section
 * @return bool
 */
function isSection($section)
{
    $sections = (array)$section;
    $current_section = section();

    foreach ($sections as $sectionName) {
        if ($current_section == $sectionName) {
            return true;
        }
    }
    return false;
}


