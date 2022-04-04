<?php


namespace wm\yii\b24\crm\lead;


use yii\helpers\ArrayHelper;


class LeadActiveQuery extends \wm\yii\b24\ActiveQuery
{
    protected $listMethodName = 'crm.lead.list';

    protected $oneMethodName = 'crm.lead.get';

//    protected function getPrimaryTableName()
//    {
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
        $this->params = [
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

        if($id === null && $this->where){
            $this->queryMethod = 'all';
        }else{
            $this->errorParams = true;
        }

        $this->params = $data;
    }
}