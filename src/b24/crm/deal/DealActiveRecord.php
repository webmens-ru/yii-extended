<?php


namespace wm\yii\b24\crm\deal;


use Yii;


class DealActiveRecord extends \wm\yii\b24\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function fieldsMethod()
    {
        return 'crm.deal.fields';
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
        return Yii::createObject(DealActiveQuery::className(), [get_called_class()]);
    }
}