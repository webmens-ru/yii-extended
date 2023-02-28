<?php

namespace wm\yii\b24\crm\deal\field;

use wm\yii\b24\TableSchema;
use Yii;

class DealFieldActiveRecord extends \wm\yii\b24\ActiveRecord
{
    public static function getTableSchema()
    {
            $schemaData =[
                'isDynamic' => [
                    'title' => 'Динамическое',
                    'type' => 'string'
                ],
                'isImmutable' => [
                    'title' => 'Не изменяемое',
                    'type' => 'string'
                ],
                'isMultiple' => [
                    'title' => 'Множественное',
                    'type' => 'string'
                ],
                'isReadOnly' => [
                    'title' => 'Только для чтения',
                    'type' => 'string'
                ],
                'isRequired' => [
                    'title' => 'Обязательное',
                    'type' => 'string'
                ],
                'title' => [
                    'title' => 'Название',
                    'type' => 'string'
                ],
                'type' => [
                    'title' => 'Тип',
                    'type' => 'string'
                ],
                'filterLabel'=> [
                    'title' => 'Тип',
                    'type' => 'string'
                ],
                'formLabel'=> [
                    'title' => 'Тип',
                    'type' => 'string'
                ],
                'listLabel'=> [
                    'title' => 'Тип',
                    'type' => 'string'
                ],
                'systemName'=> [
                    'title' => 'Тип',
                    'type' => 'string'
                ],
            ];
            return new TableSchema($schemaData, ['primaryKey' => static::$primaryKey]);
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
        return Yii::createObject(DealFieldActiveQuery::class, [get_called_class()]);
    }
}
