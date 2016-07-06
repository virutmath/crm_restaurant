<?
require_once 'inc_security.php';

//class Ajax - version 1.0
class DeskAjax extends AjaxCommon
{
    public function __construct()
    {
        parent::__construct(); // TODO: Change the autogenerated stub
        //Set option_filter
        global $configuration;
        $this->option_filter = 'agency_' . $configuration['con_default_agency'];
    }

    function loadFormAddSection()
    {
        checkPermission('add');
        global $configuration;
        //open modal
        $this->openModal();

        //Lấy ra danh sách cửa hàng
        $db_age = new db_query('SELECT * FROM agencies');
        $list_agencies = array();
        $list_all_svdesk = array();
        while ($row = mysqli_fetch_assoc($db_age->result)) {
            $list_agencies[$row['age_id']] = $row['age_name'];
            $db_sed = new db_query('SELECT * FROM service_desks WHERE sed_agency_id = ' . $row['age_id']);
            while ($row_s = mysqli_fetch_assoc($db_sed->result)) {
                $list_all_svdesk[$row['age_id']][$row_s['sed_id']] = $row_s['sed_name'] . ' - ' . $row['age_name'];
            }
        }
        $db_service_desk = new db_query('SELECT * FROM service_desks WHERE sed_agency_id = ' . $configuration['con_default_agency']);
        $list_service_desks = array();
        while ($row = mysqli_fetch_assoc($db_service_desk->result)) {
            $list_service_desks[$row['sed_id']] = $row['sed_name'] . ' - ' . $list_agencies[$configuration['con_default_agency']];
        }

        $this->add(
            $this->form->select(array(
                'label' => 'Cửa hàng',
                'name' => 'agency_list',
                'id' => 'agency_list',
                'option' => $list_agencies,
                'extra' => 'onchange=loadServiceDesk()',
                'selected' => $configuration['con_default_agency'],
                'require'=>1,
                'errorMsg'=>'Bạn chưa chọn cửa hàng'
            ))
        );

        $this->add(
            $this->form->select(array(
                'label' => 'Quầy phục vụ',
                'name' => 'sec_service_desk',
                'id' => 'sec_service_desk',
                'option' => $list_service_desks,
                'require'=>1,
                'errorMsg'=>'Bạn chưa chọn quầy phục vụ'
            ))
        );
        //Thêm javascript
        $this->add('<script>var list_service_desk = ' . json_encode($list_all_svdesk) . '</script>');
        $this->add(
            $this->form->text(array(
                'label' => 'Tên khu vực',
                'name' => 'sec_name',
                'id' => 'sec_name',
                'require' => 1,
                'errorMsg' => 'Bạn chưa nhập tên khu vực'
            ))
        );

        $this->add(
            $this->form->textarea(array(
                'label' => 'Ghi chú',
                'name' => 'sec_note',
                'id' => 'sec_note'
            ))
        );
        //close modal
        $this->closeModal('add_section');
    }

    function loadFormEditSection()
    {
        checkPermission('edit');
        global $configuration;
        $sec_id = getValue('sec_id', 'int', 'POST', 0);
        $db_query = new db_query('SELECT *
                                  FROM sections
                                  LEFT JOIN service_desks ON sec_service_desk = sed_id
                                  LEFT JOIN agencies ON age_id = sed_agency_id
                                  WHERE sec_id = ' . $sec_id);
        //lấy chi tiết bản ghi
        $row_section = mysqli_fetch_assoc($db_query->result);
        unset($db_query);
        //open modal
        $this->openModal();

        //Lấy ra danh sách cửa hàng
        $db_age = new db_query('SELECT * FROM agencies');
        $list_agencies = array();
        $list_all_svdesk = array();
        while ($row = mysqli_fetch_assoc($db_age->result)) {
            $list_agencies[$row['age_id']] = $row['age_name'];
            $db_sed = new db_query('SELECT * FROM service_desks WHERE sed_agency_id = ' . $row['age_id']);
            while ($row_s = mysqli_fetch_assoc($db_sed->result)) {
                $list_all_svdesk[$row['age_id']][$row_s['sed_id']] = $row_s['sed_name'] . ' - ' . $row['age_name'];
            }
        }
        $db_service_desk = new db_query('SELECT * FROM service_desks WHERE sed_agency_id = ' . $configuration['con_default_agency']);
        $list_service_desks = array();
        while ($row = mysqli_fetch_assoc($db_service_desk->result)) {
            $list_service_desks[$row['sed_id']] = $row['sed_name'] . ' - ' . $list_agencies[$configuration['con_default_agency']];
        }

        $this->add(
            $this->form->select(array(
                'label' => 'Cửa hàng',
                'name' => 'agency_list',
                'id' => 'agency_list',
                'option' => $list_agencies,
                'extra' => 'onchange=loadServiceDesk()',
                'selected' => $configuration['con_default_agency'],
                'require'=>1,
                'errorMsg'=>'Bạn chưa chọn cửa hàng'
            ))
        );

        $this->add(
            $this->form->select(array(
                'label' => 'Quầy phục vụ',
                'name' => 'sec_service_desk',
                'id' => 'sec_service_desk',
                'option' => $list_service_desks,
                'selected' => $row_section['sec_service_desk'],
                'require'=>1,
                'errorMsg'=>'Bạn chưa chọn quầy phục vụ'
            ))
        );
        //Thêm javascript
        $this->add('<script>var list_service_desk = ' . json_encode($list_all_svdesk) . '</script>');

        $this->add(
            $this->form->text(array(
                'label' => 'Tên khu vực',
                'name' => 'sec_name',
                'id' => 'sec_name',
                'value' => $row_section['sec_name'],
                'require' => 1,
                'errorMsg' => 'Bạn chưa nhập tên khu vực'
            ))
        );
        $this->add(
            $this->form->textarea(array(
                'label' => 'Ghi chú',
                'name' => 'sec_note',
                'id' => 'sec_note',
                'value' => $row_section['sec_note']
            ))
        );
        //close modal
        $this->closeModal('edit_section', $sec_id);

    }

    function deleteSection()
    {
        //xóa khu vực
        $sec_id = getValue('sec_id', 'int', 'POST', 0);
        //check quyền xóa
        checkPermission('trash');
        $array_return = array();
        $db_data = new db_query('SELECT * FROM sections WHERE sec_id = ' . $sec_id . ' LIMIT 1');
        $section_data = mysqli_fetch_assoc($db_data->result);
        unset($db_data);
        move2trash('sec_id', $sec_id, 'sections', $section_data);
        $array_return = array('success' => 1);
        die(json_encode($array_return));
    }

    function getListDesk()
    {
        global $configuration;
        $sec_id = getValue('sec_id', 'str', 'POST', '');
        switch ($sec_id) {
            case 'all':
                $db_query = new db_query('SELECT sec_id,sec_name
                                          FROM sections
                                          LEFT JOIN service_desks ON sed_id = sec_service_desk
                                          WHERE sed_agency_id = ' . $configuration['con_default_agency']);
                while ($row = mysqli_fetch_assoc($db_query->result)) {
                    ?>
                    <div class="section-name bold"><?= $row['sec_name'] ?></div>
                    <?
                    $db_desk = new db_query('SELECT * FROM desks WHERE des_sec_id = ' . $row['sec_id']);
                    while ($row_desk = mysqli_fetch_assoc($db_desk->result)) {
                        ?>
                        <div class="col-sm-2 desk-item menu-normal" id="record_<?= $row_desk['des_id'] ?>"
                             onclick="active_desk(this)" data-record_id="<?= $row_desk['des_id'] ?>">
                            <?= $row_desk['des_name'] ?>(ID:<?= $row_desk['des_id'] ?>)
                        </div>
                    <?
                    }?>
                    <div class="clearfix"></div>
                <?
                }
                break;
            case 'trash':
                $list_desk = trash_list('desks');
                echo '<div class="section-name bold">Thùng rác</div>';
                foreach ($list_desk as $row_desk) {
                    ?>
                    <div class="col-sm-2 desk-item menu-trash" id="record_<?= $row_desk['des_id'] ?>"
                         onclick="active_desk(this)" data-record_id="<?= $row_desk['des_id'] ?>">
                        <?= $row_desk['des_name'] ?>(ID:<?= $row_desk['des_id'] ?>)
                    </div>
                <?
                }
                break;
            default :
                $sec_id = getValue('sec_id', 'int', 'POST', 0);
                ?>
                <div class="section-name bold">Danh sách:</div>
                <?
                $db_desk = new db_query('SELECT * FROM desks WHERE des_sec_id = ' . $sec_id);
                while ($row_desk = mysqli_fetch_assoc($db_desk->result)) {
                    ?>
                    <div class="col-sm-2 desk-item menu-normal" id="record_<?= $row_desk['des_id'] ?>"
                         onclick="active_desk(this)" data-record_id="<?= $row_desk['des_id'] ?>">
                        <?= $row_desk['des_name'] ?>(ID:<?= $row_desk['des_id'] ?>)
                    </div>
                <?
                }
                break;
        }
    }

    function loadFormAddDesk()
    {
        global $configuration;
        //mảng khu vực bàn ăn
        $array_section = array('' => ' - Chọn khu vực - ');
        $db_sec = new db_query('SELECT *
                                FROM sections
                                LEFT JOIN service_desks ON sed_id = sec_service_desk
                                WHERE sed_agency_id = ' . $configuration['con_default_agency']);
        while ($row = mysqli_fetch_assoc($db_sec->result)) {
            $array_section[$row['sec_id']] = $row['sec_name'];
        }
        //open modal
        $this->openModal();

        $this->add(
            $this->form->text(array(
                'label' => 'Nhập tên bàn',
                'name' => 'des_name',
                'id' => 'des_name',
                'require' => 1,
                'errorMsg' => 'Bạn chưa nhập tên bàn ăn'
            ))
        );
        $this->add(
            $this->form->select(array(
                'label' => 'Chọn khu vực',
                'name' => 'des_sec_id',
                'id' => 'des_sec_id',
                'option' => $array_section,
                'require'=>1,
                'errorMsg'=>'Bạn chưa chọn khu vực'
            ))
        );
        $this->add(
            $this->form->textarea(array(
                'label' => 'Ghi chú',
                'name' => 'des_note',
                'id' => 'des_note'
            ))
        );
        //close modal
        $this->closeModal('add_desk');
    }

    function loadFormEditDesk()
    {
        global $configuration;
        $des_id = getValue('des_id', 'int', 'POST', 0);
        //mảng khu vực bàn ăn
        $array_section = array('' => ' - Chọn khu vực - ');
        $db_sec = new db_query('SELECT *
                                FROM sections
                                LEFT JOIN service_desks ON sed_id = sec_service_desk
                                WHERE sed_agency_id = ' . $configuration['con_default_agency']);
        while ($row = mysqli_fetch_assoc($db_sec->result)) {
            $array_section[$row['sec_id']] = $row['sec_name'];
        }
        //lấy ra record cần sửa đổi
        $db_data = new db_query("SELECT * FROM desks WHERE des_id = " . $des_id);
        if ($row = mysqli_fetch_assoc($db_data->result)) {
            foreach ($row as $key => $value) {
                $$key = $value;
            }
        } else {
            exit();
        }
        //open modal
        $this->openModal();

        $this->add(
            $this->form->text(array(
                'label' => 'Nhập tên bàn',
                'name' => 'des_name',
                'id' => 'des_name',
                'require' => 1,
                'errorMsg' => 'Bạn chưa nhập tên bàn ăn',
                'value' => $row['des_name']
            ))
        );
        $this->add(
            $this->form->select(array(
                'label' => 'Chọn khu vực',
                'name' => 'des_sec_id',
                'id' => 'des_sec_id',
                'option' => $array_section,
                'selected' => $row['des_sec_id'],
                'require'=>1,
                'errorMsg'=>'Bạn chưa chọn khu vực'
            ))
        );
        $this->add(
            $this->form->textarea(array(
                'label' => 'Ghi chú',
                'name' => 'des_note',
                'id' => 'des_note',
                'value' => $row['des_note']
            ))
        );
        //close modal
        $this->closeModal('edit_desk', $des_id);
    }

    function deleteDesk()
    {
        //xóa bàn
        $des_id = getValue('des_id', 'int', 'POST', 0);
        checkPermission('trash');
        $db_data = new db_query('SELECT * FROM desks WHERE des_id = ' . $des_id . ' LIMIT 1');
        $desk_data = mysqli_fetch_assoc($db_data->result);
        unset($db_data);
        move2trash('des_id', $des_id, 'desks', $desk_data, $this->option_filter);
        $array_return = array('success' => 1);
        die(json_encode($array_return));
    }
}

$deskajax = new DeskAjax();
$deskajax->execute();
