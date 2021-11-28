<?php

namespace common\helpers;

use common\models\Image;
use Exception;
use Imagine\Image\Box;
use Yii;
use yii\helpers\FileHelper;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

class ImageHelper
{
    /**
     * Try to delete the image files related to this Image.
     *
     * @param Image $image The image model to remove files for
     * @param string $path Path to the folder that contains the images, for example
     * Yii::getAlias("@userImgPath") for images. Without trailing slash.
     * @return bool wether deletion was successful
     */
    public static function removeRelatedFiles(Image $image, string $path): bool
    {
        $success = true;
        $resizedImagePath = $path . '/' . $image->file_name;
        $fullSizeImagePath = $path . '/full/' . $image->file_name;
        // Delete the resized image
        if (file_exists($resizedImagePath)) {
            if (!unlink($resizedImagePath)) {
                $success = false;
                Yii::warning("Could not unlink file $resizedImagePath", __METHOD__);
            }
        } else {
            Yii::warning("Trying to delete non-existent file $resizedImagePath", __METHOD__);
            Yii::warning(Yii::$app->getRequest()->hostName);
        }
        // Delete the full size image
        if (file_exists($fullSizeImagePath)) {
            if (!unlink($fullSizeImagePath)) {
                $success = false;
                Yii::warning("Could not unlink file $fullSizeImagePath", __METHOD__);
            }
        } else {
            Yii::warning("Trying to delete non-existent file $fullSizeImagePath", __METHOD__);
        }
        return $success;
    }

    /**
     * Given an upload file that corresponds to an image file,
     * save the file, and its thumbnail, at a given path, and
     * create an Image model to point to them.
     *
     * @param UploadedFile $uploadedFile
     * @param string $path
     * @return bool|Image The Image model created or false if it could not be created
     * @throws ServerErrorHttpException
     */
    public static function createNewImage(
        UploadedFile $uploadedFile,
        string       $path,
        string       $class = Image::class): bool|Image
    {
        // Make sure file has a valid extension
        $ext = $uploadedFile->getExtension();
        if (!in_array($ext, Yii::$app->params['validImageExtensions'])) {
            throw new ServerErrorHttpException(
                "File extension $ext in not valid. Should be one of " .
                implode(', ', Yii::$app->params['validImageExtensions'])
            );
        }
        // If the image directory does not exist, create it
        $directory = $path . DIRECTORY_SEPARATOR . 'full';
        try {
            if (!file_exists($directory)) {
                FileHelper::createDirectory($directory);
            }
            do {
                $imageName = StringHelper::random_str(20) . '.' . $uploadedFile->extension;
                $imagePath = $directory . DIRECTORY_SEPARATOR . $imageName;
            } while (file_exists($imagePath));
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw new ServerErrorHttpException('Error saving file ');
        }
        if ($imagePath === null || $imageName === null) {
            throw new ServerErrorHttpException(
                'Error creating a path to save image'
            );
        }
        if (!$uploadedFile->saveAs($imagePath)) {
            Yii::error([
                "Problem saving image $imagePath",
                'File size is: ' . $uploadedFile->size
            ], __METHOD__);
            return false;
        }
        if (YII_DEBUG) {
            Yii::debug("Saved image: $imagePath", __METHOD__);
        }
        // Create thumbnail
        $thumbnailPath = $path . DIRECTORY_SEPARATOR . $imageName;
        $width = $height = 500;
        \yii\imagine\Image::getImagine()->open($imagePath)
            ->thumbnail(new Box($width, $height))->save($thumbnailPath, ['quality' => 90]);
        // We managed to save the file create a new Image model
        $image = new $class();
        $image->file_name = $imageName;
        if (!$image->save()) {
            Yii::error(['Error saving image model', $image->getErrors()], __METHOD__);
            throw new ServerErrorHttpException('Error saving image model');
        }
        return $image;
    }
}
