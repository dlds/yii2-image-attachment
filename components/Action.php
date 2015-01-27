<?php

namespace dlds\imageable\components;

use Yii;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;
use yii\db\ActiveRecord;
use Imagine\Image\Box;
use yii\imagine\Image;

/**
 * Action to handle calls from ImageAttachmentWidget,
 * and apply changes to model with ImageAttachmentBehavior
 *
 * @example
 *
 *    public function actions()
 *    {
 *        return array(
 *            'saveImageAttachment' => 'ext.imageAttachment.ImageAttachmentAction',
 *        );
 *    }
 *
 * @author Bogdan Savluk <savluk.bogdan@gmail.com>
 *
 */
class Action extends \yii\base\Action {

    /**
     * @var array Mapping between types and model class names
     */
    public $types = [];

    public function run($type, $behavior, $id)
    {
        $remove = Yii::$app->request->post('remove', false);

        if (!isset($this->types[$type]))
        {
            throw new BadRequestHttpException('Specified model not found');
        }
        /** @var ActiveRecord $targetModel */
        $targetModel = call_user_func([$this->types[$type], 'findOne'], ['id' => $id]);

        /** @var ImageAttachmentBehavior $behavior */
        $behavior = $targetModel->getBehavior($behavior);

        if ($remove)
        {
            $behavior->removeImages();

            return Json::encode([]);
        }
        else
        {
            /** @var UploadedFile $imageFile */
            $imageFile = UploadedFile::getInstanceByName('image');
            $behavior->setImage($imageFile->tempName);

            return Json::encode(['previewUrl' => $behavior->getUrl('preview')]);
        }
    }

}
