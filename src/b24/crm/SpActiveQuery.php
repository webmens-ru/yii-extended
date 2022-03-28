<?php

namespace wm\yii\b24\crm;

use yii\helpers\ArrayHelper;

class SpActiveQuery extends \wm\yii\b24\ActiveQuery
{
    public $entityTypeId;

    protected $listMethodName = 'crm.item.list';

    protected $oneMethodName = 'crm.item.get';

    protected $listDataSelector = 'result.items';

    protected $oneDataSelector = 'result.item';

    public function getEntityTypeIdUsedInFrom()
    {
        if (empty($this->entityTypeId)) {
            $this->entityTypeId = $this->modelClass::entityTypeId();
        }

        return $this->entityTypeId;
    }

    protected function prepairParams(){
        $this->getEntityTypeIdUsedInFrom();
        $data = [
            'entityTypeId' => $this->entityTypeId,
            'filter' => $this->where,
            'order' => $this->orderBy,
            'select' => $this->select,
            'start' => $this->offset,
        ];
        $this->params = $data;
    }

    protected function prepareFullParams($id){
        $this->getEntityTypeIdUsedInFrom();
        $this->params = [
            'entityTypeId' => $this->entityTypeId,
            'id' => $id
        ];
    }

    public $primaryKey = 'id';

    protected function prepairOneParams(){
        $this->getEntityTypeIdUsedInFrom();
        $id = null;
        if(ArrayHelper::getValue($this->where, 'id')){
            $id = ArrayHelper::getValue($this->where, 'id');
        }
        if($this->link){

        }
        $data = [
            'entityTypeId' => $this->entityTypeId,
            'id' => $id
        ];
        $this->params = $data;
    }
}
