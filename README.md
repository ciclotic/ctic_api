*NOTA Esta documentación está en castellano por motivos de productividad dada la comunidad que la desarrolla. En cuanto salga una versión beta toda documentación va a estar también en Inglés.

##Instalación
composer install

Seguir los siguientes pasos para instalar jwt:
https://api-platform.com/docs/core/jwt/

Shortcut:

`set -e`

`apk add openssl`

`mkdir -p config/jwt`

Configurar variable JWT_PASSPHRASE en el fichero .env.local

`jwt_passhrase=$(grep ''^JWT_PASSPHRASE='' .env.local | cut -f 2 -d ''='')`

`echo "$jwt_passhrase" | openssl genpkey -out config/jwt/private.pem -pass stdin -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096`

`echo "$jwt_passhrase" | openssl pkey -in config/jwt/private.pem -passin stdin -out config/jwt/public.pem -pubout`

`chmod 755 config/jwt/public.pem`

`chmod 750 config/jwt/private.pem`

Si es necesario se debe poner un grupo que el usuario que ejecuta el servidor tenga permisos en los dos ficheros anteriores.
