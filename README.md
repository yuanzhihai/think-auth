# ThinkPHP6+ 权限认证

## 安装

~~~
composer require yzh52521/think-auth
~~~

基础user表

``` sql
CREATE TABLE `user` (
`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
`username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名称',
`email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮箱地址',
`email_verified_at` timestamp NULL DEFAULT NULL COMMENT '邮箱验证时间',
`phone` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
`phone_verified_at` timestamp NULL DEFAULT NULL COMMENT '手机验证时间',
`password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码',
`remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '记住密码token',
`created_at` timestamp NULL DEFAULT NULL,
`updated_at` timestamp NULL DEFAULT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `user_email_unique` (`email`),
UNIQUE KEY `user_phone_unique` (`phone`) USING HASH
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 ```

Auth常用方法

```
//通过账号密码登录  第二个参数 此值指示是否需要验证会话的 「记住我」 功能
Auth::attempt(['username' => 'tp5er@qq.com', 'password' => '123456'], $remember = true);
//通过用户实例登录 
Auth::login(User::find(1), $remember = false);
//只验证一次
Auth::once(['username' => 'tp5er@qq.com', 'password' => '123456'])

//通过id登录
Auth::loginUsingId(1,$remember = false)
在没有会话或cookie的情况下，将给定的用户ID登录认证用户
Auth::onceUsingId(1);


// 获取当前的认证用户信息 ...
$user = Auth::user();
// 获取当前的认证用户id ...
$id = Auth::id();
if (Auth::check()) {
    // 用户已登录...
}
//使用户退出登录（清除会话）
Auth::logout();


//访问特定的看守器实例
Auth::guard('api')->attempt($credentials,$remember = false);
Auth::guard('api')->login(User::find(1), $remember = false);
```