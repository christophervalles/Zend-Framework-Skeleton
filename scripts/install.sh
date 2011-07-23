#!/bin/bash

echo "###################################################"
echo "#                                                 #"
echo "#        Zend Framework Skeleton Installer        #"
echo "#                                                 #"
echo "###################################################"
echo -e "\n- Getting current folder path"

# Get current foldername
currentFolder=`basename \`pwd\``

# Check if we are executing the script inside scripts folder or in the root folder
if [ $currentFolder == "scripts" ] ; then
    prefix='../'
else
    prefix='./'
fi

# Create a few folders
echo "- Creating log folders"
if [ ! -d "${prefix}logs" ]; then
    mkdir "${prefix}logs"
fi

if [ ! -d "${prefix}logs/backoffice" ]; then
    mkdir "${prefix}logs/backoffice"
fi

if [ ! -d "${prefix}logs/backoffice/missing_translations" ]; then
    mkdir "${prefix}logs/backoffice/missing_translations"
fi

if [ ! -d "${prefix}logs/frontend" ]; then
    mkdir "${prefix}logs/frontend"
fi

if [ ! -d "${prefix}logs/frontend/missing_translations" ]; then
    mkdir "${prefix}logs/frontend/missing_translations"
fi

echo "- Creating cache folder"
if [ ! -d "${prefix}cache" ]; then
    mkdir "${prefix}cache"
fi

echo "- Creating temporary folder"
if [ ! -d "${prefix}public/frontend/tmp" ]; then
    mkdir "${prefix}public/frontend/tmp"
fi

# Copy the config files
echo "- Copying environment file"
cp -n "${prefix}application/configs/environment.example.php" "${prefix}application/configs/environment.php"

echo "- Copying config file"
cp -n "${prefix}application/configs/application.example.ini" "${prefix}application/configs/application.ini"

# Getting db parameters from the user
echo "- Customizing config file - Enter for default value"
echo "   [DATABASE]"
read -p "      Database name [zfskel]: " dbName
read -p "      Database username [root]: " dbUsername
read -p "      Database password: " dbPassword
read -p "      Database host (ip or hostname) [localhost]: " dbHost

# Getting security parameters from the user
csrfSaltRandom=`md5 -qs $RANDOM`
frontendSaltRandom=`md5 -qs $RANDOM`
backofficeSaltRandom=`md5 -qs $RANDOM`

echo "   [SECURITY]"
read -p "      Salt for anti-CSRF tokens [$csrfSaltRandom]: " csrfSalt
read -p "      Salt for the frontend passwords [$frontendSaltRandom]: " frontendSalt
read -p "      Salt for the backoffice passwords [$backofficeSaltRandom]: " backofficeSalt

# Getting the backoffice credentials
backofficePasswordRandom=`md5 -qs $RANDOM | cut -c1-8`
backofficeEmailRandom=${RANDOM}@mailinator.com

echo "   [BACKOFFICE CREDENTIALS]"
read -p "      Username [john.doe]: " backofficeUsername
read -p "      Password [$backofficePasswordRandom]: " backofficePassword
read -p "      Email [$backofficeEmailRandom]: " backofficeEmail

# Setting default values
if [ -z "$dbName" ]; then
    dbName=zfskel
fi

if [ -z "$dbUsername" ]; then
    dbUsername=root
fi

if [ -z "$dbHost" ]; then
    dbHost=localhost
fi

if [ -z "$csrfSalt" ]; then
    csrfSalt=$csrfSaltRandom
fi

if [ -z "$frontendSalt" ]; then
    frontendSalt=$frontendSaltRandom
fi

if [ -z "$backofficeSalt" ]; then
    backofficeSalt=$backofficeSaltRandom
fi

if [ -z "$backofficeUsername" ]; then
    backofficeUsername=john.doe
fi

if [ -z "$backofficePassword" ]; then
    backofficePassword=$backofficePasswordRandom
fi

if [ -z "$backofficeEmail" ]; then
    backofficeEmail=$backofficeEmailRandom
fi

# Modifying config file
echo "- Modifying config file"
sed -i '' -e "9s/.*/resources.db.params.dbname = \"$dbName\"/" ${prefix}application/configs/application.ini
sed -i '' -e "10s/.*/resources.db.params.username = \"$dbUsername\"/" ${prefix}application/configs/application.ini
sed -i '' -e "11s/.*/resources.db.params.password = \"$dbPassword\"/" ${prefix}application/configs/application.ini
sed -i '' -e "12s/.*/resources.db.params.host = \"$dbHost\"/" ${prefix}application/configs/application.ini
sed -i '' -e "19s/.*/security.csrfsalt = \"$csrfSalt\"/" ${prefix}application/configs/application.ini
sed -i '' -e "23s/.*/backoffice.security.passwordsalt = \"$backofficeSalt\"/" ${prefix}application/configs/application.ini
sed -i '' -e "24s/.*/frontend.security.passwordsalt = \"$frontendSalt\"/" ${prefix}application/configs/application.ini

# Modifying the migration file
echo "- Writing the credentials migration"
sed -i '' -e "12s/.*/         \$username = '$backofficeUsername';/" ${prefix}scripts/migrations/001-BackofficeUsers.php
sed -i '' -e "13s/.*/         \$password = sha1('${backofficeSalt}${backofficePassword}');/" ${prefix}scripts/migrations/001-BackofficeUsers.php
sed -i '' -e "14s/.*/         \$email = '$backofficeEmail';/" ${prefix}scripts/migrations/001-BackofficeUsers.php

# Create the log files
echo "- Creating log files"
touch "${prefix}logs/backoffice/flagflippers.log"
touch "${prefix}logs/backoffice/general.log"
touch "${prefix}logs/backoffice/mailer.log"
touch "${prefix}logs/frontend/flagflippers.log"
touch "${prefix}logs/frontend/general.log"
touch "${prefix}logs/frontend/mailer.log"
touch "${prefix}logs/frontend/gateway.log"

# Give read/write access to the logs and cache folders
echo "- Giving permissions to log and cache folders"
chmod -R 777 "${prefix}logs"
chmod -R 777 "${prefix}cache"

# Install the AkRabat migration tool
#echo "- Installing AkRabat migration tool"
#${prefix}bin/zf.sh --setup storage-directory
#${prefix}bin/zf.sh --setup config-file
#echo 'basicloader.classes.0 = "Akrabat_Tool_DatabaseSchemaProvider"' > ~/.zf.ini

# Run the migrations
echo "- Running the DB migrations"
${prefix}bin/zfs.sh update database-schema

echo -e "\nInstallation finished"
