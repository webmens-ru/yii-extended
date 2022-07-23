<?php

namespace app\modules\wm\b24\crm\deal\productrow;

use yii\helpers\ArrayHelper;

class DealProductrowsActiveQuery extends \app\modules\wm\b24\ActiveQuery
{
    protected $listMethodName = 'crm.deal.productrows.get';

    protected $oneMethodName = 'crm.deal.productrows.get';

    protected $listDataSelector = 'result';

    protected $oneDataSelector = 'result.0';

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
//            'entityTypeId' => $this->entityTypeId,
            'order' => $this->orderBy ? $this->orderBy : null,
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
//            'entityTypeId' => $this->entityTypeId,
            'id' => $id
        ];
        $this->params = $data;
    }
}
