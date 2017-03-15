<?php

class Menu extends \Illuminate\Database\Eloquent\Model {
	protected $table = 'menus';
	const MAIN_PRICE = 'men_price';
	const SUB_PRICE = 'men_price1';
	const SUB_PRICE2 = 'men_price2';

	public static function getDetail($id) {
		return self::where('men_id',$id)->first();
	}
}