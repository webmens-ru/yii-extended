<?php


namespace app\modules\wm\b24\crm\productrow;


use Yii;


class ProductrowActiveRecord extends \wm\yii\b24\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function fieldsMethod()
    {
        return 'crm.item.productrow.fields';
    }

    /**
     * @inheritdoc
     */
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

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return Yii::createObject(ProductrowActiveQuery::className(), [get_called_class()]);
    }
}