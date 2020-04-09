FROM centos:7.7.1908

# 安装基础软件
RUN yum update -y                                                               \
    && yum install -y epel-release                                              \
    && yum clean all                                                            \
    && yum makecache                                                            \
    && yum groupinstall -y 'Development Tools'                                  \
    && yum install -y yum-utils net-tools sendmail git bc svn zip unzip screen vim zsh wget traceroute telnet setuptool nmon iotop bzip2 gzip psmisc xfsprogs tcpdump lvm2 sysstat htop openssl \
    && yum install -y http://rpms.remirepo.net/enterprise/remi-release-7.rpm    \
    && yum makecache                                                            \
    && yum install -y ImageMagick ImageMagick-devel ImageMagick-perl            \
    && yum --enablerepo=remi install -y redis memcached                         \
    && echo -e "set ts=4\nset expandtab" > /root/.vimrc

# 安装PHP
RUN yum install -y php74-runtime.x86_64            \
    php74.x86_64                                   \
    php74-build.x86_64                             \
    php74-php.x86_64                               \
    php74-php-bcmath.x86_64                        \
    php74-php-cli.x86_64                           \
    php74-php-common.x86_64                        \
    php74-php-dba.x86_64                           \
    php74-php-devel.x86_64                         \
    php74-php-fpm.x86_64                           \
    php74-php-gd.x86_64                            \
    php74-php-gmp.x86_64                           \
    php74-php-imap.x86_64                          \
    php74-php-intl.x86_64                          \
    php74-php-json.x86_64                          \
    php74-php-ldap.x86_64                          \
    php74-php-lz4.x86_64                           \
    php74-php-mbstring.x86_64                      \
    php74-php-mysqlnd.x86_64                       \
    php74-php-odbc.x86_64                          \
    php74-php-pdo.x86_64                           \
    php74-php-pdo-dblib.x86_64                     \
    php74-php-pear.noarch                          \
    php74-php-pecl-crypto.x86_64                   \
    php74-php-pecl-geoip.x86_64                    \
    php74-php-pecl-env.x86_64                      \
    php74-php-pecl-gnupg.x86_64                    \
    php74-php-pecl-http.x86_64                     \
    php74-php-pecl-imagick.x86_64                  \
    php74-php-pecl-inotify.x86_64                  \
    php74-php-pecl-leveldb.x86_64                  \
    php74-php-pecl-lua.x86_64                      \
    php74-php-pecl-mcrypt.x86_64                   \
    php74-php-pecl-json-post.x86_64                \
    php74-php-pecl-memcached.x86_64                \
    php74-php-pecl-mysql-xdevapi.x86_64            \
    php74-php-pecl-mysql.x86_64                    \
    php74-php-pecl-rar.x86_64                      \
    php74-php-pecl-redis5.x86_64                   \
    php74-php-pecl-swoole4.x86_64                  \
    php74-php-pecl-uuid.x86_64                     \
    php74-php-pecl-xdebug.x86_64                   \
    php74-php-pecl-xlswriter.x86_64                \
    php74-php-pecl-yaml.x86_64                     \
    php74-php-pgsql.x86_64                         \
    php74-php-pecl-zip.x86_64                      \
    php74-php-snmp.x86_64                          \
    php74-php-soap.x86_64                          \
    php74-php-sodium.x86_64                        \
    php74-php-xml.x86_64                           \
    php74-php-xmlrpc.x86_64

# 安装Composer
RUN cd /opt/remi/php74/root/usr/bin/                                                       \
    && ./php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"         \
    && ./php -r "if (hash_file('sha384', 'composer-setup.php') === 'a5c698ffe4b8e849a443b120cd5ba38043260d5c4023dbf93e1558871f1f07f58274fc6f4c93bcfd858c6bd0775cd8d1') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && ./php composer-setup.php --filename=composer                                        \
    && ./php -r "unlink('composer-setup.php');"                                            \
    && cd /root/

# 链接PHP相关二进制文件
RUN ln -s /opt/remi/php74/root/usr/bin/*         /usr/bin \
    && ln -s /opt/remi/php74/root/usr/sbin/*     /usr/bin

# 安装Apache
RUN yum install -y httpd \
    && rm -rf /etc/httpd/conf.d/welcome.conf

# 拷贝配置文件
# 1. PHP-FPM进程池配置
COPY conf/www.conf              /etc/opt/remi/php74/php-fpm.d/
# 2. php.ini配置
COPY conf/php.ini               /etc/opt/remi/php74/php.ini
# 3. httpd.conf配置
COPY conf/httpd.conf            /etc/httpd/conf/

# 新建/链接/拷贝目录
RUN mkdir -p /home/htdocs                   \
    && mkdir -p /home/conf/php              \
    && mkdir -p /home/conf/php-fpm          \
    && mkdir -p /home/conf/httpd            \
    && mkdir -p /home/log/php-fpm           \
    && mkdir -p /home/log/httpd             \
    && mkdir -p /home/log/php               \
    && ln -s /etc/opt/remi/php74/php-fpm.conf           /home/conf/php-fpm && ln -s /etc/opt/remi/php74/php-fpm.d /home/conf/php-fpm \
    && ln -s /etc/opt/remi/php74/php.ini                /home/conf/php          \
    && ln -s /var/log/httpd/*                           /home/conf/httpd        \
    && ln -s /etc/httpd/*                               /home/log/httpd         \
    && ln -s /var/opt/remi/php74/log/*                  /home/log/php-fpm

# 配置各个目录的权限（在启动的时候需要重新配置一遍，以防万一）
RUN chown -R apache:apache              /home/htdocs            \
    && chown -R apache:apache           /home/log/php           \
    && chown -R apache:apache           /home/log/httpd         \
    && chown -R apache:apache           /home/log/php-fpm       \
    && chown -R apache:apache           /home/conf/httpd        \
    && chown -R apache:apache           /home/conf/php          \
    && chown -R apache:apache           /home/conf/php-fpm

# 拉取最新源码
RUN cd /home/htdocs \
    && git clone https://github.com/LuRenJiasWorld/OPMLHub.git . \
    && cd src \
    && composer install

# 拷贝启动脚本
COPY script/init.sh            /

# 给予init.sh可执行权限
RUN chmod +x /init.sh

# 配置启动脚本
CMD /init.sh

# httpd
EXPOSE 80