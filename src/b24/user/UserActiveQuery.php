<?php

namespace wm\yii\b24\user;

//Код не универсален а направлен на смарт процессы стоит перенести в другой класс
use yii\helpers\ArrayHelper;

class UserActiveQuery extends \wm\yii\b24\ActiveQuery
{
//    public $entityTypeId;

    protected $listMethodName = 'user.get';

    protected $oneMethodName = 'user.get';

    protected $listDataSelector = 'result';

    protected $oneDataSelector = 'result.0';

    public function getEntityTypeIdUsedInFrom()
    {
//        if (empty($this->entityTypeId)) {
//            $this->entityTypeId = $this->modelClass::entityTypeId();
//        }

        return '';
    }

//    protected function getPrimaryTableName()
//    {
//        $modelClass = $this->modelClass;
//        //return $modelClass::tableName();
//        return $modelClass::entityTypeId();
//    }

    protected function prepairParams(){
//        $this->getEntityTypeIdUsedInFrom();
        $data = [
//            'entityTypeId' => $this->entityTypeId,
            'filter' => $this->where,
            'order' => $this->orderBy?$this->orderBy:null,
            'select' => $this->select,
            //Остальные параметры
        ];
        $this->params = $data;
    }

    protected function prepairOneParams(){
        $this->getEntityTypeIdUsedInFrom();
        $id = null;
        if(ArrayHelper::getValue($this->where, 'ID')){
            $id = ArrayHelper::getValue($this->where, 'ID');
        }
        if(ArrayHelper::getValue($this->link, 'ID')){
            $id = ArrayHelper::getValue($this->where, 'inArray.0');
        }
        $data = [
            'ID' => $id
        ];
        $this->params = $data;
    }
}
