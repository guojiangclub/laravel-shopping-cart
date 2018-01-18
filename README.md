本包是基于 [overtrue/laravel-shopping-cart][1] 进行扩展开发，主要实现了以下扩展：

1. 购物车数据支持 Cache 和 Database 存储
2. Item 增加 Model 属性返回。因为购物车可能是SPU或者SKU，因此直接通过 model 属性直接返回相关对象。
3. 支持把 Session 中的数据直接同步到 Cache 或 Database 中。
4. 支持多 Guard. 因为在 iBrand 产品有商城购物车和导购购物车。

## TODO：

1. 重新完善测试用例

> 目前改包源正在线上使用中

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
 'storage' => \iBrand\Shoppingcart\Storage\CacheStorage::class,
 
 or
 
  'storage' => \iBrand\Shoppingcart\Storage\DatabaseStorage::class,
  
  or 
  
   'storage' => \iBrand\Shoppingcart\Storage\SessionStorage::class,
```

If you use Database Storage, you need to execute `php artisan migrate`

### Add item to cart

Add a new item.

```php
Item | null ShoppingCart::add(
                    string | int $id,
                    string $name,
                    int $quantity,
                    int | float $price
                    [, array $attributes = []]
                 );
```

**example:**

```php
$row = ShoppingCart::add(37, 'Item name', 5, 100.00, ['color' => 'red', 'size' => 'M']);
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
Item ShoppingCart::update(string $rawId, int $quantity);
Item ShoppingCart::update(string $rawId, array $arrtibutes);
```

**example:**

```php
ShoppingCart::update('8a48aa7c8e5202841ddaf767bb4d10da', ['name' => 'New item name']);
// or only update quantity
ShoppingCart::update('8a48aa7c8e5202841ddaf767bb4d10da', 5);
```

### Get all items

Get all the items.

```php
Collection ShoppingCart::all();
```

**example:**

```php
$items = ShoppingCart::all();
```


### Get item

Get the specified item.

```php
Item ShoppingCart::get(string $rawId);
```

**example:**

```php
$item = ShoppingCart::get('8a48aa7c8e5202841ddaf767bb4d10da');
```

### Remove item

Remove the specified item by raw ID.

```php
boolean ShoppingCart::remove(string $rawId);
```

**example:**

```php
ShoppingCart::remove('8a48aa7c8e5202841ddaf767bb4d10da');
```

### Destroy cart

Clean Shopping Cart.

```php
boolean ShoppingCart::destroy();
boolean ShoppingCart::clean(); // alias of destroy();
```

**example:**

```php
ShoppingCart::destroy();// or ShoppingCart::clean();
```

### Total price

Returns the total of all items.

```php
int | float ShoppingCart::total(); // alias of totalPrice();
int | float ShoppingCart::totalPrice();
```

**example:**

```php
$total = ShoppingCart::total();
// or
$total = ShoppingCart::totalPrice();
```


### Count rows

Return the number of rows.

```php
int ShoppingCart::countRows();
```

**example:**

```php
ShoppingCart::add(37, 'Item name', 5, 100.00, ['color' => 'red', 'size' => 'M']);
ShoppingCart::add(37, 'Item name', 1, 100.00, ['color' => 'red', 'size' => 'M']);
ShoppingCart::add(37, 'Item name', 5, 100.00, ['color' => 'red', 'size' => 'M']);
ShoppingCart::add(127, 'foobar', 15, 100.00, ['color' => 'green', 'size' => 'S']);
$rows = ShoppingCart::countRows(); // 2
```


### Count quantity

Returns the quantity of all items

```php
int ShoppingCart::count($totalItems = true);
```

`$totalItems` : When `false`,will return the number of rows.

**example:**

```php
ShoppingCart::add(37, 'Item name', 5, 100.00, ['color' => 'red', 'size' => 'M']);
ShoppingCart::add(37, 'Item name', 1, 100.00, ['color' => 'red', 'size' => 'M']);
ShoppingCart::add(37, 'Item name', 5, 100.00, ['color' => 'red', 'size' => 'M']);
$count = ShoppingCart::count(); // 11 (5+1+5)
```

### Search items

Search items by property.

```php
Collection ShoppingCart::search(array $conditions);
```

**example:**

```php
$items = ShoppingCart::search(['color' => 'red']);
$items = ShoppingCart::search(['name' => 'Item name']);
$items = ShoppingCart::search(['qty' => 10]);
```

### Check empty

```php
bool ShoppingCart::isEmpty();
```

### Specifies the associated model

Specifies the associated model of item.

```php
Cart ShoppingCart::associate(string $modelName);
```

**example:**

```php
ShoppingCart::associate('App\Models\Product');
$item = ShoppingCart::get('8a48aa7c8e5202841ddaf767bb4d10da');
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
| `shopping_cart.adding`  | ($attributes, $cart); |
| `shopping_cart.added`  | ($attributes, $cart); |
| `shopping_cart.updating`  | ($row, $cart); |
| `shopping_cart.updated`  | ($row, $cart); |
| `shopping_cart.removing`  | ($row, $cart); |
| `shopping_cart.removed`  | ($row, $cart); |
| `shopping_cart.destroying`  | ($cart); |
| `shopping_cart.destroyed`  | ($cart); |

You can easily handle these events, for example:

```php
Event::on('shopping_cart.adding', function($attributes, $cart){
    // code
});
```


  [1]: https://github.com/overtrue/laravel-shopping-cart