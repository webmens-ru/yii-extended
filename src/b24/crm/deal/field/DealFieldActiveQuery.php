<?php

namespace wm\yii\b24\crm\deal\field;

use yii\helpers\ArrayHelper;
use wm\yii\b24\ActiveQuery;

class DealFieldActiveQuery extends ActiveQuery
{
    protected $listMethodName = 'crm.deal.fields';

    protected $oneMethodName = 'crm.deal.fields';

    protected $listDataSelector = 'result';

    protected $oneDataSelector = 'result.0';

//    protected function getPrimaryTableName()
//    {
//        $modelClass = $this->modelClass;
//        //return $modelClass::tableName();
//        return $modelClass::entityTypeId();
//    }

    /**
     * @inheritdoc
     */
    protected function prepairParams()
    {
        $data = [
//            'entityTypeId' => $this->entityTypeId,
            'order' => $this->orderBy ? $this->orderBy : null,
            //Остальные параметры
        ];
        $data = $this->prepareSelectToData($data);

        if (ArrayHelper::getValue($this->where, 'inArray')) {
            $linkKey = ArrayHelper::getValue(array_keys($this->link), '0');
            if ($linkKey) {
                $data['filter'][$linkKey] = ArrayHelper::getValue($this->where, 'inArray');
            } else {
                $data['filter'] = $this->where;
            }
        } else {
            $data['filter'] = $this->where;
        }

        $this->params = $data;
    }

    protected function prepareFullParams($id)
    {
        $this->params = [
            'id' => $id
        ];
    }

    protected function createModels($rows)
    {
        if ($this->asArray) {
            return $rows;
        } else {
            $models = [];
            /* @var $class ActiveRecord */
            $class = $this->modelClass;
            foreach ($rows as $key => $row) {
                $row['systemName'] = $key;
                $model = $class::instantiate($row);
                //$model->load($row, '');
                $modelClass = get_class($model);
//                $modelClass::populateRecord($model, $row);//
                $models[] = $model;
            }
            return $models;
        }
    }

    /**
     * @inheritdoc
     */
    protected function prepairOneParams()
    {
        $id = null;
        if (ArrayHelper::getValue($this->where, 'id')) {
            $id = ArrayHelper::getValue($this->where, 'id');
        }
        if (ArrayHelper::getValue($this->link, 'id')) {
            $id = ArrayHelper::getValue($this->where, 'inArray');
        }
        $data = [
//            'entityTypeId' => $this->entityTypeId,
            'id' => $id
        ];
        $this->params = $data;
    }
}
