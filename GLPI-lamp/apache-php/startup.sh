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
  ps aux |grep apache2 |grep -q -v grep
  PROCESS_1_STATUS=$?
  ps aux |grep vsftpd |grep -q -v grep
  PROCESS_2_STATUS=$?
  ps aux |grep cron |grep -q -v grep
  PROCESS_3_STATUS=$?
  # If the greps above find anything, they exit with 0 status
  # If they are not both 0, then something is wrong
  if [ $PROCESS_1_STATUS -ne 0 -o $PROCESS_2_STATUS -ne 0 -o $PROCESS_3_STATUS -ne 0 ]; then
    echo "One of the processes has already exited."
    exit 1
  fi
done
