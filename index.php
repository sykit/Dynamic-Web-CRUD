<?php
include_once 'dbcon.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php?access_denied");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Multiple Insert, Update, Delete(CRUD) using PHP & MySQLi</title>
        <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css" type="text/css" />
        <script src="../assets/jquery.js" type="text/javascript"></script>
        <script src="../assets/js-script.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                var max_fields = 10;
                var wrapper = $("#tbody");
                var check = $("#check");
                var add_button = $(".add_more");

                var x = 0;
                $(add_button).click(function (e) {
                    e.preventDefault();
                    if (x < max_fields) {
                        x++;
                        $(wrapper).append('<tr>'+
                        '<td hidden><input  type="checkbox" name="chk[]" class="chk-box" value="' + x + '"  /><input type="hidden" name="total[]" value="' + x + '" /></td>'+
                        '<td><input type="text" name="fname[]" placeholder="first name" class="form-control" /></td>'+
                        '<td><input type="text" name="lname[]" placeholder="last name" class="form-control" /></td>'+
                        '<td><select name="gender[]" placeholder="gender" class="form-control">'+
                          '<option value="Male">Male</option>'+
                          '<option value="Female">Female</option>'+
                        '</select></td>'+
                        '<td><input type="text" name="email[]" placeholder="email" class="form-control"/></td>'+
                        '<td><select name="grup[]" placeholder="grup" class="form-control">'+
                          '<option value="Business Solusion">Business Solusion</option>'+
                          '<option value="Product Solution" >Product Solution</option>'+
                          '<option value="Internet & Support">Internet & Support</option>'+
                        '</select></td>'+
                        '<td><select name="position[]" placeholder="position" class="form-control">'+
                          '<option value="Newcomer">Newcomer</option>'+
                          '<option value="Member" >Member</option>'+
                          '<option value="Leader" >Leader</option>'+
                          '<option value="Manager" >Manager</option>'+
                        '</select></td>'+
                        '<td><a href="#" class="remove_new glyphicon glyphicon-remove"></a></td></tr>');
                    } else
                    {
                        alert('You Reached the limits')
                    }
                });

                $(wrapper).on("click", ".remove_field", function (e) {
                    e.preventDefault();
                    $(this).closest('tr').find('input[type="checkbox"]').prop('checked', true);
                    $(this).closest('tr').attr("hidden", "true");
                });
                $(wrapper).on("click", ".remove_new", function (e) {
                    e.preventDefault();
                    $(this).closest('tr').remove();
                    x--;
                });
            });

        </script>
    </head>

    <body>

        <div class="navbar navbar-default navbar-static-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <div class="float-right">
                        <a class="btn btn-large">Selamat Datang <?php echo $_SESSION['username']; ?></a>
                        <a class="btn btn-large" href="logout.php">Log Out ></a>
                    </div>

                </div>

            </div>
        </div>
        <div class="clearfix"></div>

        <div class="container">
            <button type="button" class="add_more btn btn-primary"name="button">Tambah</button>
        </div>

        <div class="clearfix"></div><br />

        <div class="container">
            <?php
            if (isset($msg)) {
                echo $msg;
            }
            ?>
            <form method="post" name="frm" action="update_mul.php">
                <table id="table1" class='table table-bordered table-responsive'>
                    <thead>
                        <tr>
                        <!-- <th><label><input type="checkbox" class="select-all" /></label></th> -->
                            <th>First Name</th>
                            <th>Last Name</th>
														<th>Gender</th>
                            <th>Email</th>
														<th>Grup</th>
                            <th>Posisi</th>
														<th></th>

                        </tr>
                    </thead>
                    <tbody id="tbody">
                        <?php
                        $query = "SELECT * FROM users, grup, position WHERE users.id = grup.id AND grup.id = position.id";
                        $records_per_page = 10;
                        $newquery = $crud->paging($query, $records_per_page);
                        $crud->dataview($newquery);
                        ?>
                    </tbody>
                    <tfoot>

                        <tr>
                            <td colspan="7">
                                <button type="submit" name="savemul" class="btn btn-primary"><i class="glyphicon glyphicon-edit"></i> &nbsp; Save </button>&nbsp;
                                <a href="index.php" class="btn btn-large btn-success"> <i class="glyphicon glyphicon-fast-backward"></i> &nbsp; Reload</a>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="7" align="center">
                                <div class="pagination-wrap">
<?php $crud->paginglink($query, $records_per_page); ?>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </form>
    </body>
</html>
