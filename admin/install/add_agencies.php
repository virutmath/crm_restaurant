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
    <h1>Cài đặt cửa hàng mặc định</h1>
    <div class="row">
        <div class="col-xs-6">
            <div class="form-agencie">
                <ul class="agencies_control">
                    <li><label><span>Tên cửa hàng : </span><input type="text" class="form-control" id="name_agencies"></label></li>
                    <li><label><span>Tên kho hàng : </span><input type="text" class="form-control" id="name_stores"></label></li>
                    <li><label><span>Địa chỉ : </span><input type="text" class="form-control" id="address_agencies"></label></li>
                    <li><label><span>Số điện thoại : </span><input type="text" class="form-control" id="phone_agencies"></label></li>
                    <li><label><span>Ghi chú : </span><input type="text" class="form-control" id="note_agencies"></label></li>
                </ul>
            </div>
            <div class="clearfix"></div>
            <div class="col-xs-12">
                <button class="btn btn-primary button_setup" id="reset-default" onclick="addAgenDefault()">Thêm mới cửa hàng mặc định</button>
            </div>
        </div>

    </div>
</div>

</body>
<script src="../resources/js/jquery.js" type="text/javascript"></script>
<script src="script.js" type="text/javascript"></script>
</html>




