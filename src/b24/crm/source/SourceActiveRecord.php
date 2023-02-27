<?php

namespace wm\yii\b24\crm\source;

//use yii\base\Model;
use Bitrix24\B24Object;
use Bitrix24\CRM\Status;
use phpDocumentor\Reflection\DocBlock\Tags\Source;
use wm\b24tools\b24Tools;
use Yii;
use yii\helpers\ArrayHelper;

class SourceActiveRecord extends Status
{
    public static function entityId()
    {
        return 'SOURCE';
    }
}
