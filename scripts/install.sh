#!/bin/bash

# Get current foldername
currentFolder=`basename \`pwd\``

# Check if we are executing the script inside scripts folder or in the root folder
if [ $currentFolder == "scripts" ] ; then
    prefix='../'
else
    prefix='./'
fi

# Create a few folders
mkdir "${prefix}logs"
mkdir "${prefix}logs/backoffice"
mkdir "${prefix}logs/backoffice/missing_translations"
mkdir "${prefix}logs/frontend"
mkdir "${prefix}logs/frontend/missing_translations"
mkdir "${prefix}cache"
mkdir "${prefix}public/frontend/tmp"

# Copy the config files
cp "${prefix}application/configs/environment.example.php" "${prefix}application/configs/environment.php"
cp "${prefix}application/configs/application.example.ini" "${prefix}application/configs/application.ini"

# Create the default log files
touch "${prefix}logs/backoffice/flagflippers.log"
touch "${prefix}logs/backoffice/general.log"
touch "${prefix}logs/backoffice/mailer.log"
touch "${prefix}logs/frontend/flagflippers.log"
touch "${prefix}logs/frontend/general.log"
touch "${prefix}logs/frontend/mailer.log"
touch "${prefix}logs/frontend/gateway.log"

# Give read/write access to the logs and cache folders
chmod -R 777 "${prefix}logs"
chmod -R 777 "${prefix}cache"

#Install the AkRabat migration tool
${prefix}bin/zf.sh --setup storage-directory
${prefix}bin/zf.sh --setup config-file
echo 'basicloader.classes.0 = "Akrabat_Tool_DatabaseSchemaProvider"' > ~/.zf.ini

#Run the migrations
${prefix}bin/zf.sh update database-schema