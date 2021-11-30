<?php

namespace wm\yii\db;

use Yii;

class Query extends \yii\db\Query {

    public function andFilterCompare($name, $value, $defaultOperator = '=') {
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
