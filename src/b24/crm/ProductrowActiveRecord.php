<?php


namespace app\modules\wm\b24\crm;


use Yii;


class ProductrowActiveRecord extends \app\modules\wm\b24\ActiveRecord
{
    public static function fieldsMethod()
    {
        return 'crm.item.productrow.fields';
    }

    public static function fieldsDataSelector()
    {
        return 'result.fields';
    }

    public function fields()
    {
        return $this->attributes();
    }

    public static function getFooter($models)
    {
        return [];
    }

    public static function find()
    {
        return Yii::createObject(ProductrowActiveQuery::className(), [get_called_class()]);
    }
}