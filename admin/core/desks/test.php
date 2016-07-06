<?
require_once 'inc_security.php';
$db_age = new db_query('SELECT * FROM agencies');
$list_agencies = array();
$list_service_desks = array();
while($row = mysqli_fetch_assoc($db_age->result)) {
    $list_agencies[$row['age_id']] = $row['age_name'];
    $db_sed = new db_query('SELECT * FROM service_desks WHERE sed_agency_id = ' . $row['age_id']);
    while($row_s = mysqli_fetch_assoc($db_sed->result)) {
        $list_service_desks[$row['age_id']][$row_s['sed_id']] = $row_s['sed_name'];
    }
}
echo json_encode($list_service_desks);

