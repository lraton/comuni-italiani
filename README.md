# Axio Studio Comuni

## Informazioni

Questo package Laravel include delle API utili per gestire le informazioni riguardanti i comuni italiani.

Supporta:

- Laravel 8
- Laravel 9
- Laravel 10
- Laravel 11
- Laravel 12
- Laravel 13

## Come funziona

Grazie a comode API è possibile ottenere informazioni su **CAP, città, province, regioni e zone d'Italia**.

| Endpoint | Metodo | Descrizione | Parametri |
| --- | --- | --- | --- |
| `/api/comuni/zones` | GET | Restituisce una lista di tutte le zone italiane | - |
| `/api/comuni/zones/{id}` | GET | Restituisce le informazioni di una determinata zona tramite il suo `id` | - |
| `/api/comuni/regions` | GET | Restituisce la lista di tutte le regioni italiane | - |
| `/api/comuni/regions/{id}` | GET | Restituisce le informazioni di una determinata regione tramite il suo `id` | - |
| `/api/comuni/provinces` | GET | Restituisce la lista di tutte le province italiane | `q` (query string) - filtra per nome dopo il terzo carattere di ricerca |
| `/api/comuni/provinces/{id}` | GET | Restituisce le informazioni di una determinata provincia tramite il suo `id` | - |
| `/api/comuni/provinces/{code}` | GET | Restituisce le informazioni di una determinata provincia tramite il suo `code` | - |
| `/api/comuni/cities` | GET | Restituisce la lista di tutti i comuni italiani | `q` (query string) - filtra per nome dopo il terzo carattere di ricerca |
| `/api/comuni/cities/{id}` | GET | Restituisce le informazioni di una determinata città tramite il suo `id` | - |
| `/api/comuni/zips` | GET | Restituisce la lista di tutti i CAP italiani | `q` (query string) - filtra per codice CAP (5 caratteri numerici) |
| `/api/comuni/zips/{id}` | GET | Restituisce le informazioni di un determinato CAP tramite il suo `id` | - |

## Installazione

Per installare il package eseguire:

```bash
composer require axiostudio/comuni-italiani
```

Per completare l'installazione è necessario eseguire le migration e aggiornare il database dei comuni:

```bash
php artisan migrate && php artisan comuni:update
```

### Laravel Sail

Se il progetto utilizza Laravel Sail:

```bash
./vendor/bin/sail artisan migrate && ./vendor/bin/sail artisan comuni:update
```

oppure, se è configurato l'alias `sail`:

```bash
sail artisan migrate && sail artisan comuni:update
```

## Personalizzazione

È possibile impostare tramite la variabile `.env` `COMUNI_MIDDLEWARES` del proprio progetto Laravel la lista dei middleware sotto cui verranno registrate le rotte del package.

Il separatore deve essere il carattere `,`.

**Nota !**

Se la variabile `COMUNI_MIDDLEWARES` non è presente, verrà applicato automaticamente solo il middleware `api`.

**Esempio**

```ini
# file .env progetto Laravel

COMUNI_MIDDLEWARES=api,auth:sanctum
```

In questo esempio tutte le [rotte](#come-funziona) verranno protette dai middleware:

- `api`
- `auth:sanctum`

## Esportazione

È possibile esportare nel proprio progetto il file di configurazione e le migration del package tramite:

```bash
php artisan vendor:publish --provider="Axiostudio\Comuni\ComuniServiceProvider"
```

### Laravel Sail

Con Laravel Sail:

```bash
./vendor/bin/sail artisan vendor:publish --provider="Axiostudio\Comuni\ComuniServiceProvider"
```

oppure:

```bash
sail artisan vendor:publish --provider="Axiostudio\Comuni\ComuniServiceProvider"
```

## Aggiornamento database comuni

Per aggiornare o importare i dati relativi a zone, regioni, province, comuni e CAP:

```bash
php artisan comuni:update
```

Con Laravel Sail:

```bash
sail artisan comuni:update
```

oppure:

```bash
./vendor/bin/sail artisan comuni:update
```

## Note

Per supporto o segnalazioni di bug utilizzare le Issue GitHub.

Per contribuire al progetto è possibile aprire una Pull Request specificando le modifiche o integrazioni effettuate.

## Credits

Questo pacchetto è stato creato ed è mantenuto da Axio Studio.

Maggiori informazioni: https://axio.studio
