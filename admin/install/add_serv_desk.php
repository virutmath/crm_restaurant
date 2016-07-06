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
    <h1>Cài đặt quầy phục vụ</h1>
    <div class="row">
        <div class="col-xs-4">
            <div class="form-agencie">
                <ul class="agencies_control">
                    <li><label><span>Tên quầy phục vụ : </span><input type="text" class="form-control" id="name_servdesk"></label></li>
                    <li><label><span>Điện thoại : </span><input type="text" class="form-control" id="phone_servdesk"></label></li>
                    <li>
                        <label><span>Cửa hàng : </span>
                        <select id="cb_agencies" class="form-control">
                        <?
                            $db_serv_desk = new db_query('SELECT * FROM agencies');
                            while($row_serv = mysqli_fetch_assoc($db_serv_desk->result)){?>
                            <option value="<?=$row_serv['age_id']?>"><?=$row_serv['age_name']?></option>
                         <?}?>
                        </select>
                        </label>
                    </li>
                    <li><label><span>Ghi chú : </span><input type="text" class="form-control" id="note_servdesk"></label></li>
                </ul>
            </div>
            <div class="clearfix"></div>
            <div class="col-xs-12">
                <button class="btn btn-primary button_setup" id="reset-default" onclick="addServDesk()">Thêm mới quầy phục vụ</button>
            </div>
        </div>

    </div>
</div>

</body>
<script src="../resources/js/jquery.js" type="text/javascript"></script>
<script src="script.js" type="text/javascript"></script>
</html>




