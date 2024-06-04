<?php

namespace Models;

use core\Model;

/**
 * @property int $id ID картинки
 * @property int $announcement_id ID оголошення
 * @property string $image_path Шлях до картинки
 */

class CarImages extends Model
{
    public static $tableName = 'car_images';

    public static function AddVehicleImages($announcementId, $imagePath)
    {
        $image = new CarImages();
        $image->announcement_id = $announcementId;
        $image->image_path = $imagePath;
        $image->save();
    }
}