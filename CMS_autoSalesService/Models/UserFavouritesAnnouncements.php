<?php

namespace Models;

use core\Model;

/**
 * @property int $id ID запису
 * @property int $user_id ID користувача
 * @property int $announcement_id ID оголошення
 */

class UserFavouritesAnnouncements extends Model
{
    public static $tableName = 'user_favourite_announcements';
}