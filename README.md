# wenex-platform/php-sdk

PHP SDK for the Wenex platform.

- It wraps a single pre-configured [Guzzle](https://docs.guzzlephp.org/) client and
exposes each platform domain (auth, identity, financial, …) as a lazily-built
service aggregator. All reusable HTTP plumbing (the `RestfulService` CRUD base,
`GraphqlService`, enums, models, crypto helpers) comes from the companion package
`wenex-platform/php-sdk-common`, which lives in its **own repository** and is
**not tracked by this repository** — `packages/common/` is a local, gitignored
working copy of it.
  ## Requirements
  - PHP >= 8.1, with Composer
  - Git, with SSH read access to the private `php-sdk-common` repository

## Install

```bash
git clone <this-repo-url>
cd php-sdk

# Bring in the private common package (kept out of version control on purpose).
# Mirrors the platform-sdk flow: a stage (staging|production) and the wenex
# domain are mandatory — the package is cloned from git@gitlab.<domain>:wenex/…
composer common:clone -- staging wenex.org

composer install
```

`packages/common` is gitignored and wired as a local Composer path repository
(symlinked into `vendor/`), so `composer install` resolves it from disk — no
package registry is involved.

Keep it current with:

```bash
composer common:update -- staging wenex.org
```

To develop the common package in place, work directly inside `packages/common`
(it remains a standalone git checkout of `php-sdk-common`).

## Usage

Auth is caller-managed: you build a Guzzle client with the base URI and
`Authorization` header, then hand it to the SDK.

```php
use GuzzleHttp\Client;
use WenexPlatform\PhpSdk\Platform;

$httpClient = new Client([
    'base_uri' => 'https://api.example.com/',
    'headers'  => ['Authorization' => 'Bearer ' . $accessToken],
    'timeout'  => 30,
]);

$platform = Platform::build($httpClient);
```

### Obtaining a token

The password grant requires your client/app credentials. `response_type`
must be `'token'` — the platform otherwise defaults password grants to the
authorization-code flow and returns no bearer token.

```php
$response = $platform->getAuth()->getAuths()->token([
    'grant_type'    => 'password',
    'client_id'     => $clientId,      // platform-issued client id
    'client_secret' => $clientSecret,
    'app_id'        => $appId,
    'domain'        => 'example.com',
    'username'      => 'user@example.com',
    'password'      => 'secret',
    'response_type' => 'token',
    'scopes'        => ['whole'],
]);

$accessToken = $response['access_token'];
// Configure the Guzzle client with this Bearer header, as shown above.
```

### Querying resources

```php
$users = $platform->getIdentity()->getUsers();

// List with a Mongo-style filter + pagination.
$page = $users->find([
    'query'      => ['status' => 'active'],
    'pagination' => ['limit' => 10],
]);

// Single record by ID (null when the record does not exist).
$one = $users->findById('64a...');

// Count matching documents.
$total = $users->count(['status' => 'active']);
```

### Request config

Every method takes an optional request-config array as its last argument. Keys
that Guzzle understands (`headers`, `timeout`, `sink`, `stream`, ...) are
forwarded to the request; a few keys are consumed by the SDK:

| Key            | Meaning                                                                 |
| -------------- | ----------------------------------------------------------------------- |
| `params`       | Extra query parameters merged with the filter (`['params' => ['zone' => 'own']]`) |
| `fullResponse` | Return the raw envelope: `{items}` for `find`/`createBulk`, `{data}` for single-record ops, `{total}` for `count`/`updateBulk` |
| `responseType` | `blob`, `arraybuffer`, `string` or `stream` (file downloads)             |
| `prefix`       | Per-call path prefix override                                           |

Client-side keys are never serialized into the query string.

```php
$users->find(['query' => []], ['params' => ['zone' => 'own'], 'headers' => ['X-Request-Id' => '42']]);
$envelope = $users->find([], ['fullResponse' => true]);
```

### Custom operations

```php
use WenexPlatform\PhpSdkCommon\Core\Classes\GraphqlRequest;

$platform->getFinancial()->getTransactions()->init([...]);
$platform->getTouch()->getEmails()->send([...]);
$platform->getContent()->getPosts()->search(['query' => ['match' => ['title' => 'hi']]]);

// Nested sub-services.
$platform->getEssential()->getSagas()->getStages();
$platform->getTouch()->getPushes()->getHistories();

// File upload (multipart) and download (raw body by default, or a stream).
$platform->getSpecial()->getFiles()->upload([
    ['value' => fopen('/path/to/report.pdf', 'r'), 'filename' => 'report.pdf'],
], 'private');

$contents = $platform->getSpecial()->getFiles()->download('file-id');
$stream = $platform->getSpecial()->getFiles()->download('file-id', [], 'stream');

// GraphQL.
$platform->getGraphql()->request(new GraphqlRequest(query: '{ viewer { id } }'));
```

### Property-style access

Every accessor is also available as a property: `$platform->identity->users` is
equivalent to `$platform->getIdentity()->getUsers()`. The method form is
recommended for clarity and static analysis.

## API surface

Every service implements the standard CRUD: `find`, `findById`, `create`,
`createBulk`, `updateById`, `updateBulk`, `deleteById` (soft delete),
`restoreById`, `destroyById` (permanent delete), and `count`.

> Some collections do not implement every operation server-side — e.g. bulk
> update on `identity/users` currently responds with 500 `Method not implemented.` — so `updateBulk` works per collection at the gateway's
> discretion.

| Domain              | Services (custom methods)                                                |
| ------------------- | ------------------------------------------------------------------------ |
| `getAuth()`         | `auths` (`token`, `verify`, `logout`, `check`, `can`), `apts`, `grants`  |
| `getContext()`      | `configs`, `settings`                                                     |
| `getDomain()`       | `apps`, `clients`                                                         |
| `getEssential()`    | `sagas` (`start`, `add`, `abort`, `commit`, `getStages`)                  |
| `getFinancial()`    | `wallets`, `accounts`, `currencies`, `invoices` (`payment`), `transactions` (`init`, `abort`, `verify`) |
| `getGeneral()`      | `events`, `comments`, `artifacts`, `workflows`, `activities`              |
| `getIdentity()`     | `users`, `profiles`, `sessions`                                           |
| `getSpecial()`      | `files` (`download`, `upload`, `share`), `stats` (`collect`, `stackup`)   |
| `getTouch()`        | `emails` (`send`), `smss` (`send`, `sendByTemplate`), `pushes` (`send`, `getHistories`), `notices` |
| `getContent()`      | `notes`, `posts` (`search`), `tickets`                                    |
| `getLogistic()`     | `cargoes`, `drivers`, `vehicles`, `travels` (`routing`), `locations` (`addressLookup`, `geocodeLookup`) |
| `getConjoint()`     | `members`, `accounts` (`cred`), `channels`, `contacts`, `messages` (`search`) |
| `getCareer()`       | `stocks`, `stores`, `products` (`search`), `branches`, `services`, `customers`, `employees`, `businesses` |
| `getThing()`        | `devices`, `sensors`, `metrics`                                           |
| `getGraphql()`      | GraphQL endpoint (`request`)                                              |

Access any service via `$platform->get<Domain>()->get<Service>()`, e.g.
`$platform->getFinancial()->getTransactions()`.

## License

Apache-2.0.
