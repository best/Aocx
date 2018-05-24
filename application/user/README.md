# 功能说明

## 用户密码

### 用户密码生成方式

1. 随机生成六位唯一身份码userID ：md5(userID) => 作为密钥

2. 获取的密码原文：oPassword.aocx_password_salt => 作为原文

3. md5(oPassword) 取前16位 => 作为初始iv

4. sPassword = aes-256-cfb加密（原文，密钥，初始iv）

### 用户密码验证过程

1. 从数据库读取userID和sPassword

2. 原文 => 用户提交oPassword

3. 密钥 => md5(userID)

4. 初始iv => md5(oPassword) 取前16位

5. wPassword = aes-256-cfb加密（原文，密钥，初始iv）

6. 对比sPassword 和 wPassword

## Ticket

### Ticket生成

1. Ticket每次登陆时候进行生成，保存到session和cookie中，用于记录用户登陆信息，维持用户登陆状态

2. Ticket同样使用AES对身份信息进行加密

3. key = 随机生成(32)

4. 初始iv = 随机生成(16)

5. 原文 = md5(userID)

6. ticket = aes.加密（userID,key,iv）

7. 根据checkbox保存到session +（cookie）

### Ticket验证

* 直接去数据库查找ticket并进行IP比对即可

* 限制网站用户只能同时登陆一个账号

## 邮件验证Token

### Token生成

* 16位随机字符串

* 生存周期三天 过期（当前日期-生成日期>3天）需重新发送