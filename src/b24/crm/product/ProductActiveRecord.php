<?php


namespace app\modules\wm\b24\crm\product;


use Yii;


class ProductActiveRecord extends \app\modules\wm\b24\ActiveRecord
{
    public static function fieldsMethod()
    {
        return 'crm.product.fields';
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
        return Yii::createObject(ProductActiveQuery::className(), [get_called_class()]);
    }
}