<?php


namespace wm\yii\b24\crm\invoice;


use Yii;


class InvoiceActiveRecord extends \wm\yii\b24\ActiveRecord
{
    public static function entityTypeId()
    {
        return 31;
    }

    /**
     * @inheritdoc
     */
    public static function tableSchemaCaheKey()
    {
        return static::fieldsMethod()._.static::entityTypeId();
    }

    /**
     * @inheritdoc
     */
    public static function fieldsDataSelector()
    {
        return 'result.fields';
    }

    /**
     * @inheritdoc
     */
    public static function callAdditionalParameters()
    {
        return ['entityTypeId' => static::entityTypeId()];
    }

    /**
     * @inheritdoc
     */
    public static function fieldsMethod()
    {
        return 'crm.item.fields';
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
        return Yii::createObject(InvoiceActiveQuery::className(), [get_called_class()]);
    }
}