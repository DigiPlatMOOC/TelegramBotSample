#!/bin/bash

#TODO: insert the full path of your php script (e.g. PHP_SCRIPT="/path/to/directory/hook.php")
PHP_SCRIPT="hook.php"

if [ -z "$PHP_SCRIPT" ]; then

	echo "WARNING: Setup the actual PHP_SCRIPT path, please"
	exit 0
fi


usage(){
	echo "usage: $0 [ -r ] [ -h ] "
}

ACTION="SETUP"

while getopts rh option
do
    case $option in
            (r)
                    ACTION='remove';;
            (h)
                    usage
		    exit;;
            (*)
                    usage
                    exit;;
    esac
done


if [ "$ACTION" = "SETUP" ]; then
 
	#write out current crontab
	crontab -l > mycron

	#echo new cron into cron file
	echo "* * * * * php $PHP_SCRIPT" >> mycron

else
	#remove PHP_SCRIPT command
	crontab -l | grep -v $PHP_SCRIPT > mycron
fi

#install new cron file
crontab mycron
rm mycron
