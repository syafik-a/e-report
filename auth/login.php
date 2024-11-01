<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Document</title>
</head>

<body class="bg-gray-200 font-sans text-gray-700">
    <div class="container mx-auto p-8 flex">
        <div class="max-w-md w-full mx-auto">

            <div class="container flex flex-col justify-center items-center">
                <img src="../assets/images/logo.png " class="mr-3 w-1/4" alt="Logo" />
                <span class="bg-gradient-to-r text-transparent from-blue-500 to-purple-500 bg-clip-text">
                    <h1 class="text-4xl text-center mb-12 font-bold ">Login masehh</h1>
                </span>

            </div>

            <div class="bg-white rounded-lg overflow-hidden shadow-2xl">
                <div class="p-8">
                    <form method="POST" class="" action="#" onsubmit="return false;">
                        <div class="mb-5">
                            <label for="username" class="block mb-2 text-sm font-medium text-gray-600">Username</label>

                            <input type="text" name="username" class="block w-full p-3 rounded bg-gray-200 border border-transparent focus:outline-none">
                        </div>

                        <div class="mb-5">
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-600">Password</label>

                            <input type="text" name="password" class="block w-full p-3 rounded bg-gray-200 border border-transparent focus:outline-none">
                        </div>

                        <button class="w-full p-3 mt-4 bg-blue-500 text-white rounded shadow hover:bg-blue-600">Login</button>
                    </form>
                </div>


            </div>
        </div>
    </div>
</body>

</html>