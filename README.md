# Laravel Data Audit Package

[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/spatie/laravel-permission/run-tests-L8.yml?branch=main&label=Tests)]()
[![License](https://poser.pugx.org/orchestra/testbench/license)](https://packagist.org/packages/orchestra/testbench)


## Installation
To install it you need to add this in your composer.json file

```json
"repositories": {
        "eloise/laravel-data-audit": {        
            "type": "vcs",
            "url": "https://github.com/EloisePHP/laravel-data-audit-package"}
    },
```

and then 
```php
composer require eloise/laravel-data-audit
```


## What It Does
This package allows you to audit every action done with a Model you chose.

Once installed for every Model in you project you want to Audit you should make it implement the contract AuditableModel and use the trait AuditableModelTrait like this:

```php
// Adding permissions to a user
class DefaultAuditableModel extends Model implements AuditableModel
{
    use AuditableModelTrait;
```

And that's it! Everytime someone creates, edits or deletes this model it will be audited and you can check it in the eloise_audit table.

## Support me

I'm looking for a job right now so if you show this package to anyone out there it would help me a ton.

### Testing
All tests are made with [Orchestra Testbench](https://packages.tools/testbench).

## Alternatives

- [Spatie Activity Log](https://github.com/spatie/laravel-activitylog) is a great package done by [Spatie](https://spatie.be). The main difference with Spatie's package is that it is focused on user activity.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.