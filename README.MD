bash -c "$(wget -qLO - https://github.com/tteck/Proxmox/raw/main/ct/ubuntu.sh)"

192.168.0.193/24
192.168.0.254

root
..yv

df
sudo apt install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt upgrade

sudo apt -y install php8.2
php -v
sudo apt -y install libapache2-mod-php8.2

curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | sudo -E bash
sudo apt install symfony-cli
symfony --version

curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
HASH=`curl -sS https://composer.github.io/installer.sig`
echo $HASH
php -r "if (hash_file('SHA384', '/tmp/composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
composer --version

curl https://raw.githubusercontent.com/creationix/nvm/master/install.sh | bash 
source ~/.bashrc   
nvm install --lts 
npm install -g pnpm

sudo apt install git -y
git config --global user.name "Archistico"
git config --global user.email "info@archistico.com"
git config --list
nano ~/.gitconfig

ssh-keygen -t ed25519 -C "info@archistico.com"
eval "$(ssh-agent -s)"
ssh-add ~/.ssh/id_ed25519
cat ~/.ssh/id_ed25519.pub

Copia tutto il cat sotto setting -> SSH keys

sudo apt install sqlite3 libsqlite3-dev -y

sudo apt install curl -y

sudo ufw app list
sudo ufw allow 'Apache'

sudo systemctl status apache2

per trovare l'IP
ip addr show eth0 | grep inet | awk '{ print $2; }' | sed 's/\/.*$//'


sudo mkdir /var/www/inout
sudo chown -R $USER:$USER /var/www/inout
sudo nano /etc/apache2/sites-available/inout.conf

<VirtualHost *:80>
    ServerName inout
    ServerAlias www.inout
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/inout/public

    <Directory /var/www/inout/public>
        AllowOverride None
        Require all granted
        FallbackResource /index.php
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>


sudo a2ensite inout
sudo a2dissite 000-default
sudo apache2ctl configtest
sudo systemctl reload apache2

nano /var/www/inout/index.html

<html>
  <head>
    <title>inout website</title>
  </head>
  <body>
    <h1>Hello World!</h1>

    <p>This is the landing page of <strong>inout</strong>.</p>
  </body>
</html>

sudo nano /etc/apache2/mods-enabled/dir.conf
spostare php prima di html
<IfModule mod_dir.c>
        DirectoryIndex index.php index.html index.cgi index.pl index.xhtml index.htm
</IfModule>

sudo systemctl reload apache2

nano /var/www/inout/index.php
<?php
phpinfo();

sudo apt install php8.2-fpm php8.2-common php8.2-mysql php8.2-xml php8.2-xmlrpc php8.2-curl php8.2-gd php8.2-imagick php8.2-cli php8.2-dev php8.2-imap php8.2-mbstring php8.2-opcache php8.2-soap php8.2-zip php8.2-intl php8.2-bcmath unzip -y
sudo apt install php8.2-sqlite

symfony check:requirements


composer require symfony/apache-pack 

symfony server:ca:install

sudo apt-get install ruby-full
gem install bundler



apt update
apt install samba -y

useradd smbutente
usermod -a -G sambashare smbutente
smbpasswd -a smbutente
usermod -aG sudo smbutente

chown smbutente:sambashare /var/www/inout -R
chmod 0775 /var/www/inout -R

cp /etc/samba/smb.conf /etc/samba/smb.conf.old
rm /etc/samba/smb.conf
nano /etc/samba/smb.conf


[global]
workgroup = WORKGROUP
vfs objects = fruit streams_xattr
map to guest = bad user

[InOut]
comment = WebApp
path = /var/www/inout
browsable = yes
read only = no


per l'accesso con ssh
mkdir /home/smbutente
passwd smbutente
sudo chown smbutente:sambashare /home/smbutente -R
usermod -d /home/smbutente -m smbutente
ssh smbutente@192.168.0.193


chown -R www-data:root /var/www
php bin/console asset-map:compile


composer require --dev symfony/debug-bundle