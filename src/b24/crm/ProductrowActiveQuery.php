<?php


namespace app\modules\wm\b24\crm;


use yii\helpers\ArrayHelper;


class ProductrowActiveQuery extends \app\modules\wm\b24\ActiveQuery
{
    protected $listMethodName = 'crm.item.productrow.list';

    protected $oneMethodName = 'crm.item.productrow.get';

    protected $listDataSelector = 'result.productRows';

    protected $oneDataSelector = 'result.productRow';

//    protected function getPrimaryTableName()
//    {
////        Yii::warning($this->modelClass, '$this->modelClass');
//        $modelClass = $this->modelClass;
//        //return $modelClass::tableName();
//        return $modelClass::entityTypeId();
//    }

    protected function prepairParams(){
        //\Yii::warning($this->orderBy, '$this->orderBy');
        $data = [
            'filter' => $this->where,
            'order' => $this->orderBy,
            //Остальные параметры
        ];
        //Yii::warning($data, '$data');
        $this->params = $data;
    }

    protected function prepairOneParams(){
        //\Yii::warning($this->orderBy, '$this->orderBy');
        $id = null;
        if(ArrayHelper::getValue($this->where, 'id')){
            $id = ArrayHelper::getValue($this->where, 'id');
        }
        if(ArrayHelper::getValue($this->link, 'id')){
            $id = ArrayHelper::getValue($this->where, 'inArray.0');
        }
        $data = [
            'id' => $id
        ];
        $this->params = $data;
    }
}