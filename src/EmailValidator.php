<?php
/**
 * Created by PhpStorm.
 * User: violetatamasauskiene
 * Date: 2019-07-05
 * Time: 09:51
 */
declare(strict_types=1);

namespace Fikusas;


class EmailValidator
{

    public function isEmailValid(string $email): bool
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)){
            echo("$email is a valid email address");
        } else {
            echo("$email is not a valid email address");
        }
    }

}