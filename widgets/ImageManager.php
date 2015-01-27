<?php

namespace dlds\imageable\widgets;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use dlds\imageable\bundles\CoreAsset;

/**
 * Widget to provide interface for image upload to models with
 * ImageAttachmentBehavior.
 * @example
 *
 *   $this->widget('ext.imageAttachment.ImageAttachmentWidget', array(
 *       'model' => $model,
 *       'behaviorName' => 'previewImageAttachmentBehavior',
 *       'apiRoute' => 'api/saveImageAttachment',
 *   ));
 *
 * @author Bogdan Savluk <savluk.bogdan@gmail.com>
 */
class ImageManager extends \yii\base\Widget {

    /**
     * Route to ImageAttachmentAction
     * @var string
     */
    public $apiRoute;
    public $assets;

    /**
     * Behaviour name in model to use
     * @var string
     */
    public $behaviorName;

    /**
     * Model with behaviour
     * @var ActiveRecord
     */
    public $model;

    /**
     * @return ImageAttachmentBehavior
     */
    public function getAttachmentBehavior()
    {
        return $this->model->getBehavior($this->behaviorName);
    }

    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['imageAttachment/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@dlds/imageable/messages',
            'fileMap' => [
            ],
        ];
    }

    public function run()
    {
        if ($this->apiRoute === null)
        {
            throw new Exception('$apiRoute must be set.', 500);
        }

        if (!is_array($this->apiRoute))
        {
            $this->apiRoute = [$this->apiRoute];
        }


        $attachmentBehavior = $this->getAttachmentBehavior();
        $options = [
            'hasImage' => $attachmentBehavior->hasImage(),
            'previewUrl' => $attachmentBehavior->getUrl('preview'),
            'previewWidth' => $attachmentBehavior->previewWidth,
            'previewHeight' => $attachmentBehavior->previewHeight,
            'apiUrl' => Url::to(ArrayHelper::merge($this->apiRoute, [
                        'type' => $attachmentBehavior->type,
                        'behavior' => $this->behaviorName,
                        'id' => $attachmentBehavior->owner->getPrimaryKey(),
                    ])
            ),
        ];

        $optionsJS = Json::encode($options);

        $view = $this->getView();
        CoreAsset::register($view);
        $view->registerJs("$('#{$this->id}').imageAttachment({$optionsJS});");

        return $this->render('@dlds/imageable/views/imageManager');
    }

}
