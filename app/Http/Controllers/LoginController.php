<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Request;
class LoginController extends BaseController
{
    function auth($token){

        $request = request::input('token');


    }



}
