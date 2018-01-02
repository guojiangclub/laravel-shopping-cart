<?php

namespace ElementVip\Shoppingcart\Storage;

use ElementVip\Shoppingcart\Item;
use Illuminate\Database\Eloquent\Collection;
use DB;

class DatabaseStorage implements Storage
{

    private $table = 'shopping_cart';

    private $filed = ['__raw_id', 'id', 'name', 'qty', 'price', 'total', '__model', 'type', 'status'];

    public function set($key, $values)
    {
        $rawIds = $values->pluck('__raw_id')->toArray();

        DB::table($this->table)->whereNotIn('__raw_id',$rawIds)->where('key',$key)->delete();

        $values = $values->toArray();
        foreach ($values as $value) {
            $item = array_only($value, $this->filed);
            $attr = json_encode(array_except($value, $this->filed));
            $insert = array_merge($item, ['attributes' => $attr, 'key' => $key]);
            if (DB::table($this->table)->where(['key' => $key, '__raw_id' => $item['__raw_id']])->first()) {
                DB::table($this->table)->where(['key' => $key, '__raw_id' => $item['__raw_id']])
                    ->update(array_except($insert, ['key', '__raw_id']));
            } else {
                DB::table($this->table)->insert($insert);
            }
        }
    }

    public function get($key, $default = null)
    {
        $items = DB::table($this->table)->where('key', $key)->get();

        $items = $items->toArray();
        $collection = [];
        foreach ($items as $item) {
            $item = json_decode(json_encode($item), true);
            $attr = json_decode($item['attributes'], true);
            $item = array_only($item, $this->filed);
            $item = array_merge($item, $attr);
            $collection[$item['__raw_id']] = new Item($item);
        }
        return new Collection($collection);
    }

    public function forget($key)
    {
        DB::table($this->table)->where('key', $key)->delete();
    }
}
