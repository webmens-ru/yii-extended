<?php
namespace wm\yii\helpers;

use yii\base\BaseObject;


/**
 * Данная функция предназначена для преобразования числового значения в его словесное представление,
 * учитывая правила грамматики русского языка.
 */
class TextHelper extends BaseObject
{

    /**
     * @param int $value
     * @param string[] $words
     * @return string
     */
    public static function num_word($value, $words)
    {
        $num = $value % 100;
        if ($num > 19) {
            $num = $num % 10;
        }
        $out = '';
        switch ($num) {
            case 1:
                $out .= $words[0];
                break;
            case 2:
            case 3:
            case 4:
                $out .= $words[1];
                break;
            default:
                $out .= $words[2];
                break;
        }

        return $out;
    }
}
