# Como instalar o REDCap em um sistema Ubuntu - Passo a passo by Rafael Rueda

# Primeiro passo

> Baixar o arquivo zip REDCap atraves do site oficial "https://community.projectredcap.org"
> Rodar os seguintes comandos:

sudo apt update

sudo apt upgrade

## Instalar o Apache

sudo apt install apache2

> Para ver os perfis de aplicativo do Uncomplicated Firewall
sudo ufw app list

> Permitir trafego na porta 80, com o seguinte comando:

sudo ufw allow in "Apache"

> Verificar o status do UFW

sudo ufw status

## Instalar o MYSQL

sudo apt install mysql-server

> Configurar o MYSQL

sudo mysql

ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'password';
EXIT;

sudo mysql_secure_installation

## Instalar o PHP

sudo apt install php libapache2-mod-php php-mysql

php -v

# Segundo passo

> Extrair o arquivo REDCap, e copiar a pasta "redcap" para "/var/www/html". Supondo que a pasta descompactada esteja em downloads, use os seguintes comandos:

whoami

> Substituir "SEU_USUARIO_CHANGE_ME" pelo usuario do linux. Caso nao saiba, rodar o comando "whoami".

sudo cp -r /home/SEU_USUARIO_CHANGE_ME/Downloads/redcap14.3.0/redcap /var/www/html

sudo reboot

> Criar a base de dados

sudo systemctl stop mysql

sudo mkdir -p /var/run/mysqld

sudo chown mysql:mysql /var/run/mysqld

sudo mysqld_safe --skip-grant-tables &

mysql -u root

USE mysql;
UPDATE user SET authentication_string=PASSWORD('password') WHERE User='root';
FLUSH PRIVILEGES;
EXIT;

ps aux | grep mysql

> Matar todos os processos mysql ativos com o id obtido atraves do comando anterior

sudo kill [id]

> Fazer para todos os processos mysql ativos
> Depois startar o mysql de novo

sudo systemctl start mysql

sudo mysql -u root -p

> Coloque a senha "password"

CREATE DATABASE redcap_db;
USE redcap_db;

# Terceiro passo

> Configurar o arquivo database.php na pasta do redcap

sudo nano /var/www/html/redcap/database.php

> Alterar para as seguintes informacoes:

 $hostname = 'localhost';
 $db = 'redcap_db';
 $username = 'root';
 $password = 'password';

 $salt = 'redcap_salt'

# Quarto passo

> Acessar "http://localhost/redcap"
> Clicar no botao de instalacao
> Configure corretamente na pagina que aparece com as configuracoes desejadas
> Copie o codigo que aparece e digite os seguinte comandos no terminal:

sudo mysql -u root -p

> Coloque a senha "password"
> Cole o codigo gigante copiado do site e aguarde

EXIT;

> Clique no botao da pagina no site para ser redirecionado para a pagina de checagem.

# Quinto passo

sudo apt-get install php-xml

sudo reboot