<?php
namespace wm\yii\helpers;

use yii\base\BaseObject;


/**
 *
 */
class TimeHelper extends BaseObject
{

    /**
     * @param int $secs
     * @param string[] $hourStrings
     * @param string[] $minuteStrings
     * @return string
     */
    public static function secToStr(
        $secs,
        $hourStrings = ['час', 'часа', 'часов'],
        $minuteStrings = ['минута', 'минуты', 'минут']
    )
    {
        $res = '';
        $hours = floor($secs / 3600);
        $secs = $secs % 3600;
        $res .= $hours.' '.TextHelper::num_word($hours, $hourStrings) . ', ';

        $minutes = floor($secs / 60);
        $res .= $minutes.' '.TextHelper::num_word($minutes, $minuteStrings);

//        $res .= self::num_word($secs, array('секунда', 'секунды', 'секунд'));

        return $res;
    }

}
