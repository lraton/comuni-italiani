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

Grazie a comode API è possibile ottenere informazioni su **CAP, città, province, regioni e zone d'Italia**, con supporto avanzato per il **filtraggio a cascata (cross-filtering)** su tutte le risorse.

| Endpoint | Metodo | Descrizione | Parametri opzionali |
| --- | --- | --- | --- |
| `/api/comuni/zones` | GET | Restituisce una lista di tutte le zone italiane | - |
| `/api/comuni/zones/{id}` | GET | Restituisce le informazioni di una determinata zona tramite il suo `id` | - |
| `/api/comuni/regions` | GET | Restituisce la lista delle regioni italiane | `q` (nome regione), `prov` / `province` (sigla/nome/ID provincia) |
| `/api/comuni/regions/{id}` | GET | Restituisce la regione con zone, province, città e CAP associati | - |
| `/api/comuni/provinces` | GET | Restituisce la lista delle province italiane | `q` (nome o codice sigla provincia), `region_id` (ID regione), `region` (nome regione) |
| `/api/comuni/provinces/{id}` | GET | Restituisce le informazioni di una provincia tramite il suo `id` | - |
| `/api/comuni/provinces/{code}` | GET | Restituisce le informazioni di una provincia tramite il suo `code` (es. `RM`) | - |
| `/api/comuni/cities` | GET | Restituisce la lista dei comuni italiani | `q` (nome città), `prov` / `province` (sigla/nome/ID provincia), `region_id` (ID regione), `region` (nome regione) |
| `/api/comuni/cities/{id}` | GET | Restituisce la città con provincia, regione e relativi CAP | - |
| `/api/comuni/zips` | GET | Restituisce la lista dei CAP italiani | `q` (prefisso o codice CAP), `city_id` (ID città), `city` (nome città), `prov` (sigla/nome provincia), `region_id` (ID regione) |
| `/api/comuni/zips/{id}` | GET | Restituisce le informazioni di un determinato CAP tramite il suo `id` | - |

---

## Esempi di Filtraggio a Cascata (Cross-Filtering)

### 1. Filtrare i comuni per Regione o Provincia
```http
GET /api/comuni/cities?region_id=12&q=Rom
GET /api/comuni/cities?prov=RM&q=Roma
```

### 2. Filtrare le province per Regione
```http
GET /api/comuni/provinces?region_id=12
GET /api/comuni/provinces?region=Lazio
```

### 3. Filtrare i CAP per Comune o Provincia
```http
GET /api/comuni/zips?city_id=58091
GET /api/comuni/zips?prov=RM&q=001
```

---

## Caching e Performance

Tutte le query memorizzate in cache utilizzano strutture array native (`->toArray()`). Questo previene problemi di deserializzazione `__PHP_Incomplete_Class` quando il package interagisce con driver di cache come Redis o Memcached.

---

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

---

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

In questo esempio tutte le rotte verranno protette dai middleware:

- `api`
- `auth:sanctum`

---

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

---

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

---

## Note

Per supporto o segnalazioni di bug utilizzare le Issue GitHub.

Per contribuire al progetto è possibile aprire una Pull Request specificando le modifiche o integrazioni effettuate.

## Credits

Questo pacchetto è stato creato ed è mantenuto da Axio Studio.

Maggiori informazioni: https://axio.studio
