cd /home/sites/inout/
rm -rf /home/sites/inout/var/log
rm -rf /home/sites/inout/var/cache
git pull
composer install
php bin/console asset-map:compile