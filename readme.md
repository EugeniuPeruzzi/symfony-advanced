-------------------   SOLO PER MACCHINE NUOVE   ------------------- 
- Installare SCOPP e poi installare Symfony CLI

`scopp install symfony-cli`

- Vediamo se il sistema è pronto:
 
`symfony check:requirements`

controlliamo se abbiamo PHP installato usando il comando `php -v` per verificare la versione e se è effettivamente installato

-------------------   SOLO PER MACCHINE NUOVE   -------------------  

********

### Creazione del nuovo progetto

Tipica Web APP
- `symfony new {project-name} --webapp`

OR

-   `composer create-project symfony/skeleton {project-name}`
    `cd {project-name}`
    `composer require webapp`

una volta installato tutto verifichiamo di essere all'interno della cartella `cd {nome progetto}` e poi  `symfony server:start` se non usiamo 'xaamp' oppure 'phplauncer' avviamo il server php chiamando: `php -S 127.0.0.1:8000 -t public`


**BEST PRACTICE**
`symfony check:security` ------ >  se il server gira andiamo a vedere se è tutto regolare al livello di sicurezza

*********

### Copiare un progetto esistente e attivare Symfony

classica procedura per clonare la cartella su GitHub, poi nella cartella del progetto `composer install` poi modifichi il classico solito file .env 

*********

### Maker bundle

Per velocizzare la creazione dei controller o file in generale, Symfony lascia a disposizione i cosiddetti Bundle:

- bisogna installarli con: `composer require --dev symfony/maker-bundle`
    - vedere tutti i comandi : `symfony console list make`
    - in breve per fare un controller: `symfony console make:controller` o altri.


*******

### Profiler Pack
__*ATTENZIONE*__ - NON VA INSTALLATO NELLA PRODUZIONE!!!!

Ti genera una toolbar che di da informazioni sui stati della tua pagina che stai creando con diversi dati di telemetria.

per installarlo: `composer require --dev symfony/profiler-pack`

*******

### Installazione di Doctrine

Doctrine è un ORM pack che serve per la comunicazione con il database, quindi al posto di usare le classiche SQL query sarà Doctrine ad occuparsi di tutto ciò, in Laravel -> Eloquent in Java -> Hibernate.

__*TIP*__ - DOCTRINE È GIÀ INSTALLATO DI DEFAULT COME APP IN SYMFONY 7+ 
per installare: `composer require symfony/orm-pack`

una volta installato l'orm pack creiamo il file: `docker-compose.yml` e inseriamo dentro questo snippet per dirgli i dati del db da utilizzare.

```yaml
version: "3.8"
services:
  mysql:
    image: mariadb:10.8.3
    # Uncomment below when on Mac M1
    # platform: linux/arm64/v8
    command: --default-authentication-plugin=mysql_native_password
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: root
    ports:
      - 3306:3306
  adminer:
    image: adminer
    restart: always
    ports:
      - 8080:8080

```

fatto tutto, fai partire Docker Desktop oppure usa l'estensione di VSC e fai partire il comando: `docker compose up`

> ### __*Errore*__ Docker andava in confusione con i file 
> - creati da Symfony `compose.yaml`
> - file creato da me `docker-compose.yml`. 
> Azione presa => cancellazione di `compose.yaml`

Una volta avviato il server, impostiamo il file .env quindi impostiamo DATABASE_URL: 

```
#DATABASE_URL="{tipo di db: mysql/postgre/sqlite ecc..}://{NOMEDATABASE}:{PASSWORD DATABASE}@127.0.0.1:{porta del database dichiarate nel file di docker}/{nome db}?serverVersion={versione del db usate sempre specificata in docker-compose}&charset=utf8mb4"
```
Poi andiamo nel file doctrine.yaml e decomentiamo la voce `server_version` e mettiamo la versione del server che usiamo. Fatto tutto questo creiamo il database
`symfony console doctrine:database:create`.

******

### Creazione delle entità/tabelle

- Creare le entità/TABELLE utilizziamo il comando `symfony console make:entity`
    - Seguiamo tutte le indicazioni per creare le colonne, farle nullable e attribuirne il tipo (stringa, numero, data ecc...)
- Creata la tabella con le sue colonne, andiamo a fare la migration usando il comando: `symfony console make:migration`
- Fatto il file di migrazione, lo spediamo al db con: `symfony console doctrine:migrations:migrate`


##### Debugger __*TIP*__
`symfony console doctrine:migrations:status`

********

### Fixture Bundle
- Fixture è l'equivalente di faker, per installarla diamo comando: `composer require --dev orm-fixtures`
    - Una volta inserite le fixture, carichiamole con il comando: `symfony console doctrine:fixture:load`

********

### One to one relation

1. Creiamo l'entità `symfony console make:entity`
    - Poi inseriamo il nome dell'entità esistente debole
    - Ci verrà chiesto il nome della nuova colonna e la tipologia di relazione che in questo caso inseriamo `One to one` 
        - Poi seguiamo tutti gli input della console.
2. Al termine si fa la migrazione: `symfony console doctrine:migrations:migrate`

*******

### One to many relation

1. Creiamo l'entità `symfony console make:entity`
    - Poi inseriamo il nome dell'entità esistente debole
    - Ci verrà chiesto il nome della nuova colonna e la tipologia di relazione che in questo caso inseriamo `One to Many` 
        - Poi seguiamo tutti gli input della console.
2. Al termine si fa la migrazione: `symfony console doctrine:migrations:migrate`


********

### Personalizzazione dei comandi in console

1. `symfony console make:command`
    - Ci verrà chiesto dalla console come procedere e che nome attribuire come ad esempio: `app:create-user`
2. Verrà generato un file nella cartella `src/Command/{nome del comando}.php` dove andremo a personalizzare il tutto 

- __*TIP*__ Vedi repo src/Command/CreateUserCommand.php

********

### Creazione di Voter

1. `symfony console make:voter`
    - Il Voter è un meccanismo in Symfony per gestire l'autorizzazione degli utenti in base a determinati criteri.
2. Verra generato il file dentro security/voter/{NomeFileVoter.php}




















