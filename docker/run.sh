#!/usr/bin/env sh

# Start Octane with the roadrunner binary if we find
# that dependency in the composer.json file
# This is a noop if octane is not used
if [ -f /var/www/html/composer.json ]; then
  if grep -Fq "spiral/roadrunner" /var/www/html/composer.json
  then
      sed -i 's/;rr command/command/g' /etc/supervisord.conf
  else
      sed -i 's/;swoole command/command/g' /etc/supervisord.conf
  fi
fi

exec supervisord -c /etc/supervisord.conf