<?php
session_start();
if (isset($_SESSION['login'])) {
    header('Location: /index.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <title>Document</title>
</head>


<body class="bg-gray-200 font-sans text-gray-700">
    <div class="container mx-auto p-8 flex">
        <div class="max-w-md w-full mx-auto">
            <div class="container flex flex-col justify-center items-center">
                <span class="bg-gradient-to-r text-transparent from-stone-400 to-neutral-800 bg-clip-text">
                    <h1 class="text-4xl text-center mt-44 mb-20 font-bold ">Admin Login</h1>
                </span>

            </div>

            <div class="bg-white rounded-lg overflow-hidden shadow-2xl">
                <div class="p-8">
                    <form method="POST" action="../utils/auth.php">
                        <div class="mb-5">
                            <input placeholder="Username" type="text" name="username" class="block w-full p-3 rounded bg-gray-200 border border-transparent focus:outline-none">
                        </div>

                        <div class="mb-5">
                            <input placeholder="*********" type="password" name="password" class="block w-full p-3 rounded bg-gray-200 border border-transparent focus:outline-none">
                        </div>

                        <button type="submit" name="login" class="w-full p-3 mt-4 bg-blue-500 text-white rounded shadow hover:bg-blue-600">Login</button>
                    </form>
                </div>

                <?php
                if (isset($_GET['notification'])) {
                    echo $_GET['notification'];
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>