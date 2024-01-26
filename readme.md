-------------------   SOLO PER MACCHINE NUOVE   ------------------- 
- Intallare SCOPP e poi intstallare Symfony CLI

`scoop install symfony-cli`

- Vediamo se il sistema e pronto:
 
`symfony check:requirements`

controliamo se abbiamo PHP installato usando il commando `php -v` per verificare la versione e se effetivamente e installato

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


**BEST PRACTIVE**
`symfony check:security` ------ >  se il serve gira andiamo a vedere se e tutto regolare al livello di sicurezza

*********

### Copiare un projetto esistente e attivare Sympfony

classica procedure per clonare la cartella su git hub, poi nella cartella del progetto `composer install` poi modifichi il classico solito file .env 

*********

### Maker bundle

Per velocizzare la crezione dei controller o file in generale, symfony lascia a disposizione i cosidetti Bundle:

- bisogna installarli con : `composer require --dev symfony/maker-bundle`
    - vedere tutti i comandi : `symfony console list make`
    - in bereve per fare un controller: `symfony console make:controller` o altri.


*******

### Profiler Pack
__*!ATTENTION*__ - NON VA INSTALLATO NELLA PRODUZIONE!!!!

Ti genera una toolbar che di da informzioni sui stati della tua pagina che stai creando con diversi dati di telimetria.

per installarlo : `composer require --dev symfony/profiler-pack`

*******

### Installazione di doctrine

Doctrine e un ORM pack che serve per la comunicazione con il database, quindi al posto di usare le classiche sql querry sara doctrine ad occuparsi di tutto cio, in Laravel -> Eloquent in Java -> Hibernate.

__*!TIP*__ - DOCTRINE E GIA INSTALLATO DI DEFAUL COME APP IN SYMFONY 7+ 
per installare: `composer require symfony/orm-pack`

una volta installato l'orm pack creamo il file : `docke-compose.yml` e inseriamo dentro questo snipet per dirgli i dati del db da utilizzare.

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

fatto tutto, fai partire docker desktop oppure usa estensione di vsc e fai partire il comando : `docker compose up`

> ### __*errore*__ Docker andava in confusione con i file 
> - creati da symfony `compose.yaml`
> - file creato da me `docker-compose.yml`. 
> azione presa => cancellazione di `compose.yaml`

una volta avviato il server impostiamo il file .env quindi impostiamo DATABASE_URL: 
```
#DATABASE_URL="{tipo di db: mysql/postgre/sqlite ecc..}://{NOMEDATABASE}:{PASSWORD DATABASE}@127.0.0.1:{porta del database dichiarate nel file di docker}/{nome db}?serverVersion={versione del db usate sempre specificata in docker-compose}&charset=utf8mb4"
```
poi andiamo nel file doctrine.yaml e decomentiamo la voce `server_version` e mettiamo la versione del server che usiamo fatto tutto questo creamo il data base
`symfony console doctrine:database:create`.

******

### Creazione delle entita/tabelle

- creare le entita/TABELE utiliaziamo il comando `symfony console make:entity`
    - segiamo tutte le indicazioni per creare le colone farle nullable e atribuirne il tipo (stringa, numero, data ecc...)
- creata la tabella con le sue collone andiamo a fare la migration usanto il commando :`symfony console make:migration`
- fatto il file di migrazione lo spediamo al db con : `symfony console doctrine:migrations:migrate`


##### Debuger __*TIP*__
`symfony console doctrine:migrations:status`

********

### Fixture Bundle
- Fixture e l'equivalente di faker, per isntallarla diamo comando : `composer require --dev orm-fixtures`
    - una volta inserite le fixture carichiamole con il comando: `symfony console doctrine:fixture:load`

********

### One to one relation

1. Creamo l'entita `symfony console make:entity`
    - poi inseriamo il nome dell'entita esistente debole
    - Ci verra chiesto il nome della nuova collona e la tipologgia di relazione che in qeusto caso inseriamo `One to one` 
        - poi seguiamo tutti gli input della console.
2. Al termine si fa la migrazione: `symfony console doctrine:migrations:migrate`

*******

### One to many relation

1. Creamo l'entita `symfony console make:entity`
    - poi inseriamo il nome dell'entita esistente debole
    - Ci verra chiesto il nome della nuova collona e la tipologgia di relazione che in qeusto caso inseriamo `One to Many` 
        - poi seguiamo tutti gli input della console.
2. Al termine si fa la migrazione: `symfony console doctrine:migrations:migrate`


********

### Personalizzazione dei commandi in console

1. `symfony console make:command`
    - ci verra chiesto dalla console come procedere e che nome atribuirli come ad esempio: `app:create-user`
2. Verra generato un file nella cartella `src/Command/{nome del commando}.php` dove andremo a personalizzare il tutto 

- __*TIP*__ Vedi repo src/Command/CreateUserCommand.php



















