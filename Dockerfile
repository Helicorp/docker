# installation a partir de l'image de mysql

FROM mysql:latest

# message de bienvenue

RUN echo "Bienvenue dans l'outil de création de limage LAMP" \
&& echo "powered by" \
&& echo ".........../.../...+------......" \
&& echo "........../.../....|............" \
&& echo "........./---/.....|............" \
&& echo "......../.../......|............" \
&& echo "......./.../.......+------......"

# installation des packets et creation des repertoires et fichier de logs

RUN apt-get update -yq \
&& apt-get install curl gnupg -yq \
&& apt-get install apache2 -yq \
&& apt-get install php -yq \
&& apt-get install nano -yq \
&& apt-get install vsftpd -yq \
&& apt-get clean -y \
&& mkdir /var/log/vsftpd && touch /var/log/vsftpd/error.log \
&& mkdir /var/log/cron && touch /var/log/cron/error.log

# copie des fichier de configuration

COPY apache2.conf etc/apache2/
COPY startup.sh startup.sh
# ouverture des ports

EXPOSE 3306
EXPOSE 80
EXPOSE 20-21
EXPOSE 65500-65515

# creation du mot de passe de la base de donnée

ENV MYSQL_ROOT_PASSWORD=root

# demande d'autostart des packets

CMD chmod 777 /startup.sh && /startup.sh
