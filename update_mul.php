<?php
include_once 'dbcon.php';

if (isset($_POST['total'])) {

    $total = $_POST['total'];
    $que = "SELECT max(id) FROM users";
    $sql2 = $DB_con->prepare($que);
    $sql2->execute();
    $row = $sql2->fetch(PDO::FETCH_ASSOC);

    if (isset($row['max(id)'])) {
        $id = $row['max(id)'];
    } else {
        $id = 0;
    }

    $fn = $_POST['fname'];
    $ln = $_POST['lname'];
    $gn = $_POST['gender'];
    $em = $_POST['email'];
    $gr = $_POST['grup'];
    $ps = $_POST['position'];

    $dataInsert = array('first_name' => $fn, 'last_name' => $ln, 'gender' => $gn, 'email' => $em, 'grup' => $gr, 'posisi' => $ps);
    if ($crud->insert(json_encode($dataInsert), $id)) {
        ?>
        <script>
            alert('<?php echo count($fn) . " records was inserted !!!"; ?>');
        </script>
        <?php
    } else {
        ?>
        <script>
            alert('error while inserting , TRY AGAIN');
        </script>
        <?php
    }
}


if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $fn = $_POST['fn'];
    $ln = $_POST['ln'];
    $gn = $_POST['gn'];
    $em = $_POST['em'];
    $gr = $_POST['gr'];
    $ps = $_POST['ps'];

    $chkid = count($id);
    $dataUpdate = array('id' => $id, 'first_name' => $fn, 'last_name' => $ln, 'gender' => $gn, 'email' => $em, 'grup' => $gr, 'posisi' => $ps);

	  if ($crud->update(json_encode($dataUpdate))) {
        ?>
        <script>
            alert('Data was Saved !!!');
        </script>
        <?php
    } else {
        ?>
        <script>
            alert('Error while Saving , TRY AGAIN');
        </script>
        <?php
    }

    if (isset($_POST['chk'])) {
        $chk = $_POST['chk'];
        $dataDelete = array('id' => $chk);
        $sql1 = $crud->delete(json_encode($dataDelete));

        if ($crud->delete(json_encode($dataDelete))) {
            ?>
            <script>
                alert('<?php echo count($chk); ?> Records Was Deleted !!!');
            </script>
            <?php
        } else {
            ?>
            <script>
                alert('Error while Deleting , TRY AGAIN');

            </script>
            <?php
        }
    }
}
$que = "SELECT * FROM gender";
$sq = $DB_con->prepare($que);
$sq->execute();
while ($row2 = $sq->fetch(PDO::FETCH_ASSOC)) {
print_r($row2);
}

?>
<script>
    window.location.href = 'index.php';
</script>
