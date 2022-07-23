<?php

namespace wm\yii\b24\crm\source;

//Код не универсален а направлен на смарт процессы стоит перенести в другой класс
use yii\helpers\ArrayHelper;
use wm\yii\b24\ActiveQuery;

class SourceActiveQuery extends ActiveQuery
{
    public $entityId;

    protected $listMethodName = 'crm.status.list';

    protected $oneMethodName = 'crm.status.get';

    public function getEntityIdUsedInFrom()
    {
        if (empty($this->entityId)) {
            $this->entityId = $this->modelClass::entityId();
        }

        return $this->entityId;
    }

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
        ];
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
        $this->getEntityTypeIdUsedInFrom();
        $this->params = [
            'entityTypeId' => $this->entityTypeId,
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
