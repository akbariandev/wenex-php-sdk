# wenex-platform/php-sdk

PHP SDK for the Wenex platform — a port of the TypeScript `@wenex/sdk`.

It wraps a single pre-configured [Guzzle](https://docs.guzzlephp.org/) client and
exposes each platform domain (auth, identity, financial, …) as a lazily-built
service aggregator. All reusable HTTP plumbing (the `RestfulService` CRUD base,
`GraphqlService`, enums, models, crypto helpers) comes from the companion package
`[wenex-platform/php-sdk-common](../php-sdk-common)`.

## Install

The SDK depends on the **private** package `wenex-platform/php-sdk-common`, which
is vendored as a git submodule at `packages/common/` (mirroring how the TypeScript
`platform-sdk` vendors `platform-sdk-common` at `src/common`). This avoids any
private-registry/Packagist setup — the common source ships inside this repo.

```bash
git clone --recurse-submodules <this-repo-url>
# or, if already cloned without submodules:
composer git:clone

composer install
```

> The submodule uses a **relative** URL (`../php-sdk-common.git`), so it resolves
> correctly whether you clone from GitHub or the GitLab staging remote. The
> `composer.json` `repositories` entry points at the local `packages/common` path
> (`symlink: true`), so `composer install` never needs network access to the
> private common repo.

> **End consumers:** if you distribute this SDK to users without private-repo
> access, either publish a release tag with the submodule pinned, or see the
> "Deferred / packaging" note below.



## Usage

Auth is caller-managed, mirroring the TS SDK: you build a Guzzle client with the
base URI and `Authorization` header, then hand it to the SDK.

```php
use GuzzleHttp\Client;
use WenexPlatform\PhpSdk\Platform;

$httpClient = new Client([
    'base_uri' => 'https://api.example.com/',
    'headers'  => ['Authorization' => 'Bearer ' . $accessToken],
    'timeout'  => 30,
]);

$platform = Platform::build($httpClient);

// Standard CRUD (find / find_by_id / create / update_by_id / delete_by_id / ...).
$users = $platform->getIdentity()->getUsers();
$page = $users->find(['query' => ['status' => 'active'], 'pagination' => ['limit' => 10]]);
$one  = $users->find_by_id('64a...');

// Obtain a token via the auth flow, then set it on the shared client for later calls.
$token = $platform->getAuth()->getAuths()->token([
    'grant_type' => 'password',
    'username'   => '...',
    'password'   => '...',
]);

// Custom domain methods.
$platform->getFinancial()->getTransactions()->init([...]);
$platform->getTouch()->getEmails()->send([...]);
$platform->getContent()->getPosts()->search(['query' => ['match' => ['title' => 'hi']]]);
$platform->getEssential()->getSagas()->getStages();      // sub-service
$platform->getTouch()->getPushes()->getHistories();      // sub-service

// GraphQL.
$platform->getGraphql()->request(new GraphqlRequest(query: '{ viewer { id } }'));
```



### Property-style access

For parity with the TS getters (`platform.identity`), PHP exposes both method
(`$platform->getIdentity()`) and property (`$platform->identity`) access. The
method form is recommended for clarity and static analysis.

## Conventions

- **Namespace:** `WenexPlatform\PhpSdk\` (services under `Services\<Domain>\`).
- **Methods are** `snake_case` (`find_by_id`, `create_bulk`, `restore_by_id`,
`send_by_template`, `address_lookup`) — matching `php-sdk-common`.
- **Data is passed as plain** `array` and responses are decoded `array`/`mixed`,
consistent with `php-sdk-common`'s `RestfulService`. No serializer layer.
- `fullResponse`: pass `['fullResponse' => true]` in `$config` to receive the
raw envelope (`{data: …}` / `{items: …}` / `{total: …}`) instead of the
unwrapped value.



## TypeScript → PHP accessor mapping


| TS (`platform.<domain>.<svc>`) | PHP (`$platform->get<Domain>()->get<Svc>()`) |
| ------------------------------ | -------------------------------------------- |
| `auth.auths`                   | `getAuth()->getAuths()`                      |
| `identity.users`               | `getIdentity()->getUsers()`                  |
| `financial.transactions`       | `getFinancial()->getTransactions()`          |
| `essential.sagas.stages`       | `getEssential()->getSagas()->getStages()`    |
| `touch.pushes.histories`       | `getTouch()->getPushes()->getHistories()`    |
| `special.files`                | `getSpecial()->getFiles()`                   |
| `content.posts`                | `getContent()->getPosts()`                   |
| `graphql`                      | `getGraphql()`                               |




## Deferred features

The following are intentionally **not yet implemented** (the surface exists but
throws `BadMethodCallException`):

- `cursor()` **SSE streaming** — the TS SDK streams `GET {path}/cursor` via
Server-Sent Events. A PHP equivalent needs a streaming Guzzle reader plus an
SSE frame parser.
- `files.upload()` **multipart upload** — awaiting a clean PHP equivalent of the
TS `{ value: Blob, filename?: string }[]` shape.

All other services and custom methods match the TS surface 1:1.

## Development



## Git workflow (submodules)

Mirrors the TypeScript `platform-sdk` scripts (`scripts/git-submodule.sh`,
`scripts/git-remote-add.sh`):

```bash
composer git:clone                        # init/update the packages/common submodule

composer git -- status                    # run any git command in the parent repo
                                          # AND every submodule (note the "--")

composer git -- checkout main             # e.g. move both repos to main

composer git:remote:add staging wenex.org # add GitLab remotes (staging|production)
                                          # for php-sdk AND php-sdk-common, push main
```

After `git flow release|hotfix finish`, the `composer git --` passthrough also
pushes tags in the parent repo and all submodules.

## Development

```bash
composer git:clone    # vendor php-sdk-common (first time)
composer install      # resolves common from packages/common
composer test-unit    # PHPUnit
composer analyze      # PHPStan (level 5)
composer format       # PHP-CS-Fixer (PSR-12)
```



## License

Apache-2.0.