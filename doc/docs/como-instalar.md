# Instalação [Login Cidadão](https://github.com/PROCERGS/login-cidadao)
Esta documentação foi baseada em um servidor GNU/Linux Debian 8.1. Se sua intenção é instalar em sistema de base diferente algumas adaptações podem ser necessárias. 

## Server User
Para instalar a aplicação é necessário ter acesso a dois usuários: um usuário padrão e um usuário com poderes de sudo. Você pode usar o usuário padrão de costume de seu servidor e o root, mas recomendamos criar um usuário só para o gerenciamente do Login Cidadão, facilitando assim o controle e o registro de logs do sistema. 
```bash
    // logado como root, crie o novo user:
    # useradd --create-home --groups sudo -s /bin/bash login-cidadao
    // Insira uma senha para o novo user
    # passwd login-cidadao
``` 

## Instalando Dependências
Para que o Login Cidadão funcione corretamente será necessário que estejam instaladas as seguintes dependências: 
* Apache ou Nginx
* PHP >=5.4
* memcached
* postgres ou mysql
* composer
* node.js
* PHP Extensions
  * php5-curl
  * php5-intl
  * php5-mysql ou php5-pgsql ou integração de base de dados de sua preferência
  * php5-memcache (Também é possível usar php5-memcached mas será necessário mudar algumas classes de Memcache para Memcached)

```bash
    // Atualize a lista de pacotes do seu servidor
    $ sudo apt-get update

    // Instale o servidor Nginx 
    $ sudo apt-get install nginx
    //obs: se o apache estiver instalado ele pode dar conflito com o Nginx. 
    // Fique atento para a necessidade de desinstalá-lo. 

    //Caso tenha algum gerenciador de bases de dados(mysql ou postgres) você poderá usá-lo. 
    //Se não tiver, instale o banco que quer usar. Optamos aqui por postgres
    $ sudo apt-get install postgresql

    // Instale o git para auxiliá-lo no processo de obtenção do código da aplicação
    $ sudo apt-get install git

    // Instalando pacote do server memcached 
    $ sudo apt-get install memcached
    
    // Instalando pacotes do php. 
    // Observe que aqui vamos optar por usar o postgres, mas é possível usar mysql sem problemas
    $ sudo apt-get install php5 php5-cli php5-curl php5-intl php5-pgsql php5-memcache
       
    // Instalando nodejs
    $ sudo curl -sL https://deb.nodesource.com/setup_5.x | bash -
    $ sudo apt-get install --yes nodejs

```
## Instalando Composer

O Login Cidadão usa o Composer, um gerenciador de dependências PHP. Ele permite que você declare bibliotecas como dependências no projeto que irá gerenciar. Para saber mais, acesse: https://getcomposer.org/doc/00-intro.md

Para instalar:

```bash
    // Instalando Composer
    $ sudo curl -sS https://getcomposer.org/installer | php
    $ sudo mv composer.phar /usr/local/bin/composer

```

## Obtendo o Login Cidadão 

Após instalar as dependências, clone o repositório da aplicação e mude as permissões dos arquivos para operar com apache ou nginx. Recomendamos fazer isso com o usuário criado e dentro de uma estrutura de diretórios padrão /var/www/login-cidadao, mas é possível adaptar para qualquer contexto. 
```
    //Logado como login-cidadao user, vá para o diretório /var/www
    $ cd /var/www
    // Dentro do diretório, clone o repositório da aplicação
    $ sudo git clone https://github.com/redelivre/login-cidadao.git


```

## Parametrizando a aplicação - pré-instalação

Agora vamos instalar as dependências. Após esse processo você deverá preencher os parametros relativos a sua instalação. Portanto é necessário que você tenha as seguintes informações em mãos: 

#### Informações de acesso ao banco de dados
* Endereço do host da base de dados
* Porta de acesso
* Usuário
* Senha
* Schema (Nome do banco)

#### Loadbalance
* IPs dos servidores de load balance (Para mais informações consulta a [documentação do Symfony](http://symfony.com/doc/2.8/cookbook/request/load_balancer_reverse_proxy.html) sobre o assunto).

#### Acesso de desenvolvimento e monitoramento
* IPs com acesso ao ambiente de desenvolvimento
* IPs com acesso as páginas de monitoria (endpoints)

#### Memcache e sessões
* Endereços (IP e porta) usados no memcache
* Prefixo da sessão
* Tempo de vida da sessão

#### Envio de email
* Essas informações variam de acordo com o tipo de serviço escolhido. 
Ver [Documentação Symfony](http://symfony.com/doc/2.8/cookbook/email/email.html)

#### Secret
Gere uma string secreta para composição de cifragem

Ex.:

```
    secret:            CrieSuaStringAleatoria
```

#### Chaves de APIs de terceiros
* Facebook
* Google
* Twitter
* Recaptcha

#### Dominio e contatos
* domínio da instalação
* Endereço do remetente de emails da aplicação (Ex.: noreply@seu-dominio.com)

## Acertando permissões de acesso

É necessário que as permissões do diretório estejam de acordo com as permissões do Nginx para que os arquivos sejam acessados publicamente. 

```
    // Entre no diretório criado 
    $ cd login-cidadao
    // Mude a permissão dos arquivos. Pode ser necessário fazer isso no final do processo novamente.
    $ sudo chown -R login-cidadao:www-data *
    $ sudo chown -R login-cidadao:www-data .*
```
Depois de efetuar as mudanças no permissionamento dos arquivos, aplique o comando de listagem de arquivos no diretório para verificar se foi aplicado com sucesso. Seu diretório deve estar assim:

```
	// aplicando o comando de listagem de arquivos e permissões, você deverá ver algo como isso:

	login-cidadao@localhost:/var/www/login-cidadao$ ls -la
	drwxr-xr-x  9 login-cidadao www-data   4096 Dec 15 20:32 .
	drwxr-xr-x  3 login-cidadao www-data   4096 Dec 15 20:26 ..
	drwxr-xr-x  7 login-cidadao www-data   4096 Dec 15 20:46 app
	drwxr-xr-x  2 login-cidadao www-data   4096 Dec 15 20:26 batch
	drwxr-xr-x  2 login-cidadao www-data   4096 Dec 15 20:33 bin
	-rw-r--r--  1 login-cidadao www-data     42 Dec 15 20:26 .bowerrc
	-rw-r--r--  1 login-cidadao www-data   4320 Dec 15 20:26 composer.json
	-rw-r--r--  1 login-cidadao www-data 159040 Dec 15 20:26 composer.lock
	drwxr-xr-x  8 login-cidadao www-data   4096 Dec 15 20:26 .git
	-rw-r--r--  1 login-cidadao www-data    315 Dec 15 20:26 .gitignore
	-rwxr-xr-x  1 login-cidadao www-data   3991 Dec 15 20:26 install.sh
	-rw-r--r--  1 login-cidadao www-data  34521 Dec 15 20:26 LICENSE
	-rw-r--r--  1 login-cidadao www-data    332 Dec 15 20:26 lvp_mock.bat
	-rw-r--r--  1 login-cidadao www-data   3602 Dec 15 20:26 README.md
	drwxr-xr-x  4 login-cidadao www-data   4096 Dec 15 20:26 src
	-rw-r--r--  1 login-cidadao www-data    106 Dec 15 20:26 .travis.yml
	-rw-r--r--  1 login-cidadao www-data   1308 Dec 15 20:26 UPGRADE-2.2.md
	-rw-r--r--  1 login-cidadao www-data   1962 Dec 15 20:26 UPGRADE-2.3.md
	-rw-r--r--  1 login-cidadao www-data   8495 Dec 15 20:26 UPGRADE.md
	-rw-r--r--  1 login-cidadao www-data   2470 Dec 15 20:26 Vagrantfile
	drwxr-xr-x 38 login-cidadao www-data   4096 Dec 15 20:46 vendor
	drwxr-xr-x  5 login-cidadao www-data   4096 Dec 15 20:33 web

```
## Baixando dependencias via Composer

  // Certifique-se de estar dentro do diretório raiz do projeto
  $ cd /var/www/login-cidadao
  // Quando estiver rodando o composer, o arquivo parameters.yml (arquivo de parametros da instância) 
  // será preenchido automaticamente. Fique atento as informações inseridas
  // Instalando as dependências
  $ composer install

## Parametrizando manualmente

Caso a parametrização via composer seja interrompida ou tenha dados que precisem ser completados, você pode alterar manualmente no arquivo `parameters.yml` a partir do template contido em `app/config/parameters.yml.dist`. 

## Configurando Ngix

## Certificado SSL

Ver "Usando Certificado SSL"

## Envio de email

## Configurações de serviços 

* Ver Configurações



```
	// Copiando o arquivo de template para o arquivo default
	$ sudo cp /var/www/login-cidadao/app/config/parameters.yml.dist /var/www/login-cidadao/app/config/parameters.yml
```


Fique atento aos seguintes pontos: 

3.1. Conectando a base de dados: 

Se você estiver usando 


3.2. Configurações memcached

3.3. Configurações de envio de email (smtp)

3.4. Configurações de token

    locale:            en
    secret:            ThisTokenIsNotSoSecretChangeIt


    locale:            pt_BR
    secret:            cALL-g83trinzafederuserall#set900@set8**



5. Cheque os requisitos do PHP 

Verifique se todas os requisitos estão sendo cumpridos antes de iniciar a instalação
    `php app/check.php`
 

## Configurando base de dados

Crie um usuário no postgres e depois uma base. Sugerimos usar o mesmo nome. 

```
  $ sudo -u postgres createuser -d login-cidadao
  $ createdb login-cidadao
```

7. Se a verificação for bem sucedida inicie a instalação
    `./install.sh`
 
## Arquivo de configuração `parameters.yml`
 
`locale:` -> substitua pelo seu locale (ex. pt_BR)
 
`secret:` -> substitua por uma longa cadeia de letras, números e símbolos
 
`site_domain:` -> substitua pelo seu domínio/subdomínio
 
`recaptcha_public_key:` e `recaptcha_private_key:` -> gere essas chaves em https://www.google.com/recaptcha/
 
`registration.cpf.empty_time:` e `registration.email.unconfirmed_time:` -> define quanto tempo deve ser dado para que o usuário confirme o CPF e o email, respectivamente
 
`brute_force_threshold:` -> quantas tentativas devem ser toleradas antes de considerar um ataque de força bruta
 

 
## Configurando Nginx
 
Copie o scritp padrão para o repositório de seu nginx

```
    $ sudo cp /var/www/login-cidadao/batch/nginx-login-cidadao.conf /etc/nginx/sites-available/login-cidadao.conf
```

Faça as alterações necessárias! 



  //Faça um link simbólico para o arquivo
  $ sudo ln -s /etc/nginx/sites-available/login-cidadao.conf /etc/nginx/sites-enabled/login-cidadao.conf
```

 
```
<VirtualHost *:80>
    ServerName sub.dominio.com.br
    ServerAdmin usuario@email
 
    DocumentRoot /var/www/login-cidadao/web
 
    <Directory / >
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Order allow,deny
        allow from all
    </Directory>
 
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```
 
* Em `DocumentRoot` é preciso apontar para o diretório `web`, neste exemplo o caminho completo é `/var/www/login-cidadao/web`.
 
* ServerName deve ser preenchido com o domínio (ex. dominio.com.br) ou subdomínio completo (ex. sub.dominio.com.br)
 
### [Primeiros passos pós-instalação](id:pos-instalacao)
 
1. Adicione os seguintes aliases ao seu arquivo `.bashrc`  
    `alias prod='php app/console --env=prod'`  
    `alias dev='php app/console --env=dev'`
 
2. Atualize o perfil do terminal  
    `source ~/.bashrc`
    * Obs.: Etapa desnecessária para logins futuros já que o .bashrc será executado no processo de login.
 
3. Processe e ative todos os assets  
    `prod assets:install`  
    `prod assetic:dump`
 
4. Dar poderes de super administrador para o primeiro usuário  
    `prod fos:user:promote <username> ROLE_SUPER_ADMIN`
 
    * Obs. 1: Substitua "<username>" pelo nome do usuário como mostrado na área superior direita da página, geralmente o que precede o '@' do email usado na hora da criação do usuário.
 
    * Obs. 2: Para confirmar visualmente o novo papel de super administrador faça um logout e depois um login. Junto ao nome deverá haver um campo 'impersonate', ver esse campo é a confirmação.
 
## Navegando em modo de desenvolvimento
 
Adicione `/app_dev.php` na URL.
 
## Alguns comandos práticos
 
Assume-se que as estapas 1 e 2 dos [Primeiros passos pós-instalação](#pos-instalacao) tenham sido cumpridos para seguir estes comandos.
 
* Limpar o cache  
    `prod cache:clear`  
    `dev cache:clear`  
    se não funcionar, em última instância use  
    `rm -rf app/cache/*`
* Criar ou atualizar os assets  
    `prod assets:install`  
    `prod assetic:dump`
* Criar ou atualizar os vendors (útil, por exemplo, quando se muda de branch)  
    `composer install`
 
## Adicionando serviços
 
em breve
 
## Integrando com o Mapas Culturais
 
```
'auth.provider' => 'OpauthLoginCidadao',
'auth.config' => array(
    'client_id' => 'minha_chave_publica',
    'client_secret' => 'minha_chave_privada',
    'auth_endpoint' => 'https://sub.dominio/oauth/v2/auth',
    'token_endpoint' => 'https://sub.dominio/oauth/v2/token',
    'user_info_endpoint' => 'https://sub.dominio/api/v1/person.json'
),
```
* Obs. 1: As chaves pública e privada são geradas na adição do serviço.  
* Obs. 2: substituir o domínio/subdomínio das três últimas linhas.

