<?php 

namespace App\Shop\Facades;

use Illuminate\Support\Facades\Facade;

class Favorite extends Facade
{
	protected static function getFacadeAccessor(){
      return 'favorite';
  }
	
}
