<?php
// 1. Database Connection
$conn = new mysqli("localhost", "root", "", "school");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. CREATE: Add a new student
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $grade = $_POST['grade'];
    $email = $_POST['email'];

    $sql = "INSERT INTO students (name, age, grade, email) VALUES ('$name', $age, '$grade', '$email')";
    $conn->query($sql);
    header("Location: student_system.php"); // Refresh to clear form
}

// 3. DELETE: Remove a student record
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM students WHERE id=$id");
    header("Location: student_system.php");
}

// 4. UPDATE: Save edited details
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $grade = $_POST['grade'];
    $email = $_POST['email'];

    $sql = "UPDATE students SET name='$name', age=$age, grade='$grade', email='$email' WHERE id=$id";
    $conn->query($sql);
    header("Location: student_system.php");
}

// 5. FETCH DATA FOR EDIT: If 'edit' button clicked, get current values
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $res = $conn->query("SELECT * FROM students WHERE id=$id");
    $edit_data = $res->fetch_assoc();
}

// 6. READ: Get all records for the table
$result = $conn->query("SELECT * FROM students");
?>

<!DOCTYPE html>
<html>
<head><title>Student Management System</title></head>
<body>

    <h2>Student Records</h2>
    <table border="1" cellpadding="10">
        <tr>
            <th>Name</th><th>Age</th><th>Grade</th><th>Email</th><th>Actions</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['name'] ?></td>
            <td><?= $row['age'] ?></td>
            <td><?= $row['grade'] ?></td>
            <td><?= $row['email'] ?></td>
            <td>
                <a href="?edit=<?= $row['id'] ?>">Edit</a> | 
                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <hr>

    <h3><?= $edit_data ? "Edit Student" : "Add New Student" ?></h3>
    <form method="POST" action="student_system.php">
        <input type="hidden" name="id" value="<?= $edit_data['id'] ?? '' ?>">
        
        <input type="text" name="name" placeholder="Name" value="<?= $edit_data['name'] ?? '' ?>" required><br><br>
        <input type="number" name="age" placeholder="Age" value="<?= $edit_data['age'] ?? '' ?>" required><br><br>
        <input type="text" name="grade" placeholder="Grade" value="<?= $edit_data['grade'] ?? '' ?>" required><br><br>
        <input type="email" name="email" placeholder="Email" value="<?= $edit_data['email'] ?? '' ?>" required><br><br>

        <?php if ($edit_data): ?>
            <button type="submit" name="update">Update Record</button>
            <a href="student_system.php">Cancel</a>
        <?php else: ?>
            <button type="submit" name="add">Add Student</button>
        <?php endif; ?>
    </form>

</body>
</html>