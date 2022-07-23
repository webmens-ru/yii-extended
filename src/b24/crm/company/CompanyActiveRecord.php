<?php

namespace wm\yii\b24\crm\company;

//use yii\base\Model;
use Bitrix24\B24Object;
use wm\b24tools\b24Tools;
use Yii;
use yii\helpers\ArrayHelper;

class CompanyActiveRecord extends \wm\yii\b24\ActiveRecord
{
    /**
     * @inheritDoc
     */
    public static function fieldsMethod()
    {
        return 'crm.company.fields';
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

    /**
     * @inheritDoc
     */
    public static function find()
    {
        return Yii::createObject(CompanyActiveQuery::className(), [get_called_class()]);
    }
}
