<?php

namespace wm\yii\b24\crm\lead\userfield;

use Yii;

class LeadUserfieldActiveRecord extends \wm\yii\b24\ActiveRecord
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

    public static function getFooter($models)
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return Yii::createObject(LeadUserfieldActiveQuery::class, [get_called_class()]);
    }
}
