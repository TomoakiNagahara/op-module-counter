# config.php

The counter module ships a default config template:

```text
asset/module/counter/config.php
```

This file is not loaded automatically.
To enable or customize counter config, copy it into the application config area:

```sh
cp asset/module/counter/config.php asset/config/counter.php
```

The application config loaded by `OP()->Config('counter')` is:

```text
asset/config/counter.php
```

The optional local override config is:

```text
asset/config/_counter.php
```

`asset/config/_counter.php` is loaded after `asset/config/counter.php` and can override local-only values.

Keeping the copy step explicit makes it clear to users which config file controls runtime behavior.

## Options

### `skip`

Default:

```php
[
	//	When set to "admin", admin access is not counted.
	'skip' => 'admin',
]
```

The template uses the explicit value `admin` instead of `null` so third-party users can see the available setting directly from `config.php` without first reading documentation or asking an AI assistant.

When `skip` is `admin`, `Counter::ShouldCount()` checks `OP()->isAdmin()`.
If `OP()->isAdmin()` is `true`, the counter skips incrementing.
When an admin request is skipped, the counter always outputs a `D()` debug message.

When `skip` is not `admin`, every request is counted.

Example application config:

```php
<?php
return [
	'skip' => 'admin',
];
```
