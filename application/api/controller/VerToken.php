<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-03-05
 * Time: 16:10
 */

namespace app\api\controller;
use app\api\service\VerToken as vToken;

class VerToken extends BaseController
{
    public function VerToken()
    {
        $token=new vToken();
        return $token->valid();

    }
}