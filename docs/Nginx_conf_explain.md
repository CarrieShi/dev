# Nginx 配置详解
基础配置，dev 和 静态资源的相关配置

	#运行用户
	#user  nobody;
	#启动进程,通常设置成和cpu的数量相等
	worker_processes  1;
	
	#全局错误日志及PID文件
	#error_log  logs/error.log;
	#error_log  logs/error.log  notice;
	#error_log  logs/error.log  info;
	
	#pid        logs/nginx.pid;
	
	#工作模式及连接数上限
	events {
		#use   epoll;             #epoll是多路复用IO(I/O Multiplexing)中的一种方式,但是仅用于linux2.6以上内核,可以大大提高nginx的性能
	    worker_connections  1024;#单个后台worker process进程的最大并发链接数
 		# multi_accept on; 
	}
	

	#设定http服务器，利用它的反向代理功能提供负载均衡支持
	http {
		#设定mime类型,类型由mime.type文件定义
	    include       mime.types;
	    default_type  application/octet-stream;
	
		#授权的域，解决跨域问题
		add_header Access-Control-Allow-Origin *;
	    add_header Access-Control-Allow-Headers X-Requested-With;
	    add_header Access-Control-Allow-Methods GET,POST,OPTIONS;

		#配置多个 server 虚拟主机，必须配置，否则报错：could not build the server_names_hash, you should increase server_names_hash_bucket_size: 32
		#保存服务器名字的hash表是由指令 server_names_hash_max_size 和 server_names_hash_bucket_size所控制的。
		#在减少了在内存中的存取次数后，使在处理器中加速查找hash表键值成为可能。
		#如果 hash bucket size等于一路处理器缓存的大小，那么在查找键的时候，最坏的情况下在内存中查找的次数为2。
		#第一次是确定存储单元的地址，第二次是在存储单元中查找键值。
		#因此，如果Nginx给出需要增大 hash max size 或 hash bucket size的提示，那么首要的是增大前一个参数的大小。
	    server_names_hash_max_size 512;
	    server_names_hash_bucket_size 128;
	    

		#proxy_buffering 默认 on,开启后proxy_buffers和proxy_busy_buffers_size参数才会起作用
		#proxy_buffer_size（main buffer）都是工作的,不受proxy_buffering影响
		proxy_buffer_size  128k;	#代理请求缓存区_这个缓存区间会保存用户的头信息来提供Nginx进行规则处理。一般只要能保存下头信息即可。默认值：proxy_buffer_size 4k/8k 。设置从后端服务器读取的第一部分应答的缓冲区大小，通常情况下这部分应答中包含一个小的应答头。 
	    proxy_buffers   32 32k;	#设置用于读取应答（来自后端服务器）的缓冲区数目和大小，告诉Nginx保存单个用的几个Buffer，最大用多大空间 
	    proxy_busy_buffers_size 128k;	#如果系统很忙的时候可以申请更大的proxy_buffers，官方推荐*2 
	    proxy_temp_file_write_size 128k;	#设置在写入proxy_temp_path时缓存临文件数据的大小，在预防一个工作进程在传递文件时阻塞太长。 
		#proxy_temp_path /dev/shm/proxy_temp; \\类似于http核心模块中的client_body_temp_path指令，指定一个目录来缓冲比较大的被代理请求。		

		#设定日志格式
	    #log_format  main  '$remote_addr - $remote_user [$time_local] "$request" '
	    #                  '$status $body_bytes_sent "$http_referer" '
	    #                  '"$http_user_agent" "$http_x_forwarded_for"';

	    #access_log  logs/access.log  main;
	
		#sendfile 指令指定 nginx 是否调用 sendfile 函数（zero copy 方式）来输出文件，对于普通应用，
	    #必须设为 on,如果用来进行下载等应用磁盘IO重负载应用，可设置为 off，以平衡磁盘与网络I/O处理速度，降低系统的uptime.
	    sendfile        on;
	    #tcp_nopush     on;
	
		#连接超时时间
	    #keepalive_timeout  0;
	    keepalive_timeout  65;
	
		#开启gzip压缩
	    #gzip  on;
	
	    server {
		#服务器
	            listen       80;
	            server_name   dev.myproject.com;	#设置所有web服务器负载的共同域名 
	            root        D:/www/project/dev/php/entry;
	
	            charset utf-8;
				
				#设定本虚拟主机的访问日志
	            access_log  logs/host.access.log;

				#默认请求
		        location / {
		                root        D:/www/project/dev/php/entry;
		                index  index.html index.htm index.php;
		                autoindex on;	#当网站没有默认文件时，打开域名可以看到文件目录结构
				        #??????
						if (!-e $request_filename) {
				            rewrite ^/(.+)$ /index.php/$1 last;
				        }
	            }

				#PHP 脚本请求全部转发到 FastCGI处理. 使用FastCGI默认配置.
	         	location ~ \.php {
	                include        fastcgi_params;
	                set $path_info "";
	                set $real_script_name $fastcgi_script_name;
					#??????
	                if ($fastcgi_script_name ~ "^(.+?\.php)(/.+)$") {
	                    set $real_script_name $1;
	                    set $path_info $2;
	                }
	                fastcgi_param  SCRIPT_FILENAME  $document_root$real_script_name;  
	                fastcgi_param SCRIPT_NAME $real_script_name;
	                fastcgi_param PATH_INFO $path_info;
	                fastcgi_pass   127.0.0.1:9000;
	                fastcgi_index  index.php;
	            }
	        }

			server {
			#静态资源服务器
		        listen       80;
		        server_name  dev-statics.myproject.com;
		
		        location / {
		            root   D:/www/project/dev/statics;
		            index  index.html index.htm index.php;
		            autoindex on;
		        }
				
				# 定义错误提示页面
		        error_page   500 502 503 504  /50x.html;
		        location = /50x.html {
		            root   html;
		        }

		        location ~ \.php$ {
		            root           D:/www/project/dev/statics;
		            fastcgi_pass   127.0.0.1:9000;
		            fastcgi_index  index.php;
		            fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
		            include        fastcgi_params;
		        }
		    }
	}
