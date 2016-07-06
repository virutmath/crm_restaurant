<? require_once 'inc_security.php';?>
<!DOCTYPE html>
<html>
<head>
    <title>Cài đặt hệ thống mặc định</title>
    <link href="custom.css" type="text/css" rel="stylesheet">
    <link href="../resources/css/bootstrap.min.css" type="text/css" rel="stylesheet">
</head>
<body>
<div class="wrapper">
    <div class="row">
        <div class="col-xs-12">
            <div class="col-xs-12">
                <label class="check_all"><input type="checkbox" class="check_all" id="check-all"> Tất cả </label>
            </div>
            <ul class="list_table">
                <li><label><input type="checkbox" value="admin_group_role" class=" group_table" id="admin_group_role"> admin_group_role </label></li>
                <li><label><input type="checkbox" value="agencies" class=" group_table" id="agencies">  agencies</label></li>
                <li><label><input type="checkbox" value="bill_in" class=" group_table" id="bill_in"> bill_in</label></li>
                <li><label><input type="checkbox" value="bill_in_detail" class=" group_table" id="bill_in_detail"> bill_in_detail</label></li>
                <li><label><input type="checkbox" value="bill_out" class=" group_table" id="bill_out"> bill_out</label></li>
                <li><label><input type="checkbox" value="bill_out_detail" class=" group_table" id="bill_out_detail"> bill_out_detail</label></li>
                <li><label><input type="checkbox" value="configurations" class=" group_table" id="configurations"> configurations</label></li>
                <li><label><input type="checkbox" value="current_desk" class=" group_table" id="current_desk"> current_desk</label></li>
                <li><label><input type="checkbox" value="current_desk_menu" class=" group_table" id="current_desk_menu"> current_desk_menu</label></li>
                <li><label><input type="checkbox" value="customer_cat" class=" group_table" id="customer_cat"> customer_cat</label></li>
                <li><label><input type="checkbox" value="customers" class=" group_table" id="customers"> customers</label></li>
                <li><label><input type="checkbox" value="desks" class=" group_table" id="desks"> desks</label></li>
                <li><label><input type="checkbox" value="financial" class=" group_table" id="financial"> financial</label></li>
                <li><label><input type="checkbox" value="inventory" class=" group_table" id="inventory"> inventory</label></li>
                <li><label><input type="checkbox" value="inventory_products" class=" group_table" id="inventory_products"> inventory_products</label></li>
                <li><label><input type="checkbox" value="menu_products" class=" group_table" id="menu_products"> menu_products</label></li>
                <li><label><input type="checkbox" value="menus" class=" group_table" id="menus"> menus</label></li>
                <li><label><input type="checkbox" value="product_quantity" class=" group_table" id="product_quantity"> product_quantity</label></li>
                <li><label><input type="checkbox" value="products" class=" group_table" id="products"> products</label></li>
                <li><label><input type="checkbox" value="promotions" class=" group_table" id="promotions"> promotions</label></li>
                <li><label><input type="checkbox" value="promotions_menu" class=" group_table" id="promotions_menu"> promotions_menu</label></li>
                <li><label><input type="checkbox" value="customers" class=" group_table" id="customers"> customers</label></li>
                <li><label><input type="checkbox" value="service_desks" class=" group_table" id="service_desks"> service_desks</label></li>
                <li><label><input type="checkbox" value="sections" class=" group_table" id="sections"> sections</label></li>
                <li><label><input type="checkbox" value="stock_transfer" class=" group_table" id="stock_transfer"> stock_transfer</label></li>
                <li><label><input type="checkbox" value="stock_transfer_products" class=" group_table" id="stock_transfer_products"> stock_transfer_products</label></li>
                <li><label><input type="checkbox" value="suppliers" class=" group_table" id="suppliers"> suppliers</label></li>
                <li><label><input type="checkbox" value="trash" class=" group_table" id="trash"> trash</label></li>
                <li><label><input type="checkbox" value="units" class=" group_table" id="units"> units</label></li>
                <li><label><input type="checkbox" value="users" class=" group_table" id="users"> users</label></li>
                <li><label><input type="checkbox" value="categories_multi" class=" group_table" id="categories_multi"> categories_multi</label></li>
                <li><label><input type="checkbox" value="admin_users" class=" group_table" id="admin_users"> admin_users</label></li>
                <li><label><input type="checkbox" value="admin_users_groups" class=" group_table" id="admin_users_groups"> admin_users_groups</label></li>
                <li><label><input type="checkbox" value="triggers" class=" group_table" id="triggers"> triggers</label></li>
            </ul>
            <div class="clearfix"></div>
            <div class="col-xs-12">
                <button class="btn btn-primary button_setup" id="reset-default" onclick="resetDefault()">Xóa dữ liệu database và tiếp tục</button>
            </div>
        </div>

    </div>
</div>

</body>
<script src="../resources/js/jquery.js" type="text/javascript"></script>
<script src="script.js" type="text/javascript"></script>
</html>




