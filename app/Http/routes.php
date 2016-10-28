<?php

# SSO
$app->post('auth/token', function(){
    if(!Request::has('ssotoken')){
        return response()->json(['status'=>'error','message'=>'缺少参数','data'=>[]]);
    }
//!!!!!!!!!过滤
    $SSOToken = Request::input('ssotoken');
    //token是否过期

    $userinfo = DB::table('users')->select('id', 'name')->where('sso_token',$SSOToken)->first();

    if($userinfo == false){
        return response()->json(['status'=>'error','message'=>'无效的token','data'=>[]]);
    }

    return response()->json(['status'=>'success','message'=>'成功','data'=>$userinfo]);
});

$app->post('auth/login', function(){
//!!!!!!!!!过滤
    $email = Request::input('email');

    $password = Request::input('password');

    $hashedPassword = DB::table('users')->where('email',$email)->value('password');

    if(password_verify($password,$hashedPassword) == false){
        return response()->json(['status'=>'error','message'=>'邮箱或密码错误','data'=>[]]);
    }

    $SSOToken = str_random(60);//生成token strtoupper(md5(uniqid(mt_rand(),true)))

    $tokenIsUpdated = DB::table('users')->where('email',$email)->update(['sso_token' => $SSOToken]);

    if($tokenIsUpdated == false){
        return response()->json(['status'=>'error','message'=>'token生成失败','data'=>[]]);
    }

    return response()->json(['status'=>'success','message'=>'成功','data'=> ['ssotoken'=>$SSOToken]]);

});

$app->post('auth/logout', function(){

    if(!Request::has('ssotoken')){
        return response()->json(['status'=>'error','message'=>'缺少参数','data'=>[]]);
    }
    $SSOToken = Request::input('ssotoken');

    $uid = DB::table('users')->where('sso_token',$SSOToken)->value('id');
    if($uid == false){
        return response()->json(['status'=>'error','message'=>'无效的token','data'=>[]]);
    }

    $tokenIsRemoved = DB::table('users')->where('id',$uid)->update(['sso_token'=>'']);
    if($tokenIsRemoved == false){
        return response()->json(['status'=>'error','message'=>'token清除失败','data'=>[]]);
    }

    return response()->json(['status'=>'success','message'=>'成功','data'=> []]);
});



