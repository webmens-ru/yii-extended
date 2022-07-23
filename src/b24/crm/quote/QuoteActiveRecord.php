<?php

namespace wm\yii\b24\crm\quote;

use Yii;

class QuoteActiveRecord extends \wm\yii\b24\ActiveRecord
{
    /**
     * @inheritdoc
     */
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

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return Yii::createObject(QuoteActiveQuery::className(), [get_called_class()]);
    }
}
