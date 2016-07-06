<?
abstract class AbstractAjax {
    //Biến dùng để lưu trữ các lời gọi ajax từ client
    protected $action;
    //Biến khai báo module id
    protected $module_id;
    //Biến khai báo module name
    protected $module_name;
    //Biến thông báo lỗi
    protected $bg_errorMsg;
    //Biến khai báo bảng dữ liệu của module
    protected $bg_table;
    //Biến khai báo trường id của bảng
    protected $id_field;
    //Biến khai báo trường danh mục của bảng
    protected $cat_field;
    //Biến khai báo loại danh mục
    protected $cat_type;
    //Biến khai báo tên bảng danh mục tương ứng
    protected $cat_table;
    //Biến khai báo mảng tiêu đề của modal
    protected $modal_title;

    //Biến lưu trữ kết quả cần build
    protected $ajax_result = '';
    //Biến lưu trữ các biến lấy từ csdl
    protected $f = array();

    //Biến lưu trữ option_filter cho trash
    protected $option_filter = '';

    //Biến lưu trữ class form
    public $form;
    protected $form_isopen = false;
    //Biến lưu trữ class dataGrid
    public $list;
    public $list_column = '';

    public function __construct() {
        $this->action = getValue('action','str','POST',3);
        //Lưu trữ các biến global vào trong các tham số tương ứng
        global $module_id, $module_name, $bg_errorMsg, $bg_table, $id_field, $cat_field, $cat_type, $cat_table, $modal_title;
        //Gán giá trị tương ứng
        if(isset($module_id)) {
            $this->module_id = $module_id;
        }
        if(isset($module_name)) {
            $this->module_name = $module_name;
        }
        if(isset($bg_errorMsg)) {
            $this->module_id = $bg_errorMsg;
        }
        if(isset($bg_table)) {
            $this->bg_table = $bg_table;
        }
        if(isset($id_field)) {
            $this->id_field = $id_field;
        }
        if(isset($cat_field)) {
            $this->cat_field = $cat_field;
        }
        if(isset($cat_type)) {
            $this->cat_type = $cat_type;
        }
        if(isset($cat_table)) {
            $this->cat_table = $cat_table;
        }
        if(isset($modal_title)) {
            $this->modal_title = $modal_title;
        }
        //Khởi tạo form
        $this->form = new form();
        //Khởi tạo data grid
        $this->list = new dataGrid($this->id_field,30);
    }
    public function openModal($extra = '') {
        $html = '';
        if(isset($this->modal_title[$this->action])) {
            $html .= mini_modal_open($this->modal_title[$this->action],$extra);
        }else{
            trigger_error('Chưa khai báo tiêu đề chức năng ' . $this->action);
            $html .= mini_modal_open('',$extra);
        }

        $html .= $this->form->form_open();
        $html .= $this->form->textnote('Các trường có dấu * là bắt buộc');
        $this->ajax_result .= $html;
        $this->form_isopen = true;
    }

    public function closeModal($action_modal, $record_id = 0) {
        if($this->form_isopen == false) {
            trigger_error('Form chua duoc open',E_WARNING);
        }
        $html = '';
        $html .= $this->form->form_action(array(
            'label'=>array('<i class="fa fa-save"></i> Lưu lại'),
            'type'=>array('submit'),
            'extra'=>array('')
        ));
        $html .= $this->form->hidden(array(
            'name'=>'action_modal',
            'id'=>'action_modal',
            'value'=>$action_modal
        ));
        if($record_id) {
            $html .= $this->form->hidden(array(
                'name'=>'record_id',
                'id'=>'record_id',
                'value'=>$record_id
            ));
        }
        $html .= $this->form->form_close();
        //cấp phát autonumeric
        $html .= mini_modal_close($this->initAutoNumeric());
        $html .= '<script>
                    if($(\'.modal-mini-content\').find(\'.enscroll-track\').length < 1) {
                        $(\'.modal-mini-content\').enscroll({
                            addPaddingToPane : false
                        });
                    }
                </script>';

        $this->ajax_result .= $html;
        $this->form_isopen = false;
    }

    public function openModalNoForm($extra = '') {
        $html = '';
        if(isset($this->modal_title[$this->action])) {
            $html .= mini_modal_open($this->modal_title[$this->action],$extra);
        }else{
            trigger_error('Chưa khai báo tiêu đề chức năng ' . $this->action);
            $html .= mini_modal_open('',$extra);
        }
        $this->add($html);
    }

    public function closeModalNoForm() {
        $html = '';
        $html .= mini_modal_close($this->initAutoNumeric());
        $this->add($html);
    }

    public function initAutoNumeric() {
        return 'if($(\'[data-role="auto-numeric"]\').length){
                                        $(\'[data-role="auto-numeric"]\').autoNumeric(\'init\',{lZero:\'deny\'});
                                    }';
    }

    public function add($string = '') {
        if(is_array($string)) {
            $string = json_encode($string);
        }
        $this->ajax_result .= $string;
        return $this;
    }

    public function openMindows($return_string = false) {
        $html = '<div class="mwindow-wrapper">';
        if(isset($this->modal_title[$this->action])) {
            $html .= '<div class="mwindow-header"><label>'.$this->modal_title[$this->action].'</label><span class="mwindow-close">×</span></div>';
        }else{
            trigger_error('Chưa khai báo tiêu đề của '.$this->action);
            $html .= '<div class="mwindow-header"><span class="mwindow-close">×</span></div>';
        }
        if($return_string) {
            return $html;
        }
        $this->add($html);
    }

    public function closeMindows($return_string = false) {
        $html = '';
        //cấp phát draggable cho windows
        $html .= '</div><script type="text/javascript">
                        $(\'.mwindow\').draggable({
                            handle : \'.mwindow-header\',
                            containment : \'#m-window\'
                        })
                    </script>';
        if($return_string) {
            return $html;
        }
        $this->add($html);
    }

    //hàm tính số lượng tồn kho thực đơn
    function calculateInventoryMenu($list_menu = array()) {
        /**
         * số lượng tồn của thực đơn được tính toán dựa trên công thức chế biến của thực đơn và số lượng tồn của nguyên liệu trong kho hàng
         * trường lưu dữ liệu số lượng tồn của thực đơn là men_quantity
         * mặc định giá trị men_quantity = 0. Mỗi lần tính số lượng tồn thực đơn từ nguyên liệu ta sẽ update số này vào bảng menu
         * */
        global $configuration;
        //lấy ra công thức của thực đơn
        if(is_numeric($list_menu) || is_string($list_menu)) {
            //trường hợp tham số truyền vào là 1 menu
            $men_id = intval($list_menu);
            $db_mep = new db_query('SELECT *
                                    FROM menu_products
                                    LEFT JOIN product_quantity ON product_id = mep_product_id
                                    WHERE mep_menu_id = ' . intval($men_id) . '
                                    AND store_id = ' . $configuration['con_default_store']);
            $quantity = array();
            while($row = mysqli_fetch_assoc($db_mep->result)) {
                if($row['mep_quantity'] > 0) {
                    $quantity[] = floor($row['pro_quantity']/$row['mep_quantity']);
                }else {
                    $quantity[] = 0;
                }
            }
            unset($db_mep);
            if($quantity) {
                return min($quantity);
            }else{
                return 0;
            }

        }else{
            //trường hợp tham số truyền vào là list menu
            $quantity = array();
            foreach($list_menu as $men_id) {
                $temp = array();
                $db_mep = new db_query('SELECT *
                                        FROM menu_products
                                        LEFT JOIN product_quantity ON product_id = mep_product_id
                                        WHERE mep_menu_id = ' . intval($men_id) . '
                                        AND store_id = ' . $configuration['con_default_store']);

                while($row = mysqli_fetch_assoc($db_mep->result)) {
                    if($row['mep_quantity'] > 0) {
                        $temp[] = floor($row['pro_quantity']/$row['mep_quantity']);
                    }else {
                        $temp[] = 0;
                    }
                }
                unset($db_mep);
                if($temp) {
                    $quantity[$men_id] = min($temp);
                }else{
                    $quantity[$men_id] = 0;
                }

            }
            return $quantity;
        }
    }
    public function calculateMoneyBill($list_menu = array(), $extra_fee, $discount, $vat) {
        $total_money_menu = 0;
        foreach($list_menu as $menu) {

        }
    }

    abstract function loadFormAddCategory();
    abstract function loadFormEditCategory();
    abstract function loadFormAddRecord();
    abstract function loadFormEditRecord();
    abstract function listRecord();
    abstract function searchRecord();
    abstract function deleteCategory();
    abstract function deleteRecord();
    abstract function terminalDeleteRecord();
    abstract function recoveryRecord();



    public function execute() {
        $function = $this->action;
        if(method_exists($this, $function)) {
            $this->$function();
            echo $this->ajax_result;
        }else{
            die('Method ajax not callable');
        }
    }
}