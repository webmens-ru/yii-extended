<?php

namespace wm\yii\b24\crm\smartprocess;

//use yii\base\Model;
use Bitrix24\B24Object;
use wm\b24tools\b24Tools;
use Yii;
use yii\helpers\ArrayHelper;

class SmartProcessActiveRecord extends \wm\yii\b24\ActiveRecord
{
    public static function entityTypeId()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public static function fieldsMethod()
    {
        return 'crm.item.fields';
    }

    /**
     * @inheritdoc
     */
    public static function tableSchemaCaheKey()
    {
        return static::fieldsMethod() . _ . static::entityTypeId();
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
        return Yii::createObject(SmartProcessActiveQuery::className(), [get_called_class()]);
    }

    public static function deleteAll($condition = null, $params = []) //TODO узнать что нужно возвращать
    {
        if($id = ArrayHelper::getValue($condition, 'id')){
            $component = new b24Tools();
            $b24App = null;// $component->connectFromUser($auth);
//            if ($auth === null) {
                $b24App = $component->connectFromAdmin();
//            } else {
//                $b24App = $component->connectFromUser($auth);
//            }
            $obB24 = new B24Object($b24App);
            $data = $obB24->client->call('crm.item.delete', ['entityTypeId' => static::entityTypeId(), 'id' => $id]);
        }else{
           return null; 
        }
    }

//    public static function listDataSelector()
//    {
//        return 'result.items';
//    }
}
