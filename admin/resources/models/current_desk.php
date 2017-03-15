<?php

class Current_Desk extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'current_desk';

	public static function getDetail($id)
	{
		return self::where('cud_desk_id', $id)
			->first();
	}

	public static function open($id) {
		//default data
		$data = [
			'cud_desk_id'=>$id,
			'cud_start_time'=>time(),
			'cud_pay_type'=>PAY_TYPE_CASH
		];
		//insert
		return self::insert($data);
	}

}