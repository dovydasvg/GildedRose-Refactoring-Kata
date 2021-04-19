# GildedRose Kata - PHP Version - SOLVED

See the [top level readme](../README.md) for general information about this exercise. This is the PHP version of the
 GildedRose Kata. 

## Installation

The kata uses:

- PHP 7.2+
- [Composer](https://getcomposer.org)

Recommended:
- [Git](https://git-scm.com/downloads)

Clone the repository

```shell script
git clone https://github.com/dovydasvg/GildedRose-Refactoring-Kata
```

Install all the dependencies using composer

```shell script
cd ./GildedRose-Refactoring-Kata/php
composer install
```

## Dependencies

The project uses composer to install:

- [PHPUnit](https://phpunit.de/)
- [ApprovalTests.PHP](https://github.com/approvals/ApprovalTests.php)
- [PHPStan](https://github.com/phpstan/phpstan)
- [Easy Coding Standard (ECS)](https://github.com/symplify/easy-coding-standard) 
- [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer/wiki)

## Folders

- `src` - contains the two classes:
  - `Item.php` - this class should not be changed.
  - `GildedRose.php` - the refactored and updated with new functionality class.
- `tests` - contains the tests
  - `GildedRoseTest.php` - Starter tests. Checks every case specifically.
  - `ApprovalTest.php` - alternative approval test (set to 30 days)
- `Fixture`
  - `texttest_fixture.php` used by the approval test, or can be run from the command line

## How to use this code

Use the GildedRose class to keep your inventory up to date.
After each day run the method updateQuality to update all of your items sellin dates and Quality.
The Quality of each item updates according to the rules defined in separate methods in `GildedRose.php`

To add new rules -> add a new case to the updateSwitch method in `GildedRose.php`.

Each new rule should have a test to double-check it. Add the test to `GildedRoseTest.php`.

To Check if it all works just run PHPUnit tests:

```shell script
composer test
```

## Static Analysis

PHPStan is used to run static analysis checks:

```shell script
composer phpstan
```

On Windows a batch file has been created, similar to an alias on Linux/Mac (e.g. `alias ps="composer phpstan"`), the
 same PHPUnit `composer phpstan` can be run:

```shell script
ps
```

**Happy coding**!