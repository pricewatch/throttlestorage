# Throttle Storage - PhpRedis

This is a phpredis storage for [Stiphle](https://github.com/davedevelopment/stiphle) library.

## Installation via composer

    > composer require pricewatch/throttlestorage

## Usage

```php
$throttle = new \Stiphle\Throttle\LeakyBucket();
$storage = new \PriceWatch\ThrottleStorage\Redis(new Redis());
$throttle->setStorage($storage);
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.