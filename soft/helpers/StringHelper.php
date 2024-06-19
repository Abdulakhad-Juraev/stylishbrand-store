<?php
/*
 * @author Shukurullo Odilov <shukurullo0321@gmail.com>
 * @link telegram: https://t.me/yii2_dasturchi
 * @date 22.03.2022, 16:03
 */

namespace soft\helpers;

class StringHelper extends \yii\helpers\StringHelper
{

    const DUAL_LETTERS = ["o'", "g'", "sh", "ch", "ng"];

    /**
     * Satrdan birinchi harfni aniqlaydi
     * Bu asosan o'zbek lotin yozuvidagi ikkitalik harflar uchun ishlatiladi
     * @param $string
     * @return false|string
     * @see DUAL_LETTERS
     */
    public static function firstLetter($string)
    {
        $firstTwoLetters = mb_strtolower(mb_substr($string, 0, 2));
        return in_array($firstTwoLetters, self::DUAL_LETTERS) ? mb_substr($string, 0, 2) : mb_substr($string, 0, 1);
    }

    /**
     * Replace the corresponding placeholders in the message.
     * @param string $string the string to be replaced.
     * @param array $params the parameters that will be used to replace the corresponding placeholders in the message.
     * @return string Replaced string
     */
    public static function replaceString(string $string, $params = [])
    {
        $placeholders = [];
        foreach ((array) $params as $name => $value) {
            $placeholders['{' . $name . '}'] = $value;
        }

        return ($placeholders === []) ? $string : strtr($string, $placeholders);
    }

    /**
     * Raqamni so'z bn ifodalash
     * @param $number int|mixed
     * @return false|string
     */
    public static function numberToWord($number)
    {

        $num = str_replace(array(',', ''), '', trim($number));

        if (!$num) {
            return false;
        }

        $num = (int)$num;
        $words = array();
        $list1 = array('', 'бир', 'икки', 'уч', 'тўрт', 'беш', 'олти', 'йетти', 'саккиз', 'тўққиз', 'ўн', 'ўн бир',
            'ўн икки', 'ўн уч', 'ўн тўрт', 'ўн беш', 'ўн олти', 'ўн йетти', 'ўн саккиз', 'ўн тўққиз'
        );
        $list2 = array('', 'ўн', 'йигирма', 'ўттиз', 'қириқ', 'еллик', 'отмиш', 'йетмиш', 'саксон', 'тўқсон', 'юз');
        $list3 = array('', 'минг', 'миллион', 'миллиард', 'триллион', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        );

        $num_length = strlen($num);
        $levels = (int)(($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);

        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int)($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . 'юзи' . ($hundreds == 1 ? '' : '') . ' ' : '');
            $tens = (int)($num_levels[$i] % 100);
            $singles = '';
            if ($tens < 20) {
                $tens = ($tens ? '  ' . $list1[$tens] . ' ' : '');
            } elseif ($tens >= 20) {
                $tens = (int)($tens / 10);
                $tens = '  ' . $list2[$tens] . ' ';
                $singles = (int)($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . (($levels && ( int )($num_levels[$i])) ? ' ' . $list3[$levels] . ' ' : '');
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }
        $words = implode(' ', $words);
        $words = preg_replace('/^\s\b(" ")/', '', $words);
        $words = trim($words);
        $words = ucfirst($words);
        return $words . ".";
    }
}
