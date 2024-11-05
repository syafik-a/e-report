<?php
ob_start();
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: /auth/login.php');
} elseif ($_SESSION['role_name'] !== "petugas") {
    header('Location: /index.php');
}

include_once($_SERVER['DOCUMENT_ROOT'] . "/utils/functions.php");

include_once($_SERVER['DOCUMENT_ROOT'] . "/layout/header.php");
if (!isset($_GET['page'])) {
    header("Location: /admin/index.php?page=dashboard");
}

include_once($_SERVER['DOCUMENT_ROOT'] . "/layout/navbar.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/layout/sidebar.php");

?>


<main class="flex flex-col gap-4 md:gap-5 px-4 lg:px-5 lg:ml-56 h-auto pt-20 pb-5">
    <?php
    if ($_GET['page'] == "" || $_GET["page"] == "dashboard") {
        include_once("dashboard.php");
    }

    if ($_GET['page'] == "users") {
        include_once("users.php");
    }

    if ($_GET['page'] == "users_delete") {
        if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
            $user_id = $_GET['user_id'];

            if (hapus('id', 'users', $user_id) > 0) {
                echo "<script>
                Swal.fire({
                    title: 'Good job!',
                    text: 'Data berhasil dihapus',
                    icon: 'success'
                    }).then((result) => {
                        window.location.href = 'index.php?page=users';
                    });
                </script>";
            } else {
                echo "<script>
                Swal.fire({
                    title: 'Failed!',
                    text: 'Data gagal dihapus',
                    icon: 'error'
                    }).then((result) => {
                        window.location.href = 'index.php?page=users';
                    });
                </script>";
            }
        } else {
            header("Location: /admin/index.php?page=users");
            exit();
        }
    }

    if ($_GET['page'] == "list") {
        include_once("list.php");
    }

    if ($_GET['page'] == "reports") {
        include_once("reports.php");
    }

    if ($_GET['page'] == "reports_delete") {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];

            if (hapus('id', 'reports', $id) > 0) {
                echo "<script>
                Swal.fire({
                    title: 'Good job!',
                    text: 'Data berhasil dihapus',
                    icon: 'success'
                    }).then((result) => {
                        window.location.href = 'index.php?page=list';
                    });
                </script>";
            } else {
                echo "<script>
                Swal.fire({
                    title: 'Failed!',
                    text: 'Data gagal dihapus',
                    icon: 'error'
                    }).then((result) => {
                        window.location.href = 'index.php?page=list';
                    });
                </script>";
            }
        } else {
            header("Location: /admin/index.php?page=list");
            exit();
        }
    }

    if ($_GET['page'] == "reports_approve") {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];

            approve('reports', $id);
            echo "<script>
                Swal.fire({
                    title: 'Good job!',
                    text: 'Report has been approved successfully',
                    icon: 'success'
                    }).then((result) => {
                        window.location.href = 'index.php?page=list';
                    });
                </script>";
        } else {
            header("Location: /admin/index.php?page=list");
            exit();
        }
    }

    if ($_GET['page'] == "create") {
        include_once("create-report.php");
    }

    if ($_GET['page'] == "detail" && isset($_GET["id"])) {
        include_once("detail.php");
    }

    if ($_GET['page'] == "logout") {
        include_once("../utils/auth.php");
    }


    ?>
</main>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/layout/footer.php");
?>