<?php
include 'common.php';
if (isset($_POST['attchArray'])) {
    $attach_array = $_POST['attchArray'];
    foreach ($attach_array as  $value) {
        $sql = "UPDATE adr_attachments SET deleted= 1 WHERE id='$value' ";
        $result = sql_query($sql, "Delete attachment");
    }
    // $id = $_POST['attch_ment_id'];
    if ($result) {
        echo 'File Deleted';
    } else {
        echo mysql_error($CON);
    }
}
?>
