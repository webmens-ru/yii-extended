<?php


namespace  wm\yii\b24\crm\department;


use wm\yii\b24\ActiveQuery;
use yii\helpers\ArrayHelper;


class DepartmentActiveQuery extends ActiveQuery
{
    protected $listMethodName = 'department.get';

    protected $oneMethodName = 'department.get';

    protected $oneDataSelector = 'result.0';

//    protected function getPrimaryTableName()
//    {
////        Yii::warning($this->modelClass, '$this->modelClass');
//        $modelClass = $this->modelClass;
//        //return $modelClass::tableName();
//        return $modelClass::entityTypeId();
//    }

    protected function prepairParams(){
        $data = [
            'order' => $this->orderBy,
            'select' => $this->select,
            //Остальные параметры
        ];

        if(ArrayHelper::getValue($this->where, 'inArray')){
            $linkKey = ArrayHelper::getValue(array_keys($this->link), '0');
            if($linkKey){
                $data['filter'][$linkKey] = ArrayHelper::getValue($this->where, 'inArray');
            }else{
                $data['filter'] = $this->where;
            }
        }else{
            $data['filter'] = $this->where;
        }

        $this->params = $data;
    }

    protected function prepareFullParams($id){
        $this->getEntityTypeIdUsedInFrom();
        $this->params = [
            'entityTypeId' => $this->entityTypeId,
            'id' => $id
        ];
    }

    protected function prepairOneParams(){
        $id = null;
        if(ArrayHelper::getValue($this->where, 'id')){
            $id = ArrayHelper::getValue($this->where, 'id');
        }
        if(ArrayHelper::getValue($this->link, 'id')){
            $id = ArrayHelper::getValue($this->where, 'inArray');
        }
        $data = [
            'id' => $id
        ];
        $this->params = $data;
    }
}