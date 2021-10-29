FROM yiisoftware/yii2-php:8.0-apache

# Change document root for Apache
RUN sed -i -e 's|/app/web|/app/apiv1/web|g' /etc/apache2/sites-available/000-default.conf