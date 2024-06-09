<?php

namespace Models;

use core\Core;
use core\Model;

/**
 * @property int $id ID
 * @property string $login Логін
 * @property string $password Пароль
 * @property string $first_name Ім'я
 * @property string $last_name Прізвище
 * @property string $role Роль
 * @property string $email Ел.пошта
 * @property string $phone_number Номер телефону користувача
 * @property string $region Місце (регіон) проживання користувача
 * @property string $image_path Фото користувача
 */
class Users extends Model
{
    public static $tableName = 'users';

    public static function FindLoginAndPassword($login, $password)
    {
        $rows = self::findByCondition(['login' => $login]);
        if (!empty($rows)) {
            $user = $rows[0];
            if (password_verify($password, $user['password'])) {
                return $user;
            } else {
                error_log('Password verification failed.');
            }
        }
        return null;
    }

    public static function FindByLogin($login)
    {
        $rows = self::findByCondition(['login' => $login]);
        if (!empty($rows))
            return $rows[0];
        else
            return null;
    }

    public static function IsUserLogged()
    {
        return !empty(Core::get()->session->get('user'));
    }

    public static function RegisterUser($login, $password, $firstName, $lastName, $email = null, $phoneNumber = null, $region = null, $role = 'user')
    {
        $user = new Users();
        $user->login = $login;
        $user->password = password_hash($password, PASSWORD_DEFAULT); // Хешування пароля
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->email = $email;
        $user->phone_number = $phoneNumber;
        $user->region = $region;
        $user->role = $role;
        $user->save();
    }

    public static function LoginUser($user)
    {
        Core::get()->session->set('user', $user);
    }

    public static function LogoutUser()
    {
        Core::get()->session->remove('user');
    }

    public static function GetUserInfo($userId)
    {
        return self::findByCondition(['id' => $userId]);
    }

    public static function EditUserInfo($userId, $userData)
    {
        $user = Users::findById($userId);

        if ($user) {
            foreach ($userData as $field => $value) {
                if ($field === 'password') {
                    // Якщо поле - пароль, хешуємо його перед збереженням
                    $user->{$field} = password_hash($value, PASSWORD_DEFAULT);
                } elseif (isset($value) && !empty($value)) {
                    // Встановлюємо інші поля, якщо вони не порожні
                    $user->{$field} = $value;
                }
            }
            var_dump($user);
            $user->save(); // Зберігаємо зміни в базі даних
            return true;
        } else {
            return false;
        }
    }

    public static function IsAdmin($userId)
    {
        if (self::IsUserLogged()) {
            $rows = self::findRowsByCondition('role', ['id' => $userId, 'role' => 'admin']);
            if (!empty($rows) && $rows[0]['role'] === 'admin')
                return true;
            else
                return false;
        }
        return false;
    }

    public static function CountAll($where = null)
    {
        $result = self::findRowsByCondition('COUNT(*) as count', $where);
        return isset($result[0]['count']) ? (int)$result[0]['count'] : 0;
    }

    public static function DeleteRow($where){
        if (empty($where))
            $where = null;
        return Core::get()->db->delete(self::$tableName, $where);
    }

    public static function getPaginatedUsers($limit, $offset, $where = null)
    {
        if (empty($where))
            $where = null;
        $rows = Core::get()->db->select(self::$tableName, '*', $where, $limit, $offset, );
        return $rows;
    }

    public static function countAllUsers()
    {
        return self::CountAll();
    }
}