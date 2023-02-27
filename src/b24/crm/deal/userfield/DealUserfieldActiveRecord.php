<?php

namespace wm\yii\b24\crm\deal\userfield;

use Yii;

class DealUserfieldActiveRecord extends \wm\yii\b24\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function fieldsMethod()
    {
        return 'crm.userfield.fields';
    }

    /**
     * @inheritdoc
     */
    public static function fieldsDataSelector()
    {
        return 'result';
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
     * @inheritdoc
     */
    public static function find()
    {
        return Yii::createObject(DealUserfieldActiveQuery::className(), [get_called_class()]);
    }
}
