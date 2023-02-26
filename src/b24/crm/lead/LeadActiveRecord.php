<?php

namespace wm\yii\b24\crm\lead;

use Bitrix24\B24Object;
use wm\b24tools\b24Tools;
use wm\yii\helpers\ArrayHelper;
use Yii;

class LeadActiveRecord extends \wm\yii\b24\ActiveRecord
{
    /**
     * @var array
     */
    public static $primaryKey = ['ID'];

    /**
     * @inheritdoc
     */
    public static function fieldsMethod()
    {
        return 'crm.lead.fields';
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
        return Yii::createObject(LeadActiveQuery::className(), [get_called_class()]);
    }

    /**
     * @param null $condition
     * @param array $params
     * @return array|int|null
     * @throws \Bitrix24\Exceptions\Bitrix24ApiException
     * @throws \Bitrix24\Exceptions\Bitrix24EmptyResponseException
     * @throws \Bitrix24\Exceptions\Bitrix24Exception
     * @throws \Bitrix24\Exceptions\Bitrix24IoException
     * @throws \Bitrix24\Exceptions\Bitrix24MethodNotFoundException
     * @throws \Bitrix24\Exceptions\Bitrix24PaymentRequiredException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalDeletedException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalRenamedException
     * @throws \Bitrix24\Exceptions\Bitrix24SecurityException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsExpiredException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsInvalidException
     * @throws \Bitrix24\Exceptions\Bitrix24WrongClientException
     * @throws \yii\db\Exception
     */
    public static function deleteAll($condition = null, $params = []) //TODO узнать что нужно возвращать
    {
        if($id = ArrayHelper::getValue($condition, 'ID')){
            $component = new b24Tools();
            // $b24App = null;// $component->connectFromUser($auth);
//            if ($auth === null) {
            $b24App = $component->connectFromAdmin();
//            } else {
//                $b24App = $component->connectFromUser($auth);
//            }
            $obB24 = new B24Object($b24App);
            $data = $obB24->client->call('crm.lead.delete', ['ID' => $id]);
            return $data;
        }else{
            return null;
        }
    }
}
