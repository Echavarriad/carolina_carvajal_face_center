<?php

namespace App\Repositories;

use App\Models\Coupon;


class CouponRepository extends BaseRepository
{
	
	public function __construct(Coupon $model)
	{
		parent::__construct($model);
	}

	public function status() {
		$data = request()->all();
		$object = $this->get($data['id']);
		$message = '';
		if ($data['status'] == 'true') {
			$message = 'Cupón activo.';
			$object->status = 1;
			$type = 'success';
		} else {
			$message = 'Cupón desactivado.';
			$object->status = 0;
			$type = 'warning';
		}
		$response = ['status' => true , 'message'=> $message, 'type' => $type];
		$object->save();
		
		return $response;
	  }

}