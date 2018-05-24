# Aocx
===============

Aocx是个人设计开发的安徽大学在线CTF竞赛系统，毕业设计作品

## 网站功能设计

功能设计如下：

~~~
www  WEB功能结构框架
├─index                     首页
│
├─practice                  题库
│  ├─index                  题库分类显示页面
│  ├─{species}              题目分类（species：Web Pwn Crypto Reverse Misc）
│  │  └─{pID}               答题页面（pID：题目编号）
│  │
│  ├─judge                  题目评分功能
│  └─ ...                   更多
│
├─ctf                       竞赛
│  ├─index                  全部竞赛显示页面（可进入历史竞赛围观）
│  ├─{contest}              竞赛名称（contest：contest.nickname）
│  │  ├─waiting             竞赛倒计时页面
│  │  ├─index               竞赛主页面：题目分类显示
│  │  ├─{species}           题目分类（species：Web Pwn Crypto Reverse Misc）
│  │  │  └─{pID}            答题页面（pID：题目编号）
│  │  │
│  │  ├─rank                分数公开页面
│  │  ├─notice              竞赛中公告页面
│  │  └─ ...                更多
│  │
│  ├─judge                  竞赛题目评分功能
│  │
│  └─ ...                   更多
│
├─rank                      排行榜
│
├─user                      用户
│  ├─login                  用户登陆页面
│  ├─register               用户注册页面
│  ├─logout                 用户登出页面
│  ├─findpass               找回密码页面
│  ├─resetpass              重置密码页面
│  ├─mail                   邮件验证
│  │  ├─resend              重发注册邮件验证功能
│  │  └─registerValidate    注册邮件验证页面
│  │
│  ├─profile                个人中心
│  ├─account                账户管理
│  ├─history                答题记录
│  │
│  ├─ctf                    竞赛管理
│  ├─apply                  竞赛报名
│  └─ ...                   更多
│
└─ ...                      更多


 ~~~
 
 ## CTF题目类型 
 
 Web Pwn Crypto Reverse Misc
 
 WEB 溢出 密码学 逆向 杂项
 
 W   P    C     R    M
 
 ## 邮件验证
 
 * 注册邮箱验证 - 访问链接
 
 * 找回密码验证 - 访问链接