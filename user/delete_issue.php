<?php

include '../db.php';


if (isset($_GET['id'])) {
    $id = $_GET['id'];


    $sql = "DELETE FROM issues WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Issue deleted successfully!";
        header("Location: view_issues.php"); 
    } else {
        echo "Error deleting issue: " . $conn->error;
    }
} else {
    echo "Invalid request!";
}

$conn->close();
?>
