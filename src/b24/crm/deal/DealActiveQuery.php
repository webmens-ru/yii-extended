<?php

namespace wm\yii\b24\crm\deal;

use yii\helpers\ArrayHelper;

class DealActiveQuery extends \wm\yii\b24\ActiveQuery
{
    protected $listMethodName = 'crm.deal.list';

    protected $oneMethodName = 'crm.deal.get';

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
            'order' => $this->orderBy,
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
            'id' => $id
        ];

        if ($id === null && $this->where) {
            $this->queryMethod = 'all';
        } else {
            $this->errorParams = true;
        }

        $this->params = $data;
    }
}
