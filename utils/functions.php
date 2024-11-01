<?php
$hostname = "127.0.0.1";
$username = "root";
$password = "";
$database = "e-report";

$connection = mysqli_connect($hostname, $username, $password, $database);

function query($query)
{
    global $connection;
    $result = mysqli_query($connection, $query);

    $rows = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $rows[] = $row;
        }
    }

    return $rows;
}

function tambah($data, $table, $fields)
{
    global $connection;

    $columns = [];
    $values = [];

    foreach ($fields as $field) {
        if (isset($data[$field])) {
            if ($field === 'password') {
                $hashedPassword = password_hash($data[$field], PASSWORD_DEFAULT);
                $columns[] = $field;
                $values[] = "'" . $hashedPassword . "'";
            } else {
                $columns[] = $field;
                $values[] = "'" . htmlspecialchars($data[$field]) . "'";
            }
        }
    }

    if (isset($data['nik'])) {
        $nik = htmlspecialchars($data["nik"]);
        $checkNIK = query("SELECT * FROM users WHERE nik='$nik'");

        if (count($checkNIK) > 0) {
            return -1;
        }
    }

    if (isset($_FILES['gambar'])) {
        $image = upload();
        if ($image === false) {
            return false;
        }
        $columns[] = 'gambar';
        $values[] = "'" . $image . "'";
    }

    $query = "INSERT INTO $table (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $values) . ")";
    $insert = mysqli_query($connection, $query);
    return mysqli_affected_rows($connection);
}



function upload()
{
    $originalName = $_FILES['gambar']['name'];
    $filesize = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    if (
        $error === 4
    ) {
        echo "<script>
				alert('pilih gambar terlebih dahulu!');
			  </script>";
        return false;
    }

    $validExtension = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $originalName);
    $ekstensiGambar = strtolower(end($ekstensiGambar));
    if (!in_array($ekstensiGambar, $validExtension)) {
        echo "<script>
				alert('File bukan gambar');
			  </script>";
        return false;
    }

    if ($filesize > 1000000) {
        echo "<script>
				alert('Ukuran gambar terlalu besar!');
			  </script>";
        return false;
    }

    $imgFolder = 'img/';
    if (!is_dir($imgFolder)) {
        mkdir($imgFolder, 0755, true);
    }
    $newFilename = uniqid() . '.' . $ekstensiGambar;
    move_uploaded_file($tmpName, $imgFolder . $newFilename);

    return $newFilename;
}

function hapus($identifier, $table, $id)
{
    global $connection;
    mysqli_query($connection, "DELETE FROM $table WHERE $identifier='$id'");
    return mysqli_affected_rows($connection);
}

function validateInput($data, $fields, $isUpdate = false)
{
    $errors = [];
    $valid = true;

    foreach ($fields as $field => $errorMessage) {
        if (empty($data[$field]) && $field !== 'password' && $field !== 'password_confirm' && $field !== 'old_password') {
            $errors[$field] = $errorMessage;
            $valid = false;
        } else {
            $data[$field] = htmlspecialchars($data[$field]);
        }
    }

    if (!empty($data['password']) || !empty($data['password_confirm'])) {
        if ($data['password'] !== $data['password_confirm']) {
            $errors['password_confirm'] = 'Password did not match';
            $valid = false;
        }
    }

    if ($isUpdate && !empty($data['old_password'])) {
        global $connection;
        $userId = $data['id'];
        $query = "SELECT password FROM users WHERE id='$userId'";
        $result = mysqli_query($connection, $query);
        $user = mysqli_fetch_assoc($result);

        if (!password_verify($data['old_password'], $user['password'])) {
            $errors['old_password'] = 'Old password is incorrect';
            $valid = false;
        }
    }

    return ['valid' => $valid, 'errors' => $errors, 'data' => $data];
}

function handleFormSubmit($data, $table, $action)
{

    $fields = [
        'nik' => 'NIK is required',
        'username' => 'Username is required',
        'name' => 'Nama is required',
        'phone_number' => 'Phone Number is required',
        'password' => 'Password is required',
        'password_confirm' => 'Confirmation password is required',
        'role_id' => 'Role must be selected',
        'old_password' => 'Old password is required'
    ];

    $isUpdate = ($action === 'update');
    $validationResult = validateInput($data, $fields, $isUpdate);

    $valid = $validationResult['valid'];
    $errors = $validationResult['errors'];

    if (empty($data['password']) && $isUpdate) {
        unset($data['password'], $data['password_confirm'], $data['old_password']);
    } else {
        unset($data['password_confirm'], $data['old_password']);
    }


    if ($valid) {
        $result = $isUpdate ? update($table, $data, ['id' => $data['id']]) : tambah($data, $table, array_keys($fields));


        $successMessage = $isUpdate ? 'Berhasil mengubah data' : 'Berhasil menambahkan data';
        $duplicateMessage = 'NIK sudah ada';
        $failureMessage = $isUpdate ? 'Gagal mengubah data' : 'Gagal menambahkan data';

        if ($result > 0) {
            echo "<script>
            Swal.fire({
                title: 'Good job!',
                text: '$successMessage',
                icon: 'success'
            }).then((result) => {
                window.location.href = 'index.php?page=users';
            });
            </script>";
        } else if ($result == -1) {
            echo "<script>
            Swal.fire({
                title: 'Failed',
                text: '$duplicateMessage',
                icon: 'error'
            }).then((result) => {
                window.location.href = 'index.php?page=users';
            });
            </script>";
        } else {
            echo "<script>
            Swal.fire({
                title: 'Failed',
                text: '$failureMessage',
                icon: 'error'
            }).then((result) => {
                window.location.href = 'index.php?page=users';
            });
            </script>";
        }
    } else {
        $errorMessage = implode(", ", $errors);
        echo "<script>
        Swal.fire({
            title: 'Failed',
            text: 'Data belum lengkap: $errorMessage',
            icon: 'error'
        });
        </script>";
    }
}

function update($table, $data, $where)
{
    global $connection;

    if (isset($data['id'])) {
        unset($data['id']);
        unset($data['update']);
    }



    $fields = [];
    foreach ($data as $column => $value) {
        if ($column === 'password') {
            $value = password_hash($value, PASSWORD_DEFAULT);
        } elseif ($column === 'gambar' && $_FILES['gambar']['error'] !== 4) {
            $image = upload();
            if (!$image) {
                return false;
            }
            if (!empty($data['old_image'])) {
                unlink('./img/' . $data['old_image']);
            }
            $value = $image;
        } else {
            $value = htmlspecialchars($value);
        }

        $fields[] = "$column = '$value'";
    }

    $fields_sql = implode(", ", $fields);

    $where_sql = [];
    foreach ($where as $key => $val) {
        $val = htmlspecialchars($val);
        $where_sql[] = "$key = '$val'";
    }
    $where_clause = implode(" AND ", $where_sql);

    $query = "UPDATE $table SET $fields_sql WHERE $where_clause";

    mysqli_query($connection, $query);

    return mysqli_affected_rows($connection);
}
