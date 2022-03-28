<?php


namespace app\modules\wm\b24\crm\deal\productrow;


use Yii;


class DealProductrowsActiveRecord extends \app\modules\wm\b24\ActiveRecord
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
//TODO getFooter($models) точно нужно? тут
    public static function getFooter($models)
    {
        return [];
    }

    public static function find()
    {
        return Yii::createObject(DealProductrowsActiveQuery::className(), [get_called_class()]);
    }
}