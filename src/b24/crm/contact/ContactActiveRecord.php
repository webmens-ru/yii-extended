<?php

namespace wm\yii\b24\crm\contact;

//use yii\base\Model;
use Bitrix24\B24Object;
use wm\b24tools\b24Tools;
use Yii;
use yii\helpers\ArrayHelper;


class ContactActiveRecord extends \wm\yii\b24\ActiveRecord
{
    /**
     * @inheritDoc
     */
    public static function fieldsMethod()
    {
        return 'crm.contact.fields';
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
        return Yii::createObject(ContactActiveQuery::className(), [get_called_class()]);
    }
}
