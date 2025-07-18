<?php

/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace wm\yii\rest;

use wm\yii\db\ActiveRecord;
use Yii;
use yii\rest\Action;
use yii\web\NotFoundHttpException;

/**
 * ViewAction implements the API endpoint for returning the detailed information about a model.
 *
 * For more details and usage information on ViewAction, see the [guide article on rest controllers](guide:rest-controllers).
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ViewAction extends Action
{
    public $renderMode = 'form';
/**
     * Displays a model.
     * @param string $id the primary key of the model.
     * @return ActiveRecord|\wm\yii\b24\ActiveRecord the model being displayed
     * @throws NotFoundHttpException
     */
    public function run($id)
    {
        /** @var ActiveRecord|\wm\yii\b24\ActiveRecord $model */
        $model = $this->findModel($id);
        $model->renderMode = $this->renderMode;
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        return $model;
    }
}
