<?php
include("includes/config.php");
include("includes/authentication.php");

define('TITLE', "Manage Students - GIZ Schools");
define('HEADER', "Students");
define('BREADCRUMB', "Students");
define('ICON', "users");

$success = $error = "";
$errors = array();

if(isset($_POST['addStudent'])){
    
    $fName = trim(stripslashes(mysqli_real_escape_string($con, $_POST['fName'])));
    $lName = trim(stripslashes(mysqli_real_escape_string($con, $_POST['lName'])));
    $email = trim(stripslashes(mysqli_real_escape_string($con, $_POST['email'])));
    $phoneNo = trim(stripslashes(mysqli_real_escape_string($con, $_POST['phoneNo'])));
    $dob = trim(stripslashes(mysqli_real_escape_string($con, $_POST['dob'])));
    $gender = trim(stripslashes(mysqli_real_escape_string($con, $_POST['gender'])));
    
    if(empty($fName)){ array_push($errors,$fNameErr = "You must enter a first name"); }
    if(empty($lName)){ array_push($errors,$lNameErr = "You must enter a last name"); }
    if(empty($email)){ array_push($errors,$emailErr = "You must enter a name"); }
    if(empty($phoneNo)){ array_push($errors,$phoneNoErr = "You must enter a name"); }
    if(empty($dob)){ array_push($errors,$dobErr = "You must select a date"); }
    if(empty($gender)){ array_push($errors,$genderErr = "You must select a gender"); }

    
     $emailCheck = mysqli_query($con, "SELECT * FROM students WHERE email = '$email'");
     if(mysqli_num_rows($emailCheck) > 0){
     array_push($errors,$emailErr = "");
     $error = $error."Sorry, Student with email '$email' already exists. Try again";
     }
    
    $randNo = mt_rand(000000,999999);
    $studentID = "GS-".$randNo;
    
    if(count($errors) == 0){
        $query = mysqli_query($con, "INSERT INTO students (studentID,fName,lName,email,phoneNo,dob,gender,dateAdded) VALUES('$studentID','$fName','$lName','$email','$phoneNo','$dob','$gender', NOW())");
        
        if($query){
            $success = "Student saved successfully";
        }
        else{
            $error = "Something went wrong. Try again later";
        }
    }
}

if(isset($_POST['updateStudent'])){
    $id = $_GET['id'];
    
    $studentname = trim(stripslashes(mysqli_real_escape_string($con, $_POST['studentname'])));
    $email = trim(stripslashes(mysqli_real_escape_string($con, $_POST['email'])));
    $phoneNo = trim(stripslashes(mysqli_real_escape_string($con, $_POST['phoneNo'])));
    $role = trim(stripslashes(mysqli_real_escape_string($con, $_POST['role'])));
    
    if(empty($studentname)){ array_push($errors,$studentnameErr = "You must enter a studentname"); }
    if(empty($email)){ array_push($errors,$emailErr = "You must enter a name"); }
    if(empty($phoneNo)){ array_push($errors,$phoneNoErr = "You must enter a name"); }
    if(empty($role)){ array_push($errors,$roleErr = "You must enter a name"); }
    
    if(count($errors) == 0){
        $query = mysqli_query($con, "UPDATE students SET phoneNo='$phoneNo', role=$role, dateUpdated=NOW() WHERE id=$id");
        
        if($query){
            $success = "Student updated successfully";
        }
        else{
            $error = "Something went wrong. Try again later";
        }
    }
}

if(isset($_POST['updateStudentPicture'])){
    $id = $_GET['id'];
    
    $studentPicture = $_FILES['studentPicture']['name'];
    
    $extension = substr($studentPicture,strlen($studentPicture)-4,strlen($studentPicture));
        
    $validExtensions = array(".png",".PNG",".jpg",".JPG");
    
    if(!in_array($extension,$validExtensions)){
        array_push($errors, $studentPictureErr = "Sorry, no file selected or the file format is not allowed. Only png/PNG/jpg/JPG is valid");
    }
    
    #Getting Student details from the database
    $getStudentData = mysqli_query($con, "SELECT * FROM students WHERE id=$id");
    $studentData = mysqli_fetch_array($getStudentData);
    $dbStudentID = $studentData['studentID'];
    $dbEmail = $studentData['email'];
    
    $newPictureName = md5($dbStudentID)."-".$dbStudentID.$extension;
    
    if(count($errors) == 0){
        $query = mysqli_query($con, "UPDATE students SET image='$newPictureName', dateUpdated=NOW() WHERE id=$id ");
        
        if($query){
            #moving the uploaded file to appropriate folder
            
            #check if file with the same name already exists
            if(file_exists("uploads/images/students/$newPictureName")) unlink("uploads/images/students/$newPictureName");
            
            #move the new file to the specified folder
            move_uploaded_file($_FILES['studentPicture']['tmp_name'], "uploads/images/students/".$newPictureName);
            
            $success = "Student picture updated successfully";
        }
        else{
            $error = "Something went wrong. Try again later";
        }
        
    }
    
        
}

if(isset($_GET['action']) && $_GET['action'] == "trash-student"){
    $id = $_GET['id'];
    
    $query = mysqli_query($con, "UPDATE students SET status='Trashed' WHERE id=$id");
        
    if($query){
        $success = "Student trashed successfully";
        header('refresh: 5; url = roles.php');
    }
    else{
        $error = "Something went wrong. Try again";
        header('refresh: 5; url = roles.php');
    }
}

if(isset($_GET['action']) && $_GET['action'] == "restore-student"){
    $id = $_GET['id'];
    
    $query = mysqli_query($con, "UPDATE students SET status='Active' WHERE id=$id");
        
    if($query){
        $success = "Student restored successfully";
        header('refresh: 5; url = roles.php');
    }
    else{
        $error = "Something went wrong. Try again";
        header('refresh: 5; url = roles.php');
    }
}

if(isset($_GET['action']) && $_GET['action'] == "delete-student"){
    $id = $_GET['id'];
    
    $query = mysqli_query($con, "DELETE FROM students WHERE id=$id");
        
    if($query){
        $success = "Student deleted successfully";
        header('refresh: 5; url = roles.php');
    }
    else{
        $error = "Something went wrong. Try again";
        header('refresh: 5; url = roles.php');
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
                        <?php if(isset($_GET['action']) && $_GET['action'] == "edit-student") : ?>
                        <section class="panel" id="edit-student">
                            <header class="panel-heading">
                                Edit Student

                                <span class="pull-right">
                                    <a href="students.php">
                                        <i class="fa fa-times text-danger"></i>
                                    </a>
                                </span>
                            </header>
                            <div class="panel-body">
                                <?php
                                $id = $_GET['id'];
                                $query = mysqli_query($con, "SELECT * FROM students WHERE id=$id");
                                $row = mysqli_fetch_array($query);
                                $studentname = $row['studentname'];
                                $email = $row['email'];
                                $phoneNo = $row['phoneNo'];
                                $role = $row['role'];
                                ?>
                                <form role="form" action="" method="post">
                                    <div class="form-group">
                                        <label for="">Studentname</label>
                                        <input type="text" name="studentname" class="form-control" id="" placeholder="Enter name" value="<?php if(isset($_POST['studentname'])){ echo $_POST['studentname']; } else{ echo $studentname; }?>" readonly>
                                        <span class="text-danger"><?php if(isset($studentnameErr)){ echo $studentnameErr; } ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Email</label>
                                        <input type="email" name="email" class="form-control" id="" placeholder="Enter email" value="<?php if(isset($_POST['email'])){ echo $_POST['email']; } else{ echo $email; }?>" readonly>
                                        <span class="text-danger"><?php if(isset($emailErr)){ echo $emailErr; } ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Phone No.</label>
                                        <input type="text" name="phoneNo" class="form-control" id="" placeholder="Enter name" value="<?php if(isset($_POST['phoneNo'])){ echo $_POST['phoneNo']; } else{ echo $phoneNo; }?>">
                                        <span class="text-danger"><?php if(isset($phoneNoErr)){ echo $phoneNoErr; } ?></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Role</label>
                                        <select name="role" id="" class="form-control">
                                            <option value="">-- Please select --</option>
                                            <?php
                                            $query = mysqli_query($con, "SELECT * FROM student_roles");
                                            while($row = mysqli_fetch_array($query)){
                                            $id = $row['id'];
                                            $name = $row['name'];
                                            ?>
                                            <option <?php if(isset($_POST['role']) && $_POST['role'] == $id || $role == $id){ echo "selected"; } ?> value="<?php echo $id; ?>"><?php echo $name; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger"><?php if(isset($roleErr)){ echo $roleErr; } ?></span>
                                    </div>

                                    <button type="submit" name="updateStudent" class="btn btn-primary">Update</button>
                                </form>
                            </div>
                        </section>
                        <?php elseif(isset($_GET['action']) && $_GET['action'] == "update-student-picture") : ?>
                        <section class="panel" id="update-student-picture">
                            <header class="panel-heading">
                                Update Student Picture
                            </header>
                            <div class="panel-body">
                                <form role="form" action="" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="">Picture</label>
                                        <input type="file" name="studentPicture" class="form-control" id="">
                                        <span class="text-danger"><?php if(isset($studentPictureErr)){ echo $studentPictureErr; } ?></span>
                                    </div>

                                    <button type="submit" name="updateStudentPicture" class="btn btn-primary">Update</button>
                                </form>

                            </div>
                        </section>
                        <?php else : ?>
                        <section class="panel" id="add-student">
                            <header class="panel-heading">
                                Add Student
                            </header>
                            <div class="panel-body">
                                <form role="form" action="" method="post">
                                    <div class="form-group">
                                        <label for="">First Name</label>
                                        <input type="text" name="fName" class="form-control" id="" placeholder="Enter name" value="<?php if(isset($_POST['fName'])){ echo $_POST['fName']; }?>">
                                        <span class="text-danger"><?php if(isset($fNameErr)){ echo $fNameErr; } ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Last Name</label>
                                        <input type="text" name="lName" class="form-control" id="" placeholder="Enter name" value="<?php if(isset($_POST['lName'])){ echo $_POST['lName']; }?>">
                                        <span class="text-danger"><?php if(isset($lNameErr)){ echo $lNameErr; } ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Birth Date</label>
                                        <input type="date" name="dob" class="form-control" id="" placeholder="Enter name" value="<?php if(isset($_POST['dob'])){ echo $_POST['dob']; }?>">
                                        <span class="text-danger"><?php if(isset($dobErr)){ echo $dobErr; } ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Email</label>
                                        <input type="email" name="email" class="form-control" id="" placeholder="Enter email" value="<?php if(isset($_POST['email'])){ echo $_POST['email']; }?>">
                                        <span class="text-danger"><?php if(isset($emailErr)){ echo $emailErr; } ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Phone No.</label>
                                        <input type="text" name="phoneNo" class="form-control" id="" placeholder="Enter name" value="<?php if(isset($_POST['phoneNo'])){ echo $_POST['phoneNo']; }?>">
                                        <span class="text-danger"><?php if(isset($phoneNoErr)){ echo $phoneNoErr; } ?></span>
                                    </div>


                                    <div class="form-group">
                                        <label for="">Gender</label>
                                        <select name="gender" id="" class="form-control">
                                            <option value="">-- Please select --</option>

                                            <option <?php if(isset($_POST['role']) && $_POST['role'] == "Male"){ echo "selected"; } ?> value="Male">Male</option>
                                            <option <?php if(isset($_POST['role']) && $_POST['role'] == "Female"){ echo "selected"; } ?> value="Female">Female</option>
                                            <option <?php if(isset($_POST['role']) && $_POST['role'] == "Bob Risky"){ echo "selected"; } ?> value="Bob Risky">Bob Risky</option>

                                        </select>
                                        <span class="text-danger"><?php if(isset($genderErr)){ echo $genderErr; } ?></span>
                                    </div>

                                    <button type="submit" name="addStudent" class="btn btn-success">Add</button>
                                </form>

                            </div>
                        </section>
                        <?php endif ?>

                    </div>
                    <div class="col-md-7">
                        <section class="panel">
                            <header class="panel-heading">
                                Manage Students
                            </header>

                            <table class="table table-striped table-advance table-hover">
                                <tbody>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Image</th>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Gender</th>
                                        <th>Phone No</th>
                                        <th>Date Added</th>
                                        <th>Date Updated</th>
                                        <th>Action</th>
                                    </tr>

                                    <?php
                                    $No = 1;
                                    $query = mysqli_query($con, "SELECT * FROM students");
                                    while($row = mysqli_fetch_array($query)){
                                    $id = $row['id'];
                                    $studentID = $row['studentID'];
                                    $name = $row['lName']." ".$row['fName'];
                                    $email = $row['email'];
                                    $phoneNo = $row['phoneNo'];
                                    $gender = $row['gender'];
                                    $image = $row['image'];
                                        
                                    $dateAdded = date("j F, Y h:iA", strtotime($row['dateAdded']));    
                                    $dateUpdated = date("j F, Y h:iA", strtotime($row['dateUpdated']));
                                        
                                    $dateUpdatedNull = $row['dateUpdated'];
                                    $status = $row['status'];
                                    
                                    ?>
                                    <tr>
                                        <td><?php echo $No++; ?></td>

                                        <td>
                                            <img src="uploads/images/students/<?php echo $image; ?>" alt="" class="img-circle" width="70" height="70">
                                        </td>

                                        <td><?php echo $studentID; ?></td>
                                        <td><?php echo $name; ?></td>
                                        <td><?php echo $email; ?></td>
                                        <td><?php echo $gender; ?></td>
                                        <td><?php echo $phoneNo; ?></td>
                                        <td><?php echo $dateAdded; ?></td>

                                        <?php if($dateUpdatedNull != "") : ?>
                                        <td><?php echo $dateUpdated; ?></td>
                                        <?php else : ?>
                                        <td></td>
                                        <?php endif ?>


                                        <td>
                                            <div class="btn-group">
                                                <a class="btn btn-primary" href="students.php?id=<?php echo $id; ?>&action=edit-student"><i class="fa fa-edit" title="Edit"></i></a>
                                                <a class="btn btn-default" href="students.php?id=<?php echo $id; ?>&action=update-student-picture"><i class="fa fa-picture-o" title="Update Student Picture"></i></a>

                                                <?php if($status == "Active" || $status == "") : ?>
                                                <a class="btn btn-warning" href="students.php?id=<?php echo $id; ?>&action=trash-student"><i class="fa fa-trash-o" title="Trash"></i></a>
                                                <?php else : ?>
                                                <a class="btn btn-success" href="students.php?id=<?php echo $id; ?>&action=restore-student"><i class="fa fa-recycle" title="Restore"></i></a>
                                                <a class="btn btn-danger" href="students.php?id=<?php echo $id; ?>&action=delete-student"><i class="fa fa-trash-o" title="Delete Permanently"></i></a>
                                                <?php endif ?>
                                            </div>
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
