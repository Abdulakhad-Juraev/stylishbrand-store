<?php
/**
 * User: uluGbek
 * @author Ulugbek Muhammadjonov
 * @email <muhammadjonovulugbek98@gmail.com>
 * Date: 31.07.2023  15:54
 */

namespace common\components\payme;

class PaymeData
{
    /**
     * Minimal summa. So'mda
     */
    const MIN_AMOUNT = 1000;

    /**
     * Maximal summa. So'mda
     */
    const MAX_AMOUNT = 100000000;


    public static $merchantId = '661cba55067bde847bea9bd3';
}