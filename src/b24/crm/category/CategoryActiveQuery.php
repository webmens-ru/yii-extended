<?php

namespace wm\yii\b24\crm\category;

//Код не универсален а направлен на смарт процессы стоит перенести в другой класс
use yii\helpers\ArrayHelper;
use wm\yii\b24\ActiveQuery;

class CategoryActiveQuery extends ActiveQuery
{
    public $entityTypeId;

    protected $listMethodName = 'crm.category.list';

    protected $oneMethodName = 'crm.category.get';

    protected $listDataSelector = 'result.categories';

    protected $oneDataSelector = 'result.category';

    public function getEntityTypeIdUsedInFrom()
    {
        if (empty($this->entityTypeId)) {
            $this->entityTypeId = $this->modelClass::entityTypeId();
        }

        return $this->entityTypeId;
    }

//    protected function getPrimaryTableName()
//    {
//        $modelClass = $this->modelClass;
//        //return $modelClass::tableName();
//        return $modelClass::entityTypeId();
//    }

    /**
     * @inheritDoc
     */
    protected function prepairParams()
    {
        $this->getEntityTypeIdUsedInFrom();
        $data = [
            'entityTypeId' => $this->entityTypeId,
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
        $this->getEntityTypeIdUsedInFrom();
        $this->params = [
            'entityTypeId' => $this->entityTypeId,
            'id' => $id
        ];
    }

    /**
     * @inheritDoc
     */
    protected function prepairOneParams()
    {
        $this->getEntityTypeIdUsedInFrom();
        $id = null;
        if (ArrayHelper::getValue($this->where, 'id')) {
            $id = ArrayHelper::getValue($this->where, 'id');
        }
        if (ArrayHelper::getValue($this->link, 'id')) {
            $id = ArrayHelper::getValue($this->where, 'inArray');
        }
        $data = [
            'entityTypeId' => $this->entityTypeId,
            'id' => $id
        ];
        $this->params = $data;
    }
}
