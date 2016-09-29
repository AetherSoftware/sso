<?php

$app->post('authtoken', function(){

//!!!!!!!!!过滤
    $SSOToken = Request::input('ssotoken');
    //token是否过期

    $userinfo = DB::table('users')->select('id', 'name')->where('sso_token',$SSOToken)->first();

    if($userinfo == false){//没查到
        return response()->json(['status'=>'error','message'=>'无效的token','data'=>[]]);
    }

    return response()->json(['status'=>'success','message'=>'成功','data'=>$userinfo]);
});

$app->post('authlogin', function(){
//!!!!!!!!!过滤
    $email = Request::input('email');

    $password = Request::input('password');

    $hashedPassword = DB::table('users')->where('email',$email)->value('password');

    if(password_verify($password,$hashedPassword) == false){
        return response()->json(['status'=>'error','message'=>'邮箱或密码错误','data'=>[]]);
    }

    $SSOToken = strtoupper(md5(uniqid(mt_rand(),true)));//生成token

    $tokenHasUpdated = DB::table('users')->where('email',$email)->update(['sso_token' => $SSOToken]);

    if($tokenHasUpdated == false){
        return response()->json(['status'=>'error','message'=>'token生成失败','data'=>[]]);
    }

    return response()->json(['status'=>'success','message'=>'成功','data'=>$SSOToken]);

});



