#!/bin/bash

# 重置权限
mkdir -p /home/htdocs                 \
&& mkdir -p /home/conf/php            \
&& mkdir -p /home/conf/php-fpm        \
&& mkdir -p /home/conf/httpd          \
&& mkdir -p /home/log/php-fpm         \
&& mkdir -p /home/log/httpd           \
&& mkdir -p /home/log/php

chown -R apache:apache         /home/htdocs           \
&& chown -R apache:apache         /home/log/php          \
&& chown -R apache:apache         /home/log/httpd        \
&& chown -R apache:apache         /home/log/php-fpm      \
&& chown -R apache:apache         /home/conf/httpd       \
&& chown -R apache:apache         /home/conf/php         \
&& chown -R apache:apache         /home/conf/php-fpm

sleep 5

# 启动PHP-FPM
php-fpm -D

sleep 5

# 启动httpd
rm -rf /run/httpd/httpd.pid
httpd -D FOREGROUND