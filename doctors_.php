<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once "dbcon.php";

    $errors = [];

    $name = $_POST['name'] ?? '';
    $specialization = $_POST['specialization'] ?? '';
    $contract_type = $_POST['contract_type'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';

    $image_url = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            if (in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image_url = $target_file;
                } else {
                    $errors[] = "Error uploading file.";
                }
            } else {
                $errors[] = "Invalid file format. Only JPG, JPEG, PNG, and GIF files are allowed.";
            }
        } else {
            $errors[] = "File is not an image.";
        }
    }

    if (empty($name)) {
        $errors[] = "Name is required.";
    }

    if (empty($specialization)) {
        $errors[] = "Specialization is required.";
    }

    if (empty($contract_type)) {
        $errors[] = "Contract Type is required.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($phone)) {
        $errors[] = "Phone is required.";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO doctors (name, specialization, contract_type, email, phone, image_url) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $name, $specialization, $contract_type, $email, $phone, $image_url);

        if ($stmt->execute()) {
            header("Location: doctors.php");
            exit();
        } else {
            $errors[] = "Error occurred while adding doctor. Please try again later.";
        }

        $stmt->close();
    }

    $_SESSION['errors'] = $errors;
    header("Location: adddoctor.php");
    exit();
}
?>
