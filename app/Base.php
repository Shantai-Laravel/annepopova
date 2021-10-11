<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

// The main Model
class Base extends Model
{
    public static $lang = 1;

    public static $currency = 5;

    public static $mainCurrency = 5;

    public static $site = 'homewear';

    public static $dillerGroupId = null;

    public static $warehouse = null;

    public static $warehouseName = null;
}
