<?php

namespace wm\yii\b24\crm\type;

use yii\helpers\ArrayHelper;

class TypeActiveQuery extends \wm\yii\b24\ActiveQuery
{
    public $entityTypeId;

    protected $listMethodName = 'crm.type.list';

    protected $oneMethodName = 'crm.type.get';

    protected $listDataSelector = 'result.types';

    protected $oneDataSelector = 'result.type';

    /**
     * @inheritdoc
     */
    protected function prepairParams()
    {
        $data = [
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
            'id' => $id
        ];

        if ($id === null && $this->where) {
            $this->queryMethod = 'all';
        } else {
            $this->errorParams = true;
        }

        $this->params = $data;
    }

    public $primaryKey = 'id';
}
