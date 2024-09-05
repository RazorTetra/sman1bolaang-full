<!-- pages/registerPage.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/img/logo-smk.png" type="image/x-icon">
    
    <!-- RemixIcons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.4.0/remixicon.css" crossorigin="">
    
    <!-- Tailwind CSS -->
    <link href="../assets/css/output.css" rel="stylesheet">
    
    <title>SMK NEGERI 1 BOLAANG - Register</title>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="w-full max-w-md p-8 space-y-4 bg-gray-200 rounded-lg shadow-lg">
        <div class="text-center">
            <img src="../assets/img/logo-smk.png" alt="Logo SMK" class="mx-auto w-20 h-20 mb-4">
        </div>
        <h2 class="text-2xl font-bold text-center text-gray-800">Create Account</h2>
        <!-- Form action diarahkan ke functions/register.php -->
        <form action="../functions/register.php" method="POST" class="space-y-6">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" id="username" required
                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 text-gray-900 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" required
                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 text-gray-900 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" required
                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 text-gray-900 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <button type="submit" name="register"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Register
                </button>
            </div>
        </form>
    </div>
</body>
</html>
