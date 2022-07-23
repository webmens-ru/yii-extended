<?php

namespace wm\yii\b24\crm\department;

use wm\yii\b24\ActiveRecord;
use Yii;

class DepartmentActiveRecord extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function fieldsMethod()
    {
        return 'department.fields';
    }

    public function fields()
    {
        return $this->attributes();
    }

    public static function getFooter($models)
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return Yii::createObject(DepartmentActiveQuery::className(), [get_called_class()]);
    }
}
