# 系统说明
- 框架：Lumen PHP Framework
- PHP： ^7.1.3
- 功能：完成 平台 与 FreeSwitch 服务通信,实现通话和其它功能的模块

# 部署说明

1. 下载项目代码
    ```bash
    $ git clone git@github.com:xskit/robot-ivr.git
    ```

1. 安装依赖包
    ```bash
    $ composer install
    ```

1. 系统注册到  `服务端`
    ```bash
    $ php artisan ivr:signin
    ```

​    

