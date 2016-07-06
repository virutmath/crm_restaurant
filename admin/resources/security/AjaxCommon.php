<?
class AjaxCommon extends AbstractAjax{

    function _loadFormAddCategory()
    {
        $this->add();
    }
    function loadFormAddCategory (){
        // TODO: Implement loadFormAddCategory() method.
        //kiểm tra quyền add
        checkPermission('add');
        //open modal
        $this->openModal();
        //...add more and morestring
        $this->_loadFormAddCategory();
        //...
        //close modal
        $this->closeModal('add_category');
        $this->add();
    }


    function _loadFormEditCategory()
    {
        $this->add();
    }
    function loadFormEditCategory() {
        // TODO: Implement loadFormEditCategory() method.
        //kiểm tra quyền edit
        checkPermission('edit');
        //lấy ra cat_id cần chỉnh sửa
        $cat_id = getValue('cat_id','int','POST',0);
        //lấy ra data cat id
        if($this->cat_field && $this->cat_table != 'categories_multi') {
            $db_data = new db_query('SELECT * FROM '.$this->cat_table.' WHERE '.$this->cat_field.' = '.$cat_id.' LIMIT 1');
        }else{
            $db_data = new db_query('SELECT * FROM '.$this->cat_table.' WHERE cat_id = '.$cat_id.' LIMIT 1');
        }

        if($row = mysqli_fetch_assoc($db_data->result)){
            //extract($row);
            $this->f = $row;
        }else{
            exit();
        }
        //open modal
        $this->openModal();
        //...add more and morestring
        $this->_loadFormEditCategory();
        //...
        //close modal
        $this->closeModal('edit_category',$cat_id);
    }

    function _loadFormAddRecord() {
        $this->add();
    }
    function loadFormAddRecord()
    {
        // TODO: Implement loadFormAddRecord() method.
        //kiểm tra quyền add
        checkPermission('add');
        //open modal
        $this->openModal();
        //...add more and morestring
        $this->_loadFormAddRecord();
        //...
        //close modal
        $this->closeModal('add_record');
    }

    function _loadFormEditRecord() {
        $this->add();
    }
    function loadFormEditRecord()
    {
        // TODO: Implement loadFormEditRecord() method.
        //kiểm tra quyền edit
        checkPermission('edit');
        //lấy ra cat_id cần chỉnh sửa
        $record_id = getValue('record_id','int','POST',0);
        //lấy ra data cat id
        $db_data = new db_query('SELECT * FROM '.$this->bg_table.' WHERE '.$this->id_field.' = '.$record_id.' LIMIT 1');
        if($row = mysqli_fetch_assoc($db_data->result)){
            //extract($row);
            $this->f = $row;
        }else{
            exit();
        }
        //open modal
        $this->openModal();
        //...add more and morestring
        $this->_loadFormEditRecord();
        //...
        //close modal
        $this->closeModal('edit_record',$record_id);
    }


    function listRecord()
    {
        // TODO: Implement listRecord() method.
        $cat_id = getValue('cat_id','str','POST','');
        $html = '';
        $this->_listAdd();
        $class_context_menu = 'menu-normal';
        switch($cat_id){
            case 'all':
                $this->list->addHiddenHeader($this->cat_field,$cat_id);
                $db_count = new db_count('SELECT count(*) as count
                                          FROM '.$this->bg_table.'
                                          WHERE 1 '.$this->list->sqlSearch());
                $total = $db_count->total;unset($db_count);
                $sql = 'SELECT *
                        FROM '.$this->bg_table.'
                        WHERE 1 '.$this->list->sqlSearch().'
                        ORDER BY '.$this->list->sqlSort().' '.$this->id_field.' ASC
                        '.$this->list->limit($total);
                //echo $sql;
                $db_listing = new db_query($sql);
                $array_row = $db_listing->resultArray();unset($db_listing);
                break;
            case 'trash':
                $this->list->addHiddenHeader($this->cat_field,$cat_id);
                $class_context_menu = 'menu-trash';
                $db_count = new db_count('SELECT count(*) as count
                            FROM trash
                            WHERE tra_table = "'.$this->bg_table.'"');
                $total = $db_count->total;unset($db_count);
                $array_row = trash_list($this->bg_table);
                $this->list->limit($total);
                break;
            default :
                $cat_id = (int)$cat_id;
                $this->list->addHiddenCondition($this->cat_field,$cat_id);
                $db_count = new db_count('SELECT count(*) as count
                                          FROM '.$this->bg_table.'
                                          WHERE 1 '.$this->list->sqlSearch() .'
                                          AND '.$this->cat_field.' = '. $cat_id);
                $total = $db_count->total;unset($db_count);
                $db_listing = new db_query('SELECT *
                                            FROM '.$this->bg_table.'
                                            WHERE 1 '.$this->list->sqlSearch().'
                                            AND '.$this->cat_field.' = '. $cat_id.'
                                            ORDER BY '.$this->list->sqlSort().' '.$this->id_field.' ASC
                                            '.$this->list->limit($total));
                $array_row = $db_listing->resultArray();unset($db_listing);
                break;
        }
        $total_row = count($array_row);
        $html .= $this->list->showHeader($total_row);
        $i = 0;
        foreach($array_row as $row){
            $list_column = $this->_listColumn($row);
            $i++;
            $html .= $this->list->start_tr($i,$row[$this->id_field],'class="'.$class_context_menu.' record-item" onclick="active_record('.$row[$this->id_field].')" data-record_id="'.$row[$this->id_field].'"');
            $html .= $list_column;
            $html .= $this->list->end_tr();
        }
        $html .= $this->list->showFooter();

        $this->add($html);
    }

    /**
     * Hàm này có chức năng thêm các field vào đối tượng list
     * @return $this->list
     */
    function _listAdd() {
        //ghi đè listAdd ở đây
        return $this->list;
    }


    /**
     * Có chức năng tác động vào list column
     * @param array $row
     * @return string
     */
    function _listColumn($row = array()) {
        return '';
    }

    function deleteCategory()
    {
        // TODO: Implement deleteCategory() method.
        //kiểm tra quyền xóa
        checkPermission('trash');
        $cat_id = getValue('cat_id','int','POST',0);
        $db_data = new db_query('SELECT * FROM '.$this->cat_table.' WHERE cat_id = '.$cat_id .' LIMIT 1');
        $array_data = mysqli_fetch_assoc($db_data->result);unset($db_data);
        if($array_data){
            move2trash('cat_id',$cat_id,$this->cat_table,$array_data, $this->option_filter);
            $array_return = array('success'=>1);
        }else{
            exit();
        }
        $this->add(json_encode($array_return));
    }

    function deleteRecord()
    {
        // TODO: Implement deleteRecord() method.
        //kiểm tra quyền xóa
        checkPermission('trash');
        $record_id = getValue('record_id','int','POST',0);
        $db_data = new db_query('SELECT * FROM '.$this->bg_table.' WHERE '.$this->id_field.' = '.$record_id .' LIMIT 1');
        $array_data = mysqli_fetch_assoc($db_data->result);unset($db_data);
        if($array_data){
            move2trash($this->id_field,$record_id,$this->bg_table,$array_data, $this->option_filter);
            $array_return = array('success'=>1);
        }else{
            exit();
        }
        $this->add(json_encode($array_return));
    }

    function terminalDeleteRecord()
    {
        // TODO: Implement terminalDeleteRecord() method.
        //kiểm tra quyền xóa vĩnh viễn
        checkPermission('delete');
        $record_id = getValue('record_id','int','POST',0);
        //xóa hoàn toàn
        terminal_delete($record_id,$this->bg_table);
        $array_return = array('success'=>1);
        $this->add(json_encode($array_return));
    }

    function recoveryRecord()
    {
        // TODO: Implement recoveryRecord() method.
        //kiểm tra quyền khôi phục
        checkPermission('recovery');
        $record_id = getValue('record_id','int','POST',0);
        //phục hồi dữ liệu
        $result = trash_recovery($record_id,$this->bg_table);
        if($result){
            $array_return = array('success'=>1);
        }else{
            $array_return = array('success'=>0,'error'=>'Khôi phục không thành công');
        }
        $this->add(json_encode($array_return));
    }

    function searchRecord()
    {
        // TODO: Implement searchRecord() method.
        //Hàm tìm kiếm ở header
        $cat_id = getValue($this->cat_field,'str','GET','');
        $html = '';
        $this->_listAdd();
        $class_context_menu = 'menu-normal';
        switch($cat_id){
            case 'all':
                $this->list->addHiddenHeader($this->cat_field,$cat_id);
                $db_count = new db_count('SELECT count(*) as count
                                          FROM '.$this->bg_table.'
                                          WHERE 1 '.$this->list->sqlSearch());
                $sql = 'SELECT count(*) as count
                                          FROM '.$this->bg_table.'
                                          WHERE 1 '.$this->list->sqlSearch();
                $total = $db_count->total;unset($db_count);
                $db_listing = new db_query('SELECT *
                            FROM '.$this->bg_table.'
                            WHERE 1 '.$this->list->sqlSearch().'
                            ORDER BY '.$this->list->sqlSort().' '.$this->id_field.' ASC
                            '.$this->list->limit($total));
                $array_row = $db_listing->resultArray();unset($db_listing);
                break;
            case 'trash':
                $this->list->addHiddenHeader($this->cat_field,$cat_id);
                $class_context_menu = 'menu-trash';
                $db_count = new db_count('SELECT count(*) as count
                            FROM trash
                            WHERE tra_table = "'.$this->bg_table.'"');
                $total = $db_count->total;unset($db_count);
                $array_row = trash_list($this->bg_table);
                $this->list->limit($total);
                break;
            default :
                $cat_id = (int)$cat_id;
                $this->list->addHiddenCondition($this->cat_field,$cat_id,'int');
                $db_count = new db_count('SELECT count(*) as count
                                          FROM '.$this->bg_table.'
                                          WHERE 1 '.$this->list->sqlSearch() .'
                                          AND '.$this->cat_field.' = '. $cat_id);
                $total = $db_count->total;unset($db_count);
                $db_listing = new db_query('SELECT *
                                            FROM '.$this->bg_table.'
                                            WHERE 1 '.$this->list->sqlSearch().'
                                            AND '.$this->cat_field.' = '. $cat_id.'
                                            ORDER BY '.$this->list->sqlSort().' '.$this->id_field.' ASC
                                            '.$this->list->limit($total));
                $array_row = $db_listing->resultArray();unset($db_listing);
                break;
        }
        $total_row = count($array_row);
        $html .= $this->list->showHeader($total_row);
        $i = 0;
        foreach($array_row as $row){
            $list_column = $this->_listColumn($row);
            $i++;
            $html .= $this->list->start_tr($i,$row[$this->id_field],'class="'.$class_context_menu.' record-item" onclick="active_record('.$row[$this->id_field].')" data-record_id="'.$row[$this->id_field].'"');
            $html .= $list_column;
            $html .= $this->list->end_tr();
        }
        $html .= $this->list->showFooter();
        $this->add($html);
    }
}