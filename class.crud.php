<?php

class crud {

    private $db;

    function __construct($DB_con) {
        $this->db = $DB_con;
    }

    public function insert($insert, $id) {
        try {
            $arr = json_decode($insert, true);
            // $id = $arr['id'][0];
            for ($i = 0; $i < count($arr['first_name']); $i++) {
                $id++;
                $sql = $this->db->prepare("INSERT INTO users(id,first_name,last_name,gender,email) VALUES(:id, :fname, :lname, :gender, :email); INSERT INTO grup(id,grup) VALUES (:id, :grup); INSERT INTO position VALUES (:id, :posisi)");
                $sql->bindparam(":fname", $arr['first_name'][$i]);
                $sql->bindparam(":lname", $arr['last_name'][$i]);
                $sql->bindparam(":gender", $arr['gender'][$i]);
                $sql->bindparam(":email", $arr['email'][$i]);
                $sql->bindparam(":grup", $arr['grup'][$i]);
                $sql->bindparam(":posisi", $arr['posisi'][$i]);
                $sql->bindparam(":id", $id);
                $sql->execute();
            }


            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getID($id) {
        $sql = $this->db->prepare("SELECT * FROM users WHERE id=:id");
        $sql->execute(array(":id" => $id));
        $editRow = $sql->fetch(PDO::FETCH_ASSOC);
        return $editRow;
    }

    public function update($update) {
        try {
            $arr = json_decode($update, true);
						// print_r($arr);
            for ($i = 0; $i < count($arr['id']); $i++) {
                $sql = $this->db->prepare("UPDATE users SET first_name=:fname, last_name=:lname, gender=:gender, email=:email WHERE id=:id; UPDATE grup SET grup=:grup WHERE id=:id; UPDATE position SET posisi=:posisi WHERE id=:id;");
                $sql->bindparam(":fname", $arr['first_name'][$i]);
                $sql->bindparam(":lname", $arr['last_name'][$i]);
                $sql->bindparam(":gender", $arr['gender'][$i]);
                $sql->bindparam(":email", $arr['email'][$i]);
                $sql->bindparam(":grup", $arr['grup'][$i]);
                $sql->bindparam(":posisi", $arr['posisi'][$i]);
                $sql->bindparam(":id", $arr['id'][$i]);

                $sql->execute();
            }


            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function delete($delete) {
        $arr = json_decode($delete, true);
        for ($i = 0; $i < count($arr['id']); $i++) {
            $sql = $this->db->prepare("DELETE FROM users WHERE id=:id; DELETE FROM grup WHERE id=:id;DELETE FROM position WHERE id=:id; ");
            $sql->bindparam(":id", $arr['id'][$i]);
            $sql->execute();
        }
        return true;
    }

    /* paging */

    public function dataview($query) {
        $sql = $this->db->prepare($query);
        $sql->execute();
        if ($sql->rowCount() > 0) {
            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                    <td hidden><input id="check" type="checkbox" name="chk[]" class="chk-box" value="<?php echo $row['id']; ?>"  /></td>
                <input type="hidden" name="id[]" value="<?php echo $row['id']; ?>" />
                <td><input type="text" name="fn[]" value="<?php echo $row['first_name']; ?>" class="form-control" /></td>
                <td><input type="text" name="ln[]" value="<?php echo $row['last_name']; ?>" class="form-control" /></td>
                <td><select name="gn[]" placeholder="gender" class='form-control'>
<!--
<?php
$sqlGender = $this->db->prepare("SELECT * FROM gender");
$sqlGender->execute();
while ($row2 = $sqlGender->fetch(PDO::FETCH_ASSOC)) {
?> -->

<!-- <option value="<?php echo $row2['gender']; ?>" <?php $row['gender'] == $row2['gender'] ? 'selected="selected"' : ''; ?>><?php echo $row2['gender']; ?></option> -->
<!-- <?php
}

 ?> -->


                <option value="Male" <?= $row['gender'] == 'Male' ? ' selected="selected"' : ''; ?>>Male</option>
                <option value="Female" <?= $row['gender'] == 'Female' ? ' selected="selected"' : ''; ?>>Female</option>
                    </select></td>
                <td><input type="text" name="em[]" value="<?php echo $row['email']; ?>" class="form-control" /></td>
                <td><select name="gr[]" placeholder="grup" class='form-control'>
                        <option value="Business Solusion" <?= $row['grup'] == 'Business Solusion' ? ' selected="selected"' : ''; ?>>Business Solusion</option>
                        <option value="Product Solution" <?= $row['grup'] == 'Product Solution' ? ' selected="selected"' : ''; ?>>Product Solution</option>
                        <option value="Internet & Support" <?= $row['grup'] == 'Internet & Support' ? ' selected="selected"' : ''; ?>>Internet & Support</option>
                    </select></td>
                <td><select name="ps[]" placeholder="position" class='form-control'>
                        <option value="Newcomer" <?= $row['posisi'] == 'Newcomer' ? ' selected="selected"' : ''; ?>>Newcomer</option>
                        <option value="Member" <?= $row['posisi'] == 'Member' ? ' selected="selected"' : ''; ?>>Member</option>
                        <option value="Leader" <?= $row['posisi'] == 'Leader' ? ' selected="selected"' : ''; ?>>Leader</option>
                        <option value="Manager" <?= $row['posisi'] == 'Manager' ? ' selected="selected"' : ''; ?>>Manager</option>
                    </select></td>
                <td><a href="#" class="remove_field glyphicon glyphicon-remove"></a></td>
                </tr>
                <?php
            }
        }
    }

    public function paging($query, $records_per_page) {
        $starting_position = 0;
        if (isset($_GET["page_no"])) {
            $starting_position = ($_GET["page_no"] - 1) * $records_per_page;
        }
        $query2 = $query . " limit $starting_position,$records_per_page";
        return $query2;
    }

    public function paginglink($query, $records_per_page) {

        $self = $_SERVER['PHP_SELF'];

        $sql = $this->db->prepare($query);
        $sql->execute();

        $total_no_of_records = $sql->rowCount();

        if ($total_no_of_records > 0) {
            ?><ul class="pagination"><?php
            $total_no_of_pages = ceil($total_no_of_records / $records_per_page);
            $current_page = 1;
            if (isset($_GET["page_no"])) {
                $current_page = $_GET["page_no"];
            }
            if ($current_page != 1) {
                $previous = $current_page - 1;
                echo "<li><a href='" . $self . "?page_no=1'>First</a></li>";
                echo "<li><a href='" . $self . "?page_no=" . $previous . "'>Previous</a></li>";
            }
            for ($i = 1; $i <= $total_no_of_pages; $i++) {
                if ($i == $current_page) {
                    echo "<li><a href='" . $self . "?page_no=" . $i . "' style='color:red;'>" . $i . "</a></li>";
                } else {
                    echo "<li><a href='" . $self . "?page_no=" . $i . "'>" . $i . "</a></li>";
                }
            }
            if ($current_page != $total_no_of_pages) {
                $next = $current_page + 1;
                echo "<li><a href='" . $self . "?page_no=" . $next . "'>Next</a></li>";
                echo "<li><a href='" . $self . "?page_no=" . $total_no_of_pages . "'>Last</a></li>";
            }
            ?></ul><?php
            }
        }

        /* paging */
    }
