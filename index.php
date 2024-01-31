<?php
include("admin/includes/config.php"); 
define('TITLE', "Welcome - GIZ Schools");

?>
<!DOCTYPE html>
<html lang="en">



<head>
    <?php include("includes/head-data.php"); ?>
</head>

<body>

    <?php include("includes/preloader.php"); ?>


    <?php include("includes/header.php"); ?>


    <div id="header-slider" class="owl-carousel owl-theme carousel-controls">
        <div class="header-slider-content bg-img slide1" style="background-image: url(assets/images/img1-dark.jpg)">
            <div class="header-content text-center dark">
                <div class="w60 m-auto text-center">
                    <h1 class="mb-20 text-muted">GIZ SCHOOLS</h1>
                    <p class="mb-40 text-muted">Get quality and undiluted education without crossing the borders!</p>
                    <p class="mb-0">
                        <a href="#programs" class="btn btn-primary m-5"><span class="fa fa-check mr-2"></span> <span>GET STARTED</span></a>
                    </p>
                </div><!-- / w60 -->
            </div><!-- / header-content -->
        </div><!-- / header-slider-content slide1 -->
    </div><!-- / header-slider -->

    <div id="main-content">

        <section id="about">
            <div class="container">
                <div class="box-w-image promo-box pl-45 pr-45">
                    <div class="row">
                        <div class="col-md-5 bg-img box-bg-image tablet-top-30" style="background-image: url(assets/images/img32.jpg)">
                            <p class="mb-0"></p>
                        </div>
                        <div class="col-md-7 col-md-offset-5">
                            <div class="box-description pl-15">
                                <h6 class="mb-15">ABOUT US</h6>
                                <p class="mb-20">
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolorum nihil eligendi tempora nisi iusto nulla unde modi incidunt animi doloremque totam doloribus ducimus, aliquam ad quo placeat. Quas id praesentium commodi mollitia, eaque iusto, ex quaerat ipsam, impedit quis deleniti asperiores eligendi ullam veritatis officiis molestiae esse voluptatem eius, quidem et doloribus perspiciatis. Eveniet laudantium soluta omnis odit corrupti enim rerum facere at adipisci, alias. Minima sed totam expedita culpa nihil accusamus non eaque modi amet quo aliquam deleniti nostrum molestias quasi ipsam debitis ex facere animi architecto quam distinctio, esse harum et. Qui expedita perferendis libero distinctio quo consectetur dolor adipisci quas excepturi odio rerum dolorem, beatae cum aliquid officiis tempore similique provident dolore. Incidunt aliquid, quis tempora dignissimos.
                                </p>
                                <div class="row">
                                    <div class="col-md-4 mb-30">
                                        <?php
                                        $query = mysqli_query($con,"SELECT * FROM programs");
                                        $countRow = mysqli_num_rows($query);
                                        ?>
                                        <i class="ti ti-list ti-42 d-inline-block va-middle mr-20 text-warning"></i>
                                        <div class="d-inline-block va-middle">
                                            <h4 class="timer fw-regular mb-0 va-middle" id="count-one" data-to="<?php echo $countRow; ?>" data-speed="1500"><?php echo $countRow; ?></h4>
                                            <p class="mb-0 text-sm fw-regular text-primary">Programs</p>
                                        </div><!-- / d-inline-block -->
                                    </div><!-- / column -->
                                    <div class="col-md-4 mb-30">
                                        <?php
                                        $query = mysqli_query($con,"SELECT * FROM courses");
                                        $countRow = mysqli_num_rows($query);
                                        ?>
                                        <i class="ti ti-user ti-42 d-inline-block va-middle mr-20 text-warning"></i>
                                        <div class="d-inline-block va-middle">
                                            <h4 class="timer fw-regular mb-0 va-middle" id="count-two" data-to="<?php echo $countRow; ?>" data-speed="3000"><?php echo $countRow; ?></h4>
                                            <p class="mb-0 text-sm fw-regular text-success">Courses</p>
                                        </div><!-- / d-inline-block -->
                                    </div><!-- / column -->
                                    <div class="col-md-4 mb-30">
                                        <?php
                                        $query = mysqli_query($con, "SELECT staff_members.id, staff_members.staffID, staff_members.lName, staff_members.fName, staff_members.email, staff_members.phoneNo, staff_members.gender, staff_members.image, staff_members.dateAdded, staff_members.dateUpdated, staff_members.status, staff_categories.name AS category, designations.name AS designation FROM staff_members LEFT JOIN staff_categories ON staff_members.category=staff_categories.id LEFT JOIN designations ON staff_members.designation=designations.id WHERE designations.name='Trainer' ");
                                        $countRow = mysqli_num_rows($query);
                                        ?>
                                        <i class="ti ti-user ti-42 d-inline-block va-middle mr-20 text-warning"></i>
                                        <div class="d-inline-block va-middle">
                                            <h4 class="timer fw-regular mb-0 va-middle" id="count-three" data-to="<?php echo $countRow; ?>" data-speed="4500"><?php echo $countRow; ?></h4>
                                            <p class="mb-0 text-sm fw-regular text-warning">Trainers</p>
                                        </div><!-- / d-inline-block -->
                                    </div><!-- / column -->
                                </div><!-- /row -->

                            </div><!-- / box-description -->
                        </div><!-- / column -->
                    </div><!-- / row -->
                </div><!-- box-w-image -->
            </div><!-- / container -->
        </section><!-- / about -->


        <section id="programs" class="big bg-white">
            <div class="container">
                <div class="section-heading text-center">
                    <h2 class="mb-30">Our Programs</h2>
                    <p class="w50 mb-50 m-x-auto">Aenean luctus, quam eget elementum scelerisque, risus lectus auctor lorem, aliquam interdum ex risus sit amet ipsum. Aenean sollicitudin vel libero et mollis sed quam faucibus.</p>
                </div><!-- / section-heading -->
                <div class="row">
                    <?php
                    $query = mysqli_query($con, "SELECT * FROM programs LIMIT 6");
                    while($row = mysqli_fetch_array($query)){
                    $id = $row['id'];
                    $name = $row['name'];
                    $description = substr($row['description'],0,90);
                        
                    $nameSlug = explode(" ", $name);
                    $nameSlug = strtolower(implode("-", $nameSlug));

                    $dateAdded = date("j F, Y h:iA", strtotime($row['dateAdded']));    
                    $dateUpdated = date("j F, Y h:iA", strtotime($row['dateUpdated']));

                    $dateUpdatedNull = $row['dateUpdated'];
                    $status = $row['status'];

                    ?>
                    <div class="col-md-4">
                        <div class="promo-box text-center mb-0">
                            <i class="ti-palette promo-icon fs-36 d-block mb-25 text-primary"></i>
                            <h6 class="box-title mb-15 text-hover success-hover text-uppercase"><?php echo $name; ?></h6>
                            <p class="box-description"><?php echo $description; ?> .... <a href="programs.php?pId=<?php echo $id; ?>&prog=<?php echo $nameSlug; ?>">Read More</a></p>
                        </div><!-- / promo-box -->
                    </div><!-- / column -->
                    <?php } ?>


                </div><!-- / row -->
            </div><!-- / container -->
        </section><!-- / features -->



        <section id="trainers" class="lg pb-70 bg-white">
            <div class="container">
                <div class="section-heading text-center">
                    <h2 class="mb-15">Our Trainers</h2>
                    <div class="spacer-line border-primary mb-20">&nbsp;</div>
                    <p class="w50 mb-70 m-x-auto">Aenean luctus, quam eget elementum scelerisque, risus lectus auctor lorem, aliquam interdum ex risus. Quisque dui nisi, suscipit et ipsum.</p>
                </div><!-- / section-heading -->
                <div class="row">

                    <?php
                    $query = mysqli_query($con, "SELECT staff_members.id, staff_members.staffID, staff_members.lName, staff_members.fName, staff_members.email, staff_members.phoneNo, staff_members.gender, staff_members.image, staff_members.dateAdded, staff_members.dateUpdated, staff_members.status, staff_categories.name AS category, designations.name AS designation FROM staff_members LEFT JOIN staff_categories ON staff_members.category=staff_categories.id LEFT JOIN designations ON staff_members.designation=designations.id WHERE designations.name='Trainer' ");
                    while($row = mysqli_fetch_array($query)){
                    $id = $row['id'];
                    $staffID = $row['staffID'];
                    $name = $row['lName']." ".$row['fName'];
                    $email = $row['email'];
                    $phoneNo = $row['phoneNo'];
                    $gender = $row['gender'];
                    $category = $row['category'];
                    $designation = $row['designation'];
                    $image = $row['image'];

                    $dateAdded = date("j F, Y h:iA", strtotime($row['dateAdded']));    
                    $dateUpdated = date("j F, Y h:iA", strtotime($row['dateUpdated']));

                    $dateUpdatedNull = $row['dateUpdated'];
                    $status = $row['status'];

                    ?>

                    <div class="col-md-4">
                        <div class="card bg-transparent b-0">
                            <div class="card-body text-center p-30 pt-0">
                                <img class="mb-30 raised circle" src="admin/uploads/images/staff/<?php echo $image; ?>" style="height:300px" alt="">
                                <p class="mb-5 fs-10 fw-regular text-black text-uppercase"><?php echo $designation; ?></p>
                                <h6 class="card-title fs-22 mb-10 text-uppercase text-primary"><?php echo $name; ?></h6>
                                <p class="mb-0"><a href="#x" class="btn btn-xs btn-facebook btn-circle btn-icon m-1"><i class="fab fa-facebook-f"></i></a> <a href="#x" class="btn btn-xs btn-twitter btn-circle btn-icon m-1"><i class="fab fa-twitter"></i></a> <a href="#x" class="btn btn-xs btn-linkedin btn-circle btn-icon m-1"><i class="fab fa-linkedin-in"></i></a> <a href="#x" class="btn btn-xs btn-dribbble btn-circle btn-icon m-1"><i class="fab fa-dribbble"></i></a></p>
                            </div><!-- / card-body -->
                        </div><!-- / card -->
                    </div><!-- / column -->
                    <?php } ?>

                </div><!-- / row -->
            </div><!-- / container -->
        </section><!-- team -->

    </div>

    <?php include("includes/footer.php"); ?>





    <?php include("includes/scripts.php"); ?>

</body>


<!-- Mirrored from erikathemes.com/demos/erika/ by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 06 Jul 2020 00:55:42 GMT -->

</html>
