<?php


namespace app\modules\wm\b24\crm\product;


use yii\helpers\ArrayHelper;


class ProductActiveQuery extends \app\modules\wm\b24\ActiveQuery
{
    protected $listMethodName = 'crm.product.list';

    protected $oneMethodName = 'crm.product.get';

//    protected function getPrimaryTableName()
//    {
//        $modelClass = $this->modelClass;
//        //return $modelClass::tableName();
//        return $modelClass::entityTypeId();
//    }

    /**
     * @inheritdoc
     */
    protected function prepairParams(){
        $data = [
            'order' => $this->orderBy,
            //Остальные параметры
        ];
        $data = $this->prepareSelectToData($data);

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

    /**
     * @inheritdoc
     */
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