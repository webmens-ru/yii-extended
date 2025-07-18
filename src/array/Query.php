<?php

namespace wm\yii\array;

use yii\base\Component;
use yii\db\QueryInterface;
use yii\helpers\ArrayHelper;
use yii\db\Expression;

/**
 *
 */
class Query extends Component implements QueryInterface
{
    /**
     * @var mixed[]
     */
    public $where = [];
    /**
     * @var string|null
     */
    public $indexBy;
    /**
     * @var mixed[]|null
     */
    public $orderBy;
    /**
     * @var int|null
     */
    public $offset;
    /**
     * @var int|null
     */
    public $limit;

    /**
     * @var mixed[]
     */
    public $data;
    /**
     * @var mixed[]|null
     */
    public $resultData;

    /**
     * @param mixed[] $data
     * @param mixed[] $config
     */
    public function __construct($data, $config = [])
    {
        $this->data = $data;
        parent::__construct($config);
    }

    // region QueryInterface methods

    /**
     * @param mixed[] $condition
     * @param mixed[] $params
     * @return $this|Query
     */
    public function andWhere($condition, $params = [])
    {
        if (!empty($this->where)) {
            $this->where = ['and', $this->where, $condition];
        } else {
            $this->where = $condition;
        }
        return $this;
    }

    /**
     * @param mixed[] $condition
     * @return $this|Query
     */
    public function orWhere($condition)
    {
        if (!empty($this->where)) {
            $this->where = ['or', $this->where, $condition];
        } else {
            $this->where = $condition;
        }
        return $this;
    }

    /**
     * @param mixed[] $condition
     * @return $this|Query
     */
    public function where($condition)
    {
        $this->where = $condition;
        return $this;
    }

    /**
     * @param mixed[] $condition
     * @return $this|Query
     */
    public function filterWhere(array $condition)
    {
        $condition = $this->filterCondition($condition);
        if ($condition !== []) {
            $this->where = $condition;
        }
        return $this;
    }

    /**
     * @param mixed[] $condition
     * @return $this|Query
     */
    public function andFilterWhere(array $condition)
    {
        $condition = $this->filterCondition($condition);
        if ($condition !== []) {
            $this->andWhere($condition);
        }
        return $this;
    }

    /**
     * @param mixed[] $condition
     * @return $this|Query
     */
    public function orFilterWhere(array $condition)
    {
        $condition = $this->filterCondition($condition);
        if ($condition !== []) {
            $this->orWhere($condition);
        }
        return $this;
    }

    /**
     * @param mixed[] $columns
     * @return $this|Query
     */
    public function orderBy($columns)
    {
        $this->orderBy = $columns;
        return $this;
    }

    /**
     * @param mixed[] $columns
     * @return $this|Query
     */
    public function addOrderBy($columns)
    {
        if ($this->orderBy === null) {
            $this->orderBy = $columns;
        } else {
            $this->orderBy = array_merge((array)$this->orderBy, (array)$columns);
        }
        return $this;
    }

    /**
     * @param $limit
     * @return $this|Query
     */
    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param $offset
     * @return $this|Query
     */
    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @param $value
     * @return $this|Query
     */
    public function emulateExecution($value = true)
    {
        return $this;
    }

    /**
     * @param $db
     * @return bool
     */
    public function exists($db = null)
    {
        return (bool)$this->one();
    }

    /**
     * @param mixed $q
     * @param mixed|null $db
     * @return int|string|null
     */
    public function count($q = '*', $db = null)
    {
        $this->execute();
        return is_array($this->resultData) ? count($this->resultData) : 0;
    }

    /**
     * @param mixed|null $db
     * @return mixed[]|null
     */
    public function all($db = null)
    {
        $this->execute();
        return $this->resultData;
    }

    /**
     * @param mixed|null $db
     * @return array|bool|mixed|null
     */
    public function one($db = null)
    {
        $this->execute();
        return $this->resultData ? reset($this->resultData) : null;
    }

    /**
     * @param string $column
     * @return $this|Query
     */
    public function indexBy($column)
    {
        $this->indexBy = $column;
        return $this;
    }

    /**
     * @param mixed|null $db
     * @return mixed[]
     */
    public function column($db = null)
    {
        $this->execute();
        if ($this->resultData === null) {
            return [];
        }

        if ($this->indexBy === null) {
            return array_values($this->resultData);
        }

        return ArrayHelper::getColumn($this->resultData, $this->indexBy);
    }

    // endregion

    // region Filters

//    public function andFilterWhereCompare($name, $value, $defaultOperator = '=')
//    {
//        if ($this->isEmpty($value)) {
//            return $this;
//        }
//
//        if (is_array($value) && !empty($value)) {
//            foreach ($value as $val) {
//                $this->andFilterWhereCompare($name, $val, $defaultOperator);
//            }
//            return $this;
//        }
//
//        if (is_string($value) && substr($value, 0, 1) == '[' && substr($value, -1, 1) == ']') {
//            $value = substr($value, 1, -1);
//            $values = explode(',', $value);
//            foreach ($values as $val) {
//                $this->andFilterWhereCompare($name, $val, $defaultOperator);
//            }
//            return $this;
//        }
//
//        if (is_string($value) && preg_match('/^(<>|>=|>|<=|<|=)/', $value, $matches)) {
//            $operator = $matches[1];
//            $value = substr($value, strlen($operator));
//        } elseif ($value === 'isNull') {
//            return $this->andWhere([$name => null]);
//        } elseif ($value === 'isNotNull') {
//            return $this->andWhere(['not', [$name => null]]);
//        } elseif (is_string($value) && preg_match('/^(like)/i', $value, $matches)) {
//            $operator = 'like';
//            $value = substr($value, strlen($matches[0]));
//        } elseif (is_string($value) && preg_match('/^(in\[.*])/', $value, $matches)) {
//            $operator = 'in';
//            $value = explode(',', mb_substr($value, 3, -1));
//        } else {
//            $operator = $defaultOperator;
//        }
//
//        return $this->andFilterWhere([$operator, $name, $value]);
//    }

    /**
     * @param string $name
     * @param mixed $value
     * @param string $defaultOperator
     * @return $this|Query
     */
    public function andFilterCompare($name, $value, $defaultOperator = '=')
    {
        switch ($defaultOperator) {
            case '<>':
                return $this->andFilterWhere(['!=', $name, $value]);
            case 'in':
                return $this->andFilterWhere(['IN', $name, $value]);
            case 'like':
            case '%like':
            case 'like%':
            case '%like%':
                return $this->andFilterWhere(['LIKE', $name, $value]);
            case 'isNull':
                return $this->andWhere([$name => null]);
            case 'isNotNull':
                return $this->andWhere(['!=', $name, null]);
            default:
                return $this->andFilterWhere([$defaultOperator, $name, $value]);
        }
    }

    // endregion

    /**
     * @return void
     */
    protected function execute()
    {
        $this->resultData = $this->data;

        // WHERE
        if (!empty($this->where)) {
            $this->resultData = array_filter($this->resultData, function ($item) {
                return $this->checkCondition($item, $this->where);
            });
            $this->resultData = array_values($this->resultData);
        }

        // INDEX BY
        if ($this->indexBy !== null) {
            $this->resultData = ArrayHelper::index($this->resultData, $this->indexBy);
        }

        // ORDER BY
        if ($this->orderBy !== null) {
            $this->resultData = $this->applyOrderBy($this->resultData, $this->orderBy);
        }

        // LIMIT / OFFSET
        if ($this->limit !== null || $this->offset !== null) {
            $this->resultData = array_slice(
                $this->resultData,
                (int)($this->offset ?? 0),
                $this->limit !== null ? (int)$this->limit : null,
                is_string(key($this->resultData)) // сохранить ключи, если они строковые
            );
        }
    }

    /**
     * @param mixed $item
     * @param mixed[] $condition
     * @return bool
     * @throws \Exception
     */
    private function checkCondition($item, $condition): bool
    {
        if (is_array($condition)) {
            // Обработка простых условий вида ['field' => 'value']
            if (!isset($condition[0])) {
                foreach ($condition as $attribute => $value) {
                    $itemValue = ArrayHelper::getValue($item, $attribute);
                    if ($itemValue != $value) {
                        return false;
                    }
                }
                return true;
            }

            $operator = strtoupper($condition[0]);

            switch ($operator) {
                case 'AND':
                    for ($i = 1; $i < count($condition); $i++) {
                        if (!$this->checkCondition($item, $condition[$i])) {
                            return false;
                        }
                    }
                    return true;

                case 'OR':
                    for ($i = 1; $i < count($condition); $i++) {
                        if ($this->checkCondition($item, $condition[$i])) {
                            return true;
                        }
                    }
                    return false;

                case 'NOT':
                    return !$this->checkCondition($item, $condition[1]);

//                case 'IN':
//                    $attribute = $condition[1];
//                    $values = $condition[2];
//                    $value = ArrayHelper::getValue($item, $attribute);
//                    return in_array($value, $values, true);

                case 'IN':
                    $attribute = $condition[1];
                    $values = $condition[2];
                    $value = ArrayHelper::getValue($item, $attribute);

                    // Приводим оба значения к строкам для сравнения
                    $value = is_scalar($value) ? (string)$value : $value;
                    $values = array_map(function($v) {
                        return is_scalar($v) ? (string)$v : $v;
                    }, $values);

                    return in_array($value, $values, true);

                case 'NOT IN':
                    $attribute = $condition[1];
                    $values = $condition[2];
                    $value = ArrayHelper::getValue($item, $attribute);
                    return !in_array($value, $values, true);

                case 'LIKE':
                    $attribute = $condition[1];
                    $value = $condition[2];
                    $itemValue = (string)ArrayHelper::getValue($item, $attribute);
                    return stripos($itemValue, (string)$value) !== false;

                case 'BETWEEN':
                    $attribute = $condition[1];
                    $min = $condition[2];
                    $max = $condition[3];
                    $itemValue = ArrayHelper::getValue($item, $attribute);
                    return $itemValue >= $min && $itemValue <= $max;

                case 'NOT BETWEEN':
                    $attribute = $condition[1];
                    $min = $condition[2];
                    $max = $condition[3];
                    $itemValue = ArrayHelper::getValue($item, $attribute);
                    return $itemValue < $min || $itemValue > $max;

                default:
                    // Обработка операторов сравнения: >, <, =, >=, <=, !=
                    if (count($condition) === 3) {
                        $attribute = $condition[1];
                        $operator = $condition[0];
                        $value = $condition[2];

                        $itemValue = ArrayHelper::getValue($item, $attribute);
                        switch ($operator) {
                            case '>':
                                return $itemValue > $value;
                            case '<':
                                return $itemValue < $value;
                            case '=':
                                return $itemValue == $value;
                            case '>=':
                                return $itemValue >= $value;
                            case '<=':
                                return $itemValue <= $value;
                            case '!=':
                            case '<>':
                                return $itemValue != $value;
                        }
                    }
            }
        }

        return (bool)$condition;
    }

    /**
     * @param mixed[] $data
     * @param string[] $columns
     * @return mixed[]
     */
    private function applyOrderBy(array $data, $columns): array
    {
        $columns = (array) $columns;

        // Разделяем атрибуты и направления сортировки
        $attributes = [];
        $directions = [];

        foreach ($columns as $attribute => $direction) {
            $attributes[] = $attribute;
            $directions[] = $direction == SORT_DESC ? SORT_DESC : SORT_ASC;
        }

        // Сортируем с помощью ArrayHelper::multisort()
        ArrayHelper::multisort($data, $attributes, $directions);

        return $data;
    }

    /**
     * @param mixed[] $condition
     * @return mixed[]
     */
    protected function filterCondition($condition)
    {
        if (!is_array($condition)) {
            return $condition;
        }

        if (!isset($condition[0])) {
            foreach ($condition as $name => $value) {
                if ($this->isEmpty($value)) {
                    unset($condition[$name]);
                }
            }
            return $condition;
        }

        $operator = array_shift($condition);
        $filtered = [];

        switch (strtoupper($operator)) {
            case 'NOT':
            case 'AND':
            case 'OR':
                foreach ($condition as $operand) {
                    $sub = $this->filterCondition($operand);
                    if (!$this->isEmpty($sub)) {
                        $filtered[] = $sub;
                    }
                }
                if (empty($filtered)) {
                    return [];
                }
                break;
            case 'BETWEEN':
            case 'NOT BETWEEN':
                if (
                    isset(
                        $condition[1],
                        $condition[2]
                    ) && $this->isEmpty($condition[1]) && $this->isEmpty($condition[2])
                ) {
                    return [];
                }
                $filtered = $condition;
                break;
            default:
                if (isset($condition[0]) && $this->isEmpty($condition[0])) {
                    return [];
                }
                $filtered = $condition;
        }

        array_unshift($filtered, $operator);
        return $filtered;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    protected function isEmpty($value): bool
    {
        return $value === '' || $value === null || $value === [];
    }
}
