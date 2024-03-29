> [简体中文](README.zh-CN.md) | [English](README.md)
### 背景
> 现在越来越多的业务涉及到了社交电商, 或者是邀请分享当中
> 但是很多朋友生成的邀请码还是随机的, 导致了需要进行多次查询 (`注册查重复`,`注册用邀请码查信息等`)
> 所以特地设计了这个`邀请码`的编码方案
> 可以用用户 `Id` 进行编码, 同时也可以用编码后的字符串反推回 `Id`

#### 在我的想法当中是这样的:<br/>
1. 用户注册时,查询数据库输入手机是否存在 (常规操作，不能少的)<br/>
2. 手机号验证没有问题后, 将用户登录信息, 存储至 `登录表`<br/>
3. 然后使用 `登录表` 新增后的数据表自增 `Id` 生成邀请码<br/>

##### 这样邀请码就不会存在重复<br/>
##### 同时以后也不会用到邀请码作为查询 `where`<br/>
##### 因为邀请码是可以反推回 `Id` 的情况<br/>

#### 安装
```
composer require nice-yu/invite-code
```

#### 单元测试信息
- 覆盖率 100% 的单元测试
- 
```
Invite Code (NiceYu\Tests\InviteCode\InviteCode)
✔ Multiple separator letters [0.14 ms]
✔ Invite code max encode [36.23 ms]
✔ Invite code encode [0.17 ms]
✔ Invite code decode [0.13 ms]
✔ Invite code error capture [4.90 ms]
✔ Invitation code settings [1.67 ms]

Time: 00:00.061, Memory: 6.00 MB
```

#### 生成一个邀请码
```php
/** 只需要引入即可 */
$class = new \NiceYu\InviteCode\InviteCode();
$class->encode(1);
```

#### 编码和解码
```php
$class = new \NiceYu\InviteCode\InviteCode();

/** 编码 */
$class->encode(1);

/** 解码 */
$class->decode($app->encode(1));
```

#### 改变生成邀请码位数
1. 默认情况下, 是六位
2. 同时去除掉了 `O` `0` `I` `1` `Y` `Z`
3. 去除掉肉眼容易分辨错误的字符后, (26 + 10) - 6 = 30位
4. 正常来说:<br/>
    30^6次方 = 10亿次<br/>
    我们可以得到不同的 7.29亿个邀请码<br/>
```php
$class = new \NiceYu\InviteCode\InviteCode();
$class->encode(729000000);
```

#### 修改邀请码的配置
1. 默认情况下是以下配置
2. 大家可以把字典打乱, 到时候每个字符代表的意义就会变更
3. `注意`: `千万不要中途修改字典, 不然需要全量更新`
```php
$max = 6;
$complement = array('Y', 'Z');
$dictionaries = array(
    '2', '3', '4', '5', '6', '7', '8', '9',
    'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
    'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R',
    'S', 'T', 'U', 'V', 'W', 'X'
);

$class = new \NiceYu\InviteCode\InviteCode();
$class->setMax($max)
      ->setComplement($complement)
      >setDictionaries($dictionaries);
$encode = $class->encode(1);
```

#### 假如需要的是其他位数
1. 一般来说 6位数 已经足够大型项目使用
2. 如果你有需要, 可以修改为 7位数 比如用于订单的计算, 也是可以的

| 位数  | 最大值           | 计算方法   |
|-----|---------------|--------|
| 5   | 2430,0000     | 30^5次方 |
| 6   | 7,2900,0000   | 30^6次方 |
| 7   | 218,7000,0000 | 30^7次方 |

