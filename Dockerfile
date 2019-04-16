FROM centos:latest
RUN yum -y upgrade
RUN yum -y install https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm
RUN yum -y install http://rpms.remirepo.net/enterprise/remi-release-7.rpm
RUN yum -y install yum-utils at
RUN yum-config-manager --enable remi-php73
RUN yum -y install \
        php \
        php-mcrypt \
        php-cli \
        php-gd \
        php-curl \
        php-mysql \
        php-ldap \
        php-zip \
        php-opcache \
        php-fileinfo \
        php-imap \
        php-mbstring \
        php-mrcrypt \
        php-pear \
        php-pecl-igbinary \
        php-pecl-imagick \
        php-pecl-memcached \
        php-pecl-redis \
        php-pecl-smbclient \
        php-process \
        php-xml \
        http \
        mod_ssl \
        mod_ldap \
        openssl \
        samba-common-tools \
CMD /usr/sbin/httpd -DFOREGROUND
