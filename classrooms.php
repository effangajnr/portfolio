<?php
include("includes/config.php");
include("includes/authentication.php");
include("includes/authorization.php");

define('TITLE', "Manage Classrooms - GIZ Classrooms");
define('HEADER', "Classrooms");
define('BREADCRUMB', "Classrooms");
define('ICON', "list-alt");

$success = $error = "";
$errors = array();

if(isset($_POST['addClassroom'])){
    
    $classroomName = trim(stripslashes(mysqli_real_escape_string($con, $_POST['classroomName'])));
    $classNo = trim(stripslashes(mysqli_real_escape_string($con, $_POST['classNo'])));
    
    if(empty($classroomName)){
        array_push($errors,$classroomNameErr = "You must enter a name");
    }
    
    if(empty($classNo)){
        array_push($errors,$classNoErr = "You must enter a classNo");
    }
    
    $classroomCheck = mysqli_query($con, "SELECT * FROM classrooms WHERE name = '$classroomName'");
    if(mysqli_num_rows($classroomCheck) > 0){
        array_push($errors,$classroomNameErr = "");
        $error = "Sorry, classroom '$classroomName' already exists. Try again";
    }
    
    if(count($errors) == 0){
        $query = mysqli_query($con, "INSERT INTO classrooms (name,classNo,dateAdded) VALUES('$classroomName', '$classNo', NOW())");
        
        if($query){
            $success = "Classroom saved successfully";
        }
        else{
            $error = "Something went wrong. Try again later";
        }
    }
}

if(isset($_POST['updateClassroom'])){
    $id = $_GET['id'];
    
    $classroomName = trim(stripslashes(mysqli_real_escape_string($con, $_POST['classroomName'])));
    $classNo = trim(stripslashes(mysqli_real_escape_string($con, $_POST['classNo'])));
    
    if(empty($classroomName)){
        array_push($errors,$classroomNameErr = "You must enter a name");
    }
    
    if(empty($classNo)){
        array_push($errors,$classNoErr = "You must enter a classNo");
    }
    

    $classroomCheck = mysqli_query($con, "SELECT * FROM classrooms WHERE name <> '$classroomName'");
    while($classrooms = mysqli_fetch_array($classroomCheck)){
        $dbClassroomName = $classrooms['name'];
    
        if($dbClassroomName === $classroomName){
            array_push($errors,$classroomNameErr = "");
            $error = "Sorry, classroom '$classroomName' already exists. Try again";
        }
    }
    
    
    if(count($errors) == 0){
        $query = mysqli_query($con, "UPDATE classrooms SET name='$classroomName', classNo='$classNo', dateUpdated=NOW() WHERE id=$id");
        
        if($query){
            $success = "Classroom updated successfully";
        }
        else{
            $error = "Something went wrong. Try again later";
        }
    }
}

if(isset($_GET['action']) && $_GET['action'] == "trash-classroom"){
    $id = $_GET['id'];
    
    $query = mysqli_query($con, "UPDATE classrooms SET status='Trashed' WHERE id=$id");
        
    if($query){
        $success = "Classroom trashed successfully";
        header('refresh: 5; url = classrooms.php');
    }
    else{
        $error = "Something went wrong. Try again";
        header('refresh: 5; url = classrooms.php');
    }
}

if(isset($_GET['action']) && $_GET['action'] == "restore-classroom"){
    $id = $_GET['id'];
    
    $query = mysqli_query($con, "UPDATE classrooms SET status='Active' WHERE id=$id");
        
    if($query){
        $success = "Classroom restored successfully";
        header('refresh: 5; url = classrooms.php');
    }
    else{
        $error = "Something went wrong. Try again";
        header('refresh: 5; url = classrooms.php');
    }
}

if(isset($_GET['action']) && $_GET['action'] == "delete-classroom"){
    $id = $_GET['id'];
    
    $query = mysqli_query($con, "DELETE FROM classrooms WHERE id=$id");
        
    if($query){
        $success = "Classroom deleted successfully";
        header('refresh: 5; url = classrooms.php');
    }
    else{
        $error = "Something went wrong. Try again";
        header('refresh: 5; url = classrooms.php');
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("includes/head-data.php"); ?>
</head>

<body>
    <!-- container section start -->
    <section id="container" class="">


        <?php include("includes/header.php"); ?>
        <!--header end-->

        <!--sidebar start-->
        <?php include("includes/aside.php"); ?>
        <!--sidebar end-->

        <!--main content start-->
        <section id="main-content">
            <section class="wrapper">
                <!--overview start-->
                <div id="page-header" class="row">
                    <?php include("includes/page-header.php"); ?>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <?php include("includes/alerts.php"); ?>
                    </div>
                </div>



                <div class="row">
                    <div class="col-md-5">
                        <?php if(isset($_GET['action']) && $_GET['action'] == "edit-classroom") : ?>
                        <section class="panel" id="edit-classroom">
                            <header class="panel-heading">
                                Edit Classroom

                                <span class="pull-right">
                                    <a href="classrooms.php">
                                        <i class="fa fa-times text-danger"></i>
                                    </a>
                                </span>
                            </header>
                            <div class="panel-body">
                                <?php
                                $id = $_GET['id'];
                                $query = mysqli_query($con, "SELECT * FROM classrooms WHERE id=$id");
                                $row = mysqli_fetch_array($query);
                                $name = $row['name'];
                                $classNo = $row['classNo'];
                                ?>
                                <form classroom="form" action="" method="post">
                                    <div class="form-group">
                                        <label for="">Name</label>
                                        <input type="text" name="classroomName" class="form-control" id="" placeholder="Enter name" value="<?php if(isset($_POST['classroomName'])){ echo $_POST['classroomName']; } else{ echo $name; } ?>">
                                        <span class="text-danger"><?php if(isset($classroomNameErr)){ echo $classroomNameErr; } ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Class Number</label>
                                        <input type="text" name="classNo" class="form-control" id="" placeholder="Enter name" value="<?php if(isset($_POST['classNo'])){ echo $_POST['classNo']; } else{ echo $classNo; }?>">
                                        <span class="text-danger"><?php if(isset($classNoErr)){ echo $classNoErr; } ?></span>
                                    </div>
                                    <button type="submit" name="updateClassroom" class="btn btn-primary">Update</button>
                                    <a href="classrooms.php" class="btn btn-danger">Cancel</a>
                                </form>

                            </div>
                        </section>
                        <?php else : ?>
                        <section class="panel" id="add-classroom">
                            <header class="panel-heading">
                                Add Classroom
                            </header>
                            <div class="panel-body">
                                <form classroom="form" action="" method="post">
                                    <div class="form-group">
                                        <label for="">Name</label>
                                        <input type="text" name="classroomName" class="form-control" id="" placeholder="Enter name" value="<?php if(isset($_POST['classroomName'])){ echo $_POST['classroomName']; }?>">
                                        <span class="text-danger"><?php if(isset($classroomNameErr)){ echo $classroomNameErr; } ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Class Number</label>
                                        <input type="text" name="classNo" class="form-control" id="" placeholder="Enter name" value="<?php if(isset($_POST['classNo'])){ echo $_POST['classNo']; }?>">
                                        <span class="text-danger"><?php if(isset($classNoErr)){ echo $classNoErr; } ?></span>
                                    </div>
                                    <button type="submit" name="addClassroom" class="btn btn-success">Add</button>
                                </form>

                            </div>
                        </section>
                        <?php endif ?>

                    </div>
                    <div class="col-md-7">
                        <section class="panel">
                            <header class="panel-heading">
                                Manage Classrooms
                            </header>

                            <table class="table table-striped table-advance table-hover">
                                <tbody>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Name</th>
                                        <th>Class No.</th>
                                        <th>Date Added</th>
                                        <th>Date Updated</th>
                                        <th>Status</th>
                                    </tr>

                                    <?php
                                    $No = 1;
                                    $query = mysqli_query($con, "SELECT * FROM classrooms");
                                    while($row = mysqli_fetch_array($query)){
                                    $id = $row['id'];
                                    $name = $row['name'];
                                    $classNo = $row['classNo'];
                                        
                                    $dateAdded = date("j F, Y h:iA", strtotime($row['dateAdded']));    
                                    $dateUpdated = date("j F, Y h:iA", strtotime($row['dateUpdated']));
                                        
                                    $dateUpdatedNull = $row['dateUpdated'];
                                    $status = $row['status'];
                                    
                                    ?>
                                    <tr>
                                        <td><?php echo $No++; ?></td>
                                        <td><?php echo $name; ?></td>
                                        <td><?php echo $classNo; ?></td>
                                        <td><?php echo $dateAdded; ?></td>

                                        <?php if($dateUpdatedNull != "") : ?>
                                        <td><?php echo $dateUpdated; ?></td>
                                        <?php else : ?>
                                        <td></td>
                                        <?php endif ?>


                                        <td>
                                            <?php if($userRole !== "User") : ?>
                                            <div class="btn-group">
                                                <a class="btn btn-primary" href="classrooms.php?id=<?php echo $id; ?>&action=edit-classroom"><i class="fa fa-edit" title="Edit"></i></a>

                                                <?php if($userRole !== "Admin") : ?>
                                                <?php if($status == "Active" || $status == "") : ?>
                                                <a class="btn btn-warning" href="classrooms.php?id=<?php echo $id; ?>&action=trash-classroom"><i class="fa fa-trash-o" title="Trash"></i></a>
                                                <?php else : ?>
                                                <a class="btn btn-success" href="classrooms.php?id=<?php echo $id; ?>&action=restore-classroom"><i class="fa fa-recycle" title="Restore"></i></a>
                                                <a class="btn btn-danger" href="classrooms.php?id=<?php echo $id; ?>&action=delete-classroom"><i class="fa fa-trash-o" title="Delete Permanently"></i></a>
                                                <?php endif ?>
                                                <?php endif ?>
                                            </div>
                                            <?php endif ?>
                                        </td>
                                    </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </section>
                    </div>
                </div>

            </section>

        </section>
        <!--main content end-->
    </section>
    <!-- container section start -->

    <!-- javascripts -->
    <?php include("includes/scripts.php"); ?>

</body>

</html>
