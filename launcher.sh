#!/usr/bin/env sh
clear

echo "======================================"
echo " Vérification des droits liés a Docker "
echo "======================================"

docker version
PROCESS_1_STATUS=$?

if [ $PROCESS_1_STATUS -ne 0 ]; then
    printf "\033[0;31m Erreur docker n'est pas installé ou vous ne possédez pas le droit.\e[0m\n"
    exit 1
fi

while : ; do
echo ""
echo "======================================"
echo "Programme de chargement des containers"
echo "======================================"
echo "Quelle environement souhaitez-vous executer ?"
echo "1-SuperSet"
echo "2-Supervision"
echo "3-GLPI"
echo "4-Exit"

read -n 1 k <&1

if [[ $k = 4 ]] ; then
    printf "\nFin de programme\n"
    break
fi

if [[ $k = 1 ]] ; then
    echo ""
    if [ "$( docker container inspect -f '{{.State.Running}}' superset_superset )" == "true" ];
    then
        echo "Les containers sont deja en cours d'execution souhaitez vous faire ?"
        echo "1-Supprimer"
        echo "2-Arret"
        echo "3-Annuler"
        read -n 1 b <&1
        if [[ $b = 1 ]] ; 
        then
            printf "\nSuppression de la suite SuperSet\n"
            docker-compose -f ./superset/docker-compose.yml down
        fi
        if [[ $b = 2 ]] ; 
        then
            printf "\nArret de la suite SuperSet\n"
            docker-compose -f ./superset/docker-compose.yml stop
        fi
    else
        printf "\nLancement de la suite SuperSet\n"
        docker-compose -f ./superset/docker-compose.yml up -d
    fi
fi

if [[ $k = 2 ]] ; then
    echo ""
    if [ "$( docker container inspect -f '{{.State.Running}}' supervision_cadvisor )" == "true" ];
    then
        echo "Les containers sont deja en cours d'execution souhaitez vous faire ?"
        echo "1-Supprimer"
        echo "2-Arret"
        echo "3-Annuler"
        read -n 1 b <&1
        if [[ $b = 1 ]] ; 
        then
            printf "\nSuppression de la suite supervision\n"
            docker-compose -f ./supervision/docker-compose.yml down
        fi
        if [[ $b = 2 ]] ; 
        then
            printf "\nArret de la suite supervision\n"
            docker-compose -f ./supervision/docker-compose.yml stop
        fi
    else
        printf "\nLancement de la suite supervision\n"
        docker-compose -f ./supervision/docker-compose.yml up -d
    fi
fi

if [[ $k = 3 ]] ; then
    echo ""
    if [ "$( docker container inspect -f '{{.State.Running}}' GLPI_php )" == "true" ];
    then
        echo "Les containers sont deja en cours d'execution souhaitez vous faire ?"
        echo "1-Supprimer"
        echo "2-Arret"
        echo "3-Annuler"
        read -n 1 b <&1
        if [[ $b = 1 ]] ; 
        then
            printf "\nSuppression de la suite GLPI-lamp\n"
            docker-compose -f ./GLPI-lamp/docker-compose.yml down
        fi
        if [[ $b = 2 ]] ; 
        then
            printf "\nArret de la suite GLPI-lamp\n"
            docker-compose -f ./GLPI-lamp/docker-compose.yml stop
        fi
    else
        printf "\nLancement de la suite GLPI-lamp\n"
        docker-compose -f ./GLPI-lamp/docker-compose.yml up -d
    fi
fi

done
