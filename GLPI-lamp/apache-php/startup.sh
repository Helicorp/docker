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

while sleep 20; do
  ps aux |grep apache2 |grep -q -v grep
  PROCESS_1_STATUS=$?
  # If the greps above find anything, they exit with 0 status
  # If they are not both 0, then something is wrong
  if [ $PROCESS_1_STATUS -ne 0 ]; then
    echo "One of the processes has already exited."
    exit 1
  fi
done
