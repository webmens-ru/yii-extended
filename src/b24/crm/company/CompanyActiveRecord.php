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
     * @var array
     */
    public static $primaryKey = ['ID'];

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

    /**
     * @param mixed[]|null $condition
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
        if ($condition && $id = ArrayHelper::getValue($condition, 'ID')) {
            $component = new b24Tools();
            // $b24App = null;// $component->connectFromUser($auth);
//            if ($auth === null) {
            $b24App = $component->connectFromAdmin();
//            } else {
//                $b24App = $component->connectFromUser($auth);
//            }
            $obB24 = new B24Object($b24App);
            $data = $obB24->client->call('crm.company.delete', ['ID' => $id]);
            return $data;
        } else {
            return null;
        }
    }
}
