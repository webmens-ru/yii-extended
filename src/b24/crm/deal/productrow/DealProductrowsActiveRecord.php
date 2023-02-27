<?php

namespace wm\yii\b24\crm\deal\productrow;

use Yii;

class DealProductrowsActiveRecord extends \wm\yii\b24\ActiveRecord
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
