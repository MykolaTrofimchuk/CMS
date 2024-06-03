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
}