<?php

namespace Models;

use core\Core;
use core\Model;

/**
 * @property string $title Заголовок оголошення
 * @property string $text Текст оголошення
 * @property string $date Дата оголошення
 * @property int $id ID оголошення
*/
class Announcements extends Model
{
    public $table = 'announcements';
}