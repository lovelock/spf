# Simple PHP Framework
搞个这个出来也算是对我近期工作的一个总结吧。用了很多PHP框架，总的来说Slim算是很不错的，很简单。但它又太过简单，无法直接上来就搞出一个**可维护**的系统。所以我在它上面做了一层简单的开发，让它能直接用在生产环境中。

## 功能

1. 支持部署环境切换
2. 数据库访问层
3. 支持json和html（页面）两种渲染方式
4. 带全局唯一_ID的日志系统
5. 集成访问外部API的能力
6. MVC各层提供单独的IoC子类

下面详细描述一下框架的特点：

###  1. 部署环境切换

该功能由`lovelock/spe`实现，该package可以单独使用。

我们在开发应用的过程中肯定会涉及到多个部署环境，通常叫做**开发环境**，**测试环境**，**仿真（模拟）环境**，**线上环境**等等，一直以来也有各种不同的实现方式。我这里也不妨说一下我知道的解决方案：

1. 配置和代码隔离，不同部署环境中把不同的配置放在同样的位置，这样代码读取的路径和文件一样，但得到的结果就不一样了。**这种方式对开发透明，开发人员不需要关心部署的问题，但往往需要专门的运维人员来维护这套配置，相应的还有配置推送系统**。
2. 配置放在代码中，根据某个标记来判断当前的环境。这样所有可能的配置就会放在一起，根据这个标记读取出需要的配置。**这种方式较灵活，成本较低。但把代码和配置放在一起本身就是很不安全的行为，同时如果需要修改配置还需要上线，带来额外的风险**。

在spf中，我使用了第二种方式，但采用接口的方式来解耦了代码和配置，用户可以用自己喜欢的方式保存配置，只须实现相应的接口即可。下面详细展开。

### 2. 数据库配置

数据库连接功能由`lovelock/spw`实现，该package可以单独使用。

我理解的数据库访问层应该是一个**独立**的组件，拿过来就能用，而不是需要所有的Model类去继承它。我们知道，继承是强耦合的，这会给之后替换数据库访问层带来大量的麻烦。而且，现代的PHP项目开发都引入了Container的概念，所以我就也把数据库访问的工作抽象到一个独立的service中。

```php
$container['db_spw'] = function ($c) {
    return new \Spw\Connection\Connection(Bag::get('database', 'spw'));
};
```

关于Spw的具体API文档，可以参考[项目主页](https://github.com/lovelock/Spw)。

`Spw\Connnection\Connection()`接受实现`Spw\Config\ConfigInterface`的对象作为唯一参数，后者要求提供几个可以确定数据库连接信息的方法，只要实现该Interface就能用来实例化`Connection`。在spf-framework项目中，我实现了一个抽象类`AbstractConfig`实现了`ConfigInterface`中获取各个配置的方法，它声明了一个属性`$config`，该属性用于以数组形式保存从配置文件中读取的数据，而预留了构造函数给使用者自己实现。例如要用`.ini`文件来保存配置，可以这样实现一个`Database`类
```php
class Database extends AbstractConfig
{
    public function __construct($dbName)
    {
         $contents = parse_ini_file(WEB_ROOT . '/App/Config/' . ucfirst(ENV) . '/database.ini', true);
         $this->config = $contents[$dbName];
    }
}
```
这种设计方式非常灵活，只要你可以把配置从配置文件中读取出来转换成数组，用任何配置文件都是没有问题的，甚至你可以引入鸟哥的Yaconf来，因为它支持ini段的继承，可以把所有配置放在同一个文件中，无论如何，最终把数组赋值给`$config`属性即可。


### 3. JSON和页面两种渲染方式

正常我们的开发工作返回的响应也就无外乎两种方式，返回JSON数据或者返回页面。返回JSON数据时，通常有`code`,`msg`,`request_id`和`data`这几个字段。返回页面时，引入了Twig模板引擎（随着前端人员话语权的提高，貌似现在也没有多少人会用后端的模板引擎了）。

### 4. 带全局唯一ID的日志系统

本质上还是Monolog，但我做了一个Wrapper，把本次请求的REQUEST_ID强制带进来，这样在处理日志的时候会对同一次请求记录的日志一目了然。

### 5. 访问外部API的能力

这个功能在内部通常会称为RPC了，但其实它和我理解的RPC还是有一定差距的。我选择了比较轻量的unirest而不是现在炙手可热的guzzle，就是因为感觉guzzle太重了，就像我不喜欢Laravel一样。

### 6. MVC各层提供单独的IoC子类

用户在写MVC各层业务代码的时候难免会希望对它们统一做一些修改，所以在提供顶层IoC类用来做控制翻转的的前提下，对Model/View/Controller都提供了Base类。



## 安装

1. `composer create-project lovelock/spf path/to/project`

2. 创建一系列需要的目录`mkdir -p cache view/{templates,cache}`

3. 创建Nginx vhost，并重启Nginx

   ```nginx
   server {
       listen 80;
       server_name spf.com;
       index index.php;
       error_log /var/log/nginx/spf.error.log;
       access_log /var/log/nginx/spf.access.log;
       root /home/frost/web/spf/public;

       location / {
           try_files $uri /index.php$is_args$args;
       }

       location ~ \.php {
           try_files $uri =404;
           include fastcgi_params;
           fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
           fastcgi_param SCRIPT_NAME $fastcgi_script_name;
           fastcgi_index index.php;
           fastcgi_pass 127.0.0.1:9000;
       }
   }
   ```

   ​