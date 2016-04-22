# WNMP 配置
参考：[wnmp配置][1]

### Nginx 安装
* 下载Nginx，解压

* 基本命令
	```
	start nginx    //启动服务
	nginx -s stop    // 停止nginx
	nginx -s reload    // 重新加载配置文件
	nginx -s quit    // 退出nginx
	```

* nginx.conf 配置
	```
	location / {
	            root   D:/www;
	            index  index.html index.htm index.php;
	            autoindex on;  #当网站没有默认文件时，打开域名可以看到文件目录结构
	        }
	...
	location ~ \.php$ {
	            root           D:/www;
	            fastcgi_pass   127.0.0.1:9000;
	            fastcgi_index  index.php;
	            fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
	            include        fastcgi_params;
	        }
	```

### MySQL 安装
* 下载zip

* 初始化，5.7版本没有data文件夹，并且不支持手动添加空的data
	```
	cmd 进入 mysql/bin
	mysqld --initialize
	```

* 配置my.ini
注意目录的格式，以及character-set-server 而不是default-character-set=utf8
	```
	basedir = D:\\wnmp\\mysql
	datadir = D:\\wnmp\\mysql\\data
	port = 3306
	character-set-server = utf8
	```

* 安装mysql服务
MySQL 是服务名
	```
	mysqld --install MySQL --defaults-file=D:\wnmp\mysql\my.ini
	```

* 基本命令
	```
	启动MySQL服务
	net start MySQL
	
	停止MySQL服务
	net Stop MySQL
	
	服务中去掉MySQL
	sc delete MySQL
	```

* 修改密码
mysql 5.7的账户root是有密码的，Linux下据说是放在/root/.mysecret
win 不知道在哪里，找了另一种方法，
	```
	忘记密码，重置密码
	net stop MySQL
	mysqld --skip-grant-tables
	重开一个cmd窗口
	mysql -uroot -p
	空密码登陆
	修改密码，注意5.7没有password字段了,修改的是authentication_string
	update mysql.user set authentication_string=PASSWORD('123456') where user='root';
	flush privileges;
	exit;
	
	用密码登陆
	mysql -uroot -p
	show databases;
	提示
	ERROR 1820 (HY000): You must reset your password using ALTER USER statement befo
	re executing this statement.
	上面的操作不完完全，执行下面
	alter user 'root'@'localhost' identified by '123456';
	据说这个也可以
	set password for 'root'@'localhost'=password('123456');
	OK
	```

### PHP
* 下载PHP7 解压 7暂时不支持memcache和redis 改为5.5
* 复制php.ini-development，改名为php.ini
	```
	extension_dir = "D:\wnmp\php\ext"
	
	php7中没有php_mysql.dll,可以改用pdo,php5中有
	extension=php_mysql.dll
	extension=php_mysqli.dll
	extension=php_pdo_mysql.dll
	
	CGI 设置
	enable_dl = On
	cgi.force_redirect = 0
	cgi.fix_pathinfo=1
	fastcgi.impersonate = 1
	cgi.rfc2616_headers = 1
	```

* 测试
	```
	D:\wnmp\php>php-cgi.exe -b 127.0.0.1:9000-c D:\wnmp\php\php.ini
	```
php7 报错：vcruntime140.dll不存在
下载 [Visual C++ Redistributable for Visual Studio2015][2]
php5 报错：msvcr110.dll不存在
下载 [Visual C++ Redistributable for Visual Studio 2012 Update 4][3]

### 整合Nginx PHP
* 下载RunHiddenConsole，解压到nginx下

* 创建start_nginx.bat文件
	```
	@echo off
	REM Windows 下无效
	REM set PHP_FCGI_CHILDREN=5
	
	REM 每个进程处理的最大请求数，或设置为 Windows 环境变量
	set PHP_FCGI_MAX_REQUESTS=1000
	 
	echo Starting PHP FastCGI...
	RunHiddenConsole D:/wnmp/php/php-cgi.exe -b 127.0.0.1:9000 -c D:/wnmp/php/php.ini
	 
	echo Starting nginx...
	RunHiddenConsole D:/wnmp/nginx/nginx.exe -p D:/wnmp/nginx
	```

* 创建stop_nginx.bat脚本
	```
	@echo off
	echo Stopping nginx...  
	taskkill /F /IM nginx.exe > nul
	echo Stopping PHP FastCGI...
	taskkill /F /IM php-cgi.exe > nul
	exit
	```


### 其他
* memcache
下载合适的版本：[php_memcache.dll][4]



[1]: http://www.cnblogs.com/li-cheng/p/4399149.html "wnmp配置"
[2]: https://download.microsoft.com/download/9/3/F/93FCF1E7-E6A4-478B-96E7-D4B285925B00/vc_redist.x64.exe "Visual C++ Redistributable for Visual Studio2015"
[3]: https://www.microsoft.com/en-in/download/details.aspx?id=30679 "Visual C++ Redistributable for Visual Studio 2012 Update 4"
[4]: http://windows.php.net/downloads/pecl/releases/memcache/3.0.8/ "php_memcache.dll"
