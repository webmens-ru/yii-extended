<?php


namespace wm\yii\b24\crm;


use Yii;


class QuoteActiveRecord extends \wm\yii\b24\ActiveRecord
{
    public static function fieldsMethod()
    {
        return 'crm.quote.fields';
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
        return Yii::createObject(QuoteActiveQuery::className(), [get_called_class()]);
    }
}