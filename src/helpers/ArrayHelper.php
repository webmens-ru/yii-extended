<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace wm\yii\helpers;

use yii\helpers\BaseArrayHelper;

use yii\base\BaseObject;

/**
 * ArrayHelper provides additional array functionality that you can use in your
 * application.
 *
 * For more details and usage information on ArrayHelper, see the [guide article on array helpers](guide:helper-array).
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ArrayHelper extends BaseArrayHelper
{
    /**
     * @param array | BaseObject $old
     * @param array | BaseObject $new
     * @return array
     */
    public static function getDiff($old, $new)
    {
        $arrayOld = ArrayHelper::toArray($old);
        $arrayNew = ArrayHelper::toArray($new);

        $newKeys = [];
        $deleteKeys = [];
        $diff = [];

        foreach ($arrayOld as $key => $value) {
            if (array_key_exists($key, $arrayNew)) {
                if ($arrayOld[$key] != $arrayNew[$key]) {
                    $diff[$key] = ['old' => $arrayOld[$key], 'new' => $arrayNew[$key]];
                }
                unset($arrayNew[$key]);
            } else {
                $deleteKeys[$key] = $value;
            }
        }
        $newKeys = $arrayNew;

        return [
            'newKeys' => $newKeys,
            'deleteKeys' => $deleteKeys,
            'diff' => $diff,
        ];
    }

    /**
     * @param array $array
     * @param string $from
     * @return array
     * @throws \Exception
     */
    public static function groupBy($array, $from)
    {
        $result = [];
        foreach ($array as $value) {
            $result[self::getValue($value, $from)][] = $value;
        }
        return $result;
    }

    /**
     * @param array $data
     * @return array
     */
    public static function selectFormatter($data)
    {
        $result = [];
        foreach ($data as $key => $value) {
            $result[] = ['value' => $key, 'title' => $value];
        }
        return $result;
    }

    /**
     * Builds a map (key-value pairs) from a multidimensional array or an array of objects.
     * The `$from` and `$to` parameters specify the key names or property names to set up the map.
     *
     * For example,
     *
     * ```php
     * $array = [
     *     ['id' => '123', 'name' => 'aaa', 'lastName' => 'AAA'],
     *     ['id' => '124', 'name' => 'bbb', 'lastName' => 'BBB'],
     *     ['id' => '345', 'name' => 'ccc', 'lastName' => 'CCC'],
     * ];
     *
     * $result = ArrayHelper::map($array, 'id', ['name', 'lastName'], '-');
     * // the result is:
     * // [
     * //     '123' => 'aaa-AAA',
     * //     '124' => 'bbb-BBB',
     * //     '345' => 'ccc-CCC',
     * // ]
     * ```
     *
     * @param array $array
     * @param string|\Closure $from
     * @param string|array $to
     * @param string $selector
     * @return array
     */
    public static function mapImplode($array, $from, $to, $selector = ' ')
    {
        $result = [];
        foreach ($array as $element) {
            $key = static::getValue($element, $from);
            $value = null;
            if (is_array($to)) {
                $temp = [];
                foreach ($to as $val) {
                    $temp[] = static::getValue($element, $val);
                }
                $value = implode($selector, $temp);
            } else {
                $value = static::getValue($element, $to);
            }
            $result[$key] = $value;
        }

        return $result;
    }
}
