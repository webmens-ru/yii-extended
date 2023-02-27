<?php

namespace wm\yii\b24\crm\category;

//use yii\base\Model;
use Bitrix24\B24Object;
use wm\b24tools\b24Tools;
use Yii;
use yii\helpers\ArrayHelper;

class CategoryActiveRecord extends \wm\yii\b24\ActiveRecord
{
    public static function entityTypeId()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public static function fieldsMethod()
    {
        return 'crm.category.fields';
    }

    /**
     * @inheritDoc
     */
    public static function tableSchemaCaheKey()
    {
        return static::fieldsMethod() . '_' . static::entityTypeId();
    }

    /**
     * @inheritDoc
     */
    public static function fieldsDataSelector()
    {
        return 'result.fields';
    }

    /**
     * @inheritDoc
     */
    public static function callAdditionalParameters()
    {
        return ['entityTypeId' => static::entityTypeId()];
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
     * @inheritDoc
     */
    public static function find()
    {
        return Yii::createObject(CategoryActiveQuery::className(), [get_called_class()]);
    }
}
