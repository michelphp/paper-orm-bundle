# PaperORMBundle

Symfony integration of [PaperORM](https://github.com/michelphp/paper-orm): configuration, services, console commands, profiler panel.

PHP 8.2+ · Symfony 5.4, 6, 7

📖 **[Documentation](https://michelphp.github.io/paper-orm/docs.html#symfony)**

## Install

```bash
composer require michel/paper-orm-bundle
```

```php
// config/bundles.php
return [
    // ...
    Michel\PaperORMBundle\PaperORMBundle::class => ['all' => true],
];
```

## Configuration

```yaml
# config/packages/paper_orm.yaml
paper_orm:
  dsn: '%env(resolve:DATABASE_URL)%'
  debug: '%kernel.debug%'
```

| Option             | Default                           | Description                                                                 |
|--------------------|-----------------------------------|-----------------------------------------------------------------------------|
| `dsn`              | *(required)*                      | Database DSN, e.g. `mariadb://user:pass@127.0.0.1:3306/app`                  |
| `debug`            | `false`                           | Record the queries, shown in the profiler                                   |
| `logger`           | `paper.logger`                    | Service ID of a PSR-3 logger (`paper.logger` writes `var/log/paper.log`); `null` disables it |
| `entity_dir`       | `%kernel.project_dir%/src/Entity` | Where your entities are                                                     |
| `migrations_dir`   | `%kernel.project_dir%/migrations` | Where migration files are written                                           |
| `migrations_table` | `mig_versions`                    | Table recording the applied migrations                                      |
| `proxy_autoload`   | `false`                           | Set to `true` if you store loaded entities in the session                   |

## What you get

- `Michel\PaperORM\EntityManagerInterface`, ready to inject.
- `paper:migration:diff`, `paper:migration:migrate`, `paper:database:create`, `paper:database:drop`, `paper:database:sync` in `bin/console`.
- A profiler panel listing the queries of the request.
- The entity manager is reset between two requests in long-running workers (FrankenPHP, RoadRunner, Messenger).

## Status

Alpha, follows the versions of PaperORM. Issues: [github.com/michelphp/paper-orm-bundle/issues](https://github.com/michelphp/paper-orm-bundle/issues)

## License

MPL-2.0
