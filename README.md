[![Build Status](https://travis-ci.org/ibrandcc/laravel-shopping-cart.svg?branch=master)](https://travis-ci.org/ibrandcc/laravel-shopping-cart)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ibrandcc/laravel-shopping-cart/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ibrandcc/laravel-shopping-cart/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/ibrandcc/laravel-shopping-cart/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ibrandcc/laravel-shopping-cart/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/ibrandcc/laravel-shopping-cart/badges/build.png?b=master)](https://scrutinizer-ci.com/g/ibrandcc/laravel-shopping-cart/build-status/master)

本包是基于 [overtrue/laravel-shopping-cart][1] 进行扩展开发，主要实现了以下扩展：

1. 购物车数据支持 Database 存储
2. Item 增加 Model 属性返回。因为购物车可能是SPU或者SKU，因此直接通过 model 属性直接返回相关对象。
3. 支持把 Session 中的数据直接同步到 Cache 或 Database 中。
4. 支持多 Guard. 因为在 iBrand 产品有商城购物车和导购购物车。

> 已经完成了 Session 和 Database 模式下的单元测试，而且正在 iBrand 产品线上使用中. 可放心使用.

## Installation

```
composer require ibrand/laravel-shopping-cart:~1.0 -vvv
```

```
php artisan vendor:publish --provider="iBrand\Shoppingcart\ServiceProvider"
```

低于 Laravel5.5 版本

`config/app.php` 文件中 'providers' 添加
```
iBrand\Shoppingcart\ServiceProvider::class
```

`config/app.php` 文件中 'aliases' 添加

```
'Cart'=> iBrand\Shoppingcart\Facade::class
```

## Usage

### Select Storage

You can change data Storage in `config/ibrand/cart.php` file.

```php
'storage' => \iBrand\Shoppingcart\Storage\DatabaseStorage::class,
  
'storage' => \iBrand\Shoppingcart\Storage\SessionStorage::class,
```

If you use Database Storage, you need to execute `php artisan migrate`

### Add item to cart

Add a new item.

```php
Item | null Cart::add(
                    string | int $id,
                    string $name,
                    int $quantity,
                    int | float $price
                    [, array $attributes = []]
                 );
```

**example:**

```php
$row = Cart::add(37, 'Item name', 5, 100.00, ['color' => 'red', 'size' => 'M']);
// Item:
//    id       => 37
//    name     => 'Item name'
//    qty      => 5
//    price    => 100.00
//    color    => 'red'
//    size     => 'M'
//    total    => 500.00
//    __raw_id => '8a48aa7c8e5202841ddaf767bb4d10da'
$rawId = $row->rawId();// get __raw_id
$row->qty; // 5
...
```

### Update item

Update the specified item.

```php
Item Cart::update(string $rawId, int $quantity);
Item Cart::update(string $rawId, array $arrtibutes);
```

**example:**

```php
Cart::update('8a48aa7c8e5202841ddaf767bb4d10da', ['name' => 'New item name');
// or only update quantity
Cart::update('8a48aa7c8e5202841ddaf767bb4d10da', 5);
```

### Get all items

Get all the items.

```php
Collection Cart::all();
```

**example:**

```php
$items = Cart::all();
```


### Get item

Get the specified item.

```php
Item Cart::get(string $rawId);
```

**example:**

```php
$item = Cart::get('8a48aa7c8e5202841ddaf767bb4d10da');
```

### Remove item

Remove the specified item by raw ID.

```php
boolean Cart::remove(string $rawId);
```

**example:**

```php
Cart::remove('8a48aa7c8e5202841ddaf767bb4d10da');
```

### Destroy cart

Clean Shopping Cart.

```php
boolean Cart::destroy();
boolean Cart::clean(); // alias of destroy();
```

**example:**

```php
Cart::destroy();// or Cart::clean();
```

### Total price

Returns the total of all items.

```php
int | float Cart::total(); // alias of totalPrice();
int | float Cart::totalPrice();
```

**example:**

```php
$total = Cart::total();
// or
$total = Cart::totalPrice();
```


### Count rows

Return the number of rows.

```php
int Cart::countRows();
```

**example:**

```php
Cart::add(37, 'Item name', 5, 100.00, ['color' => 'red', 'size' => 'M']);
Cart::add(37, 'Item name', 1, 100.00, ['color' => 'red', 'size' => 'M']);
Cart::add(37, 'Item name', 5, 100.00, ['color' => 'red', 'size' => 'M']);
Cart::add(127, 'foobar', 15, 100.00, ['color' => 'green', 'size' => 'S']);
$rows = Cart::countRows(); // 2
```


### Count quantity

Returns the quantity of all items

```php
int Cart::count($totalItems = true);
```

`$totalItems` : When `false`,will return the number of rows.

**example:**

```php
Cart::add(37, 'Item name', 5, 100.00, ['color' => 'red', 'size' => 'M']);
Cart::add(37, 'Item name', 1, 100.00, ['color' => 'red', 'size' => 'M']);
Cart::add(37, 'Item name', 5, 100.00, ['color' => 'red', 'size' => 'M']);
$count = Cart::count(); // 11 (5+1+5)
```

### Search items

Search items by property.

```php
Collection Cart::search(array $conditions);
```

**example:**

```php
$items = Cart::search(['color' => 'red']);
$items = Cart::search(['name' => 'Item name']);
$items = Cart::search(['qty' => 10]);
```

### Check empty

```php
bool Cart::isEmpty();
```

### Specifies the associated model

Specifies the associated model of item.

```php
Cart Cart::associate(string $modelName);
```

**example:**

```php
Cart::associate('App\Models\Product');
$item = Cart::get('8a48aa7c8e5202841ddaf767bb4d10da');
$item->product->name; // $item->product is instanceof 'App\Models\Product'
```


# The Collection And Item

`Collection` and `Overtrue\LaravelShoppingCart\Item` are instanceof `Illuminate\Support\Collection`, Usage Refer to：[Collections - Laravel doc.](http://laravel.com/docs/5.0/collections)

properties of `Overtrue\LaravelShoppingCart\Item`:

- `id`       - your goods item ID.
- `name`     - Name of item.
- `qty`      - Quantity of item.
- `price`    - Unit price of item.
- `total`    - Total price of item.
- `__raw_id` - Unique ID of row.
- `__model`  - Name of item associated Model.
- ... custom attributes.

And methods:

 - `rawId()` - Return the raw ID of item.

# Events

| Event Name | Parameters |
| -------  | ------- |
| `cart.adding`  | ($attributes, $cart); |
| `cart.added`  | ($attributes, $cart); |
| `cart.updating`  | ($row, $cart); |
| `cart.updated`  | ($row, $cart); |
| `cart.removing`  | ($row, $cart); |
| `cart.removed`  | ($row, $cart); |
| `cart.destroying`  | ($cart); |
| `cart.destroyed`  | ($cart); |

You can easily handle these events, for example:

```php
Event::on('cart.adding', function($attributes, $cart){
    // code
});
```


  [1]: https://github.com/overtrue/laravel-shopping-cart