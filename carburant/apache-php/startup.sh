 #! /bin/bash

#demarrage du service apache2
service apache2 start
status=$?
echo $status

if [ $status -ne 0 ]
then
        echo 'erreur apache est casse'
        exit $status
fi

#demarrage du service vsftpd
service vsftpd start
status=$?
echo $status

if [ $status -ne 0 ]
then
        echo 'erreur du serveur ftp'
        exit $status
fi

#demarrage du service cron
service cron start
status=$?
echo $status

if [ $status -ne 0 ]
then
        echo 'erreur du service cron'
        exit $status
fi 

while sleep 20; do
  service apache2 status
  PROCESS_1_STATUS=$?
  service vsftpd status
  PROCESS_2_STATUS=$?
  service cron status
  PROCESS_3_STATUS=$?
  # If the greps above find anything, they exit with 0 status
  # If they are not both 0, then something is wrong
  if [ $PROCESS_1_STATUS -ne 0 || $PROCESS_2_STATUS -ne 0 || $PROCESS_3_STATUS -ne 0]; then
    echo "Un processus a crash."
    exit 1
  fi
done
