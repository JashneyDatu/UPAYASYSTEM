<?php
include "db/db_connect.php"; 

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM students WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc(); 
    } else {
        echo "<div class='alert alert-danger text-center'>Student not found!</div>";
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_no = $_POST['student_no']; 
    $fullname = $_POST['fullname']; 
    $course = $_POST['course']; 
    $year_lvl = $_POST['year_lvl']; 

    $sql = "UPDATE students 
            SET student_no='$student_no', fullname='$fullname', course='$course', year_lvl='$year_lvl'
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success text-center'>Student updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>Error: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">Edit Student</h4>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Student No.</label>
                    <input type="text" name="student_no" class="form-control" value="<?php echo $student['student_no']; ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fullname</label>
                    <input type="text" name="fullname" class="form-control" value="<?php echo $student['fullname']; ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Course</label>
                    <select name="course" class="form-select" required>
                        <option value="">-- Select Course --</option>
                        <option value="BSIT" <?php if($student['course']=="BSIT") echo "selected"; ?>>BSIT</option>
                        <option value="BSOA" <?php if($student['course']=="BSOA") echo "selected"; ?>>BSOA</option>
                        <option value="ACT" <?php if($student['course']=="ACT") echo "selected"; ?>>ACT</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Year Level</label>
                    <select name="year_lvl" class="form-select" required>
                        <option value="">-- Select Year Level --</option>
                        <option value="1" <?php if($student['year_lvl']=="1") echo "selected"; ?>>1st Year</option>
                        <option value="2" <?php if($student['year_lvl']=="2") echo "selected"; ?>>2nd Year</option>
                        <option value="3" <?php if($student['year_lvl']=="3") echo "selected"; ?>>3rd Year</option>
                        <option value="4" <?php if($student['year_lvl']=="4") echo "selected"; ?>>4th Year</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="index.php" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-primary">Update Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>