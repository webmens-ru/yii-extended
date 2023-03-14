<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace wm\yii\rest;

use yii\base\Arrayable;
use yii\base\Component;
use yii\base\Model;
use yii\data\DataProviderInterface;
use wm\yii\data\B24DataProvider;
use yii\data\Pagination;
use yii\web\Link;

/**
 * Serializer converts resource objects and collections into array representation.
 *
 * Serializer is mainly used by REST controllers to convert different objects into array representation
 * so that they can be further turned into different formats, such as JSON, XML, by response formatters.
 *
 * The default implementation handles resources as [[Model]] objects and collections as objects
 * implementing [[DataProviderInterface]]. You may override [[serialize()]] to handle more types.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Serializer extends \yii\rest\Serializer
{
    /**
     * @var string the name of the envelope (e.g. `_links`) for returning the links objects.
     * It takes effect only, if `collectionEnvelope` is set.
     * @since 2.0.4
     */
    public $linksEnvelope = 'links';
    /**
     * @var string the name of the envelope (e.g. `_meta`) for returning the pagination object.
     * It takes effect only, if `collectionEnvelope` is set.
     * @since 2.0.4
     */
    public $metaEnvelope = 'meta';
    /**
     * Serializes the given data into a format that can be easily turned into other formats.
     * This method mainly converts the objects of recognized types into array representation.
     * It will not do conversion for unknown object types or non-object data.
     * The default implementation will handle [[Model]], [[DataProviderInterface]] and [\JsonSerializable](https://www.php.net/manual/en/class.jsonserializable.php).
     * You may override this method to support more object types.
     * @param mixed $data the data to be serialized.
     * @return mixed the converted data.
     */
    public function serialize($data)
    {
        if ($data instanceof Model && $data->hasErrors()) {
            return $this->serializeModelErrors($data);
        } elseif ($data instanceof Arrayable) {
            return $this->serializeModel($data);
        } elseif ($data instanceof \JsonSerializable) {
            return $data->jsonSerialize();
        } elseif ($data instanceof B24DataProvider) {
            return $this->serializeB24DataProvider($data);
        } elseif ($data instanceof DataProviderInterface) {
            return $this->serializeDataProvider($data);
        } elseif (is_array($data)) {
            $serializedArray = [];
            foreach ($data as $key => $value) {
                $serializedArray[$key] = $this->serialize($value);
            }
            return $serializedArray;
        }

        return $data;
    }



//    /**
//     * Serializes a data provider.
//     * @param DataProviderInterface $dataProvider
//     * @return array the array representation of the data provider.
//     */
    protected function serializeB24DataProvider($dataProvider)
    {
        $modelClass = $dataProvider->query->modelClass;
        if ($this->preserveKeys) {
            $models = $dataProvider->getModels();
        } else {
            $models = array_values($dataProvider->getModels());
        }
        $models = $this->serializeModels($models);

        if (($pagination = $dataProvider->getPagination()) !== false) {
            $this->addPaginationHeaders($pagination);
        }

//        if ($this->request->getIsHead()) {
//            return null;
//        } elseif ($this->collectionEnvelope === null) {
//            return $models;
//        }

        $result = [
            'header' => $modelClass::getHeader($models),
            'grid' => $modelClass::getGridData($models),
            'footer' => $modelClass::getFooter($models),
            'options' => (is_callable([$modelClass, 'getGridOptions'])) ? $modelClass::getGridOptions() : []
        ];
        if ($pagination !== false) {
            $result['pagination']  = $this->serializePagination($pagination);
        }

        return $result;
    }
}
