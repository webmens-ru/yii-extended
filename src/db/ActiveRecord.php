<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace wm\yii\db;

use Yii;
use wm\yii\db\ActiveQuery;

/**
 * Description of ActiveRecordExtended
 *
 * @author injen
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     * @return ActiveQuery the newly created [[ActiveQuery]] instance.
     */
    public static function find()
    {
        return Yii::createObject(ActiveQuery::class, [get_called_class()]);
    }

    public function formName()
    {
        return '';
    }

    protected function getDataProviderParams($query)
    {
        return [
            'query' => $query,
            'pagination' => [
                'pageSizeLimit' => [1, 10000],
                'defaultPageSize' => 10000,
            ]
        ];
    }

    public function getRestRules()
    {
        $res = [];
        $rules = $this->rules();
        foreach ($rules as $value) {
            $temp = [];
            $temp['fields'] = array_shift($value);
            $temp['type'] = array_shift($value);
            $temp['rules'] = $value;
            $res[] = $temp;
        }
        return $res;
    }

    public function getSchema()
    {
        return $this->convertShema($this->attributeLabels());
    }

    protected function convertShema($data)
    {
        $res = [];
        foreach ($data as $key => $value) {
            $temp = [];
            $temp['id'] = $key;
            $temp['title'] = $value;
            $res[] = $temp;
        }
        return $res;
    }

    public static function getFooter($models)
    {
        return [];
    }

    public static function getHeader($models)
    {
        return [];
    }
}
