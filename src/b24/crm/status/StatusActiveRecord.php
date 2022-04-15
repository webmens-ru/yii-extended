<?php

namespace wm\yii\b24\crm\status;

//use yii\base\Model;
use Bitrix24\B24Object;
use wm\b24tools\b24Tools;
use Yii;
use yii\helpers\ArrayHelper;


class StatusActiveRecord extends \wm\yii\b24\ActiveRecord
{
//    public static function entityTypeId()
//    {
//        return null;
//    }

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
        return Yii::createObject(StatusActiveQuery::className(), [get_called_class()]);
    }
}
