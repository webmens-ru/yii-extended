<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace wm\yii\db;

/**
 * Description of ActiveQuery
 *
 * @author injen
 */

use Yii;

class ActiveQuery extends \yii\db\ActiveQuery
{

    /**
     * Adds a filtering condition for a specific column and allow the user to choose a filter operator.
     *
     * It adds an additional WHERE condition for the given field and determines the comparison operator
     * based on the first few characters of the given value.
     * The condition is added in the same way as in [[andFilterWhere]] so [[isEmpty()|empty values]] are ignored.
     * The new condition and the existing one will be joined using the `AND` operator.
     *
     * The comparison operator is intelligently determined based on the first few characters in the given value.
     * In particular, it recognizes the following operators if they appear as the leading characters in the given value:
     *
     * - `<`: the column must be less than the given value.
     * - `>`: the column must be greater than the given value.
     * - `<=`: the column must be less than or equal to the given value.
     * - `>=`: the column must be greater than or equal to the given value.
     * - `<>`: the column must not be the same as the given value.
     * - `=`: the column must be equal to the given value.
     * - If none of the above operators is detected, the `$defaultOperator` will be used.
     *
     * @param string $name the column name.
     * @param string $value the column value optionally prepended with the comparison operator.
     * @param string $defaultOperator The operator to use, when no operator is given in `$value`.
     * Defaults to `=`, performing an exact match.
     * @return $this The query object itself
     * @since 2.0.8
     */
    public function andFilterCompare($name, $value, $defaultOperator = '=')
    {
        $arr = [];
        //убираем '[ и ']' в начале и в конце строки в запросе
        if ((substr($value, 0, 1) == '[') && (substr($value, -1, 1) == ']')) {
            $data = substr($value, 1, -1);
            $arr = explode(',', $data);
            foreach ($arr as $var) {
                $this->andFilterCompare($name, $var);
            }
            return $this;
        } else {
            if (preg_match('/^(<>|>=|>|<=|<|=)/', $value, $matches)) {
                $operator = $matches[1];
                $value = substr($value, strlen($operator));
            } elseif ($value == 'isNull') {
                return $this->andWhere([$name => null]);
            } elseif ($value == 'isNotNull') {
                return $this->andWhere('not', [$name => null]);
            } elseif (preg_match('/^(%%)/', $value, $matches)) {
                $operator = $matches[1];
                $value = substr($value, strlen($operator));
                $operator = 'like';
            } elseif (preg_match('/^(in\[.*])/', $value, $matches)) {
                $operator = 'in';
                $value = explode(',', mb_substr($value, 3, -1));
            } else {
                $operator = $defaultOperator;
            }
            return $this->andFilterWhere([$operator, $name, $value]);
        }
    }

}
