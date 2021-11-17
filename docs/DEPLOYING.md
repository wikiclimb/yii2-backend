# Deploying the application.

Deploying to a server can be automated by cloning the repository to an empty ubuntu 20.04 machine, checking out the
master branch and running the `./deploy` script on the root folder.

The script does the following:

- Update the system and install necessary dependencies.
- Install Apache and PHP8.0 with the necessary modules.
- Install MariaDB and configure it.
- Configure the HTTP virtual hosts.
- Install Certbot and have it generate certificates using the Apache plugin.
- Have certbot generate server certificates and HTTPS configuration files.
- Initialize the Yii application.
- Install Composer dependencies.

## LetsEncrypt will store credentials at

IMPORTANT NOTES:

- Unable to install the certificate
- Congratulations! Your certificate and chain have been saved at:
  /etc/letsencrypt/live/wikiclimb.org/fullchain.pem Your key file has been saved at:
  /etc/letsencrypt/live/wikiclimb.org/privkey.pem Your cert will expire on 2022-02-11. To obtain a new or tweaked
  version of this certificate in the future, simply run certbot again with the "certonly" option. To non-interactively
  renew *all* of your certificates, run "certbot renew"
- Your account credentials have been saved in your Certbot configuration directory at /etc/letsencrypt. You should make
  a secure backup of this folder now. This configuration directory will also contain certificates and private keys
  obtained by Certbot so making regular backups of this folder is ideal.
