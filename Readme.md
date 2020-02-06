# FE User History

[![Latest Stable Version](https://img.shields.io/packagist/v/sudhaus7/fe-data-history.svg)](https://packagist.org/packages/sudhaus7/fe-data-history)
[![Build Status](https://github.com/endroid/qr-code/workflows/CI/badge.svg)](https://github.com/sudhaus7/fe-data-history/actions)
[![Total Downloads](https://img.shields.io/packagist/dt/sudhaus7/fe-data-history.svg)](https://packagist.org/packages/sudhaus7/fe-data-history)
[![Monthly Downloads](https://img.shields.io/packagist/dm/sudhaus7/fe-data-history.svg)](https://packagist.org/packages/sudhaus7/fe-data-history)
[![License](https://img.shields.io/packagist/l/sudhaus7/fe-data-history.svg)](https://packagist.org/packages/sudhaus7/fe-data-history)

A TYPO3 Plugin for saving frontend edited records history in sys_history.

## Installation

Install the Extension via composer

```bash
composer require sudhaus7/fe-data-history
```

## Usage

For getting your history logged in backend table, you need to add the interface `HistoryEntityInterface` to your
Extbase AbstractEntity object.

### Example

```php
/**
 * class MyEntityObject
 * @package VENDOR\MyExtension\Domain\Model
 */
class MyEntityObject extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity implements \SUDHAUS7\FeDataHistory\Domain\HistoryEntityInterface
{
}
```

The extension uses the Extbase Backend SignalSlots for getting signal, if Entity is created, deleted or updated.

`ElementHistoryController` xclasses the original one for getting the information in the backend history log.
