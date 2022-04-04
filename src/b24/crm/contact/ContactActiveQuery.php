<?php


namespace wm\yii\b24\crm\contact;

use yii\helpers\ArrayHelper;

class ContactActiveQuery extends \wm\yii\b24\ActiveQuery
{
    //    public $entityTypeId;

    protected $listMethodName = 'crm.contact.list';

    protected $oneMethodName = 'crm.contact.get';

    public $primaryKey = 'ID';

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
            'order' => $this->orderBy?$this->orderBy:null,
            //Остальные параметры
        ];
        $data = prepareSelectToData($data);

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
        $this->getEntityTypeIdUsedInFrom();
        $id = null;
        if(ArrayHelper::getValue($this->where, 'ID')){
            $id = ArrayHelper::getValue($this->where, 'ID');
        }
        if(ArrayHelper::getValue($this->link, 'ID')){
            $id = ArrayHelper::getValue($this->where, 'inArray');
        }
        $data = [
            'ID' => $id
        ];
        if($id === null && $this->where){
            $this->queryMethod = 'all';
        }else{
            $this->errorParams = true;
        }
        $this->params = $data;
    }
}