<?php

class Current_Desk_Menu extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'current_desk_menu';
	const CREATED_AT = 'cdm_create_time';
	const UPDATED_AT = 'cdm_updated_time';
	protected $dateFormat = 'U';

	public static function getDetail($id)
	{
		return self::join('menus', 'men_id', '=', 'cdm_menu_id')
			->where('cdm_desk_id', $id)
			->get();
	}

	public static function addMenu($desk_id, Menu $menu, $number = 1, $custom_price = null)
	{
		$price = $menu->men_editable && $custom_price ? $custom_price : $menu->men_price;
		//insert
		return self::insert([
			'cdm_desk_id'=>$desk_id,
			'cdm_menu_id'=>$menu->men_id,
			'cdm_number'=>$number,
			'cdm_price'=>$price,
			'cdm_price_type'=>Menu::MAIN_PRICE,
			'cdm_create_time'=>time(),
			'cdm_updated_time'=>time()
		]);
	}

	public static function deleteMenu($desk_id, $menu_id) {
		return self::where([
			'cdm_desk_id'=>$desk_id,
			'cdm_menu_id'=>$menu_id
		])->delete();
	}

	public static function checkMenuExist($desk_id, $menu_id) {
		return !!self::where(['cdm_desk_id'=>$desk_id,'cdm_menu_id'=>$menu_id])->count();
	}

	public static function increaseDish($desk_id, $menu_id, $increment = 1) {
		return self::where(['cdm_desk_id'=>$desk_id,'cdm_menu_id'=>$menu_id])->increment('cdm_number',$increment);
	}

	public static function decreaseDish($desk_id, $menu_id, $decrement = 1) {
		return self::where(['cdm_desk_id'=>$desk_id,'cdm_menu_id'=>$menu_id])
			->where('cdm_number','>',0)
			->decrement('cdm_number',$decrement);
	}
}