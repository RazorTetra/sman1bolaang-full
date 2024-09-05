<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="../assets/css/output.css" rel="stylesheet">

    <!-- Logo dan favicon -->
    <link rel="shortcut icon" href="../assets/img/logo-smk.png" type="image/x-icon">
    
    <style>
        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Black background with opacity */
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            max-width: 400px;
            width: 100%;
        }

        .modal.show {
            display: flex;
        }
    </style>
</head>
<body class="bg-gray-200 flex items-center justify-center min-h-screen">
    <!-- Container for form -->
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full relative">
        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <img src="../assets/img/logo-smk.png" alt="SMK Logo" class="w-24 h-24">
        </div>
        
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-700">Login</h2>
        
        <!-- Form login -->
        <form action="../functions/login.php" method="POST">
            <label for="username" class="block text-gray-700">Username:</label>
            <input type="text" name="username" class="w-full p-2 mb-4 border border-gray-300 rounded" required>

            <label for="password" class="block text-gray-700">Password:</label>
            <input type="password" name="password" class="w-full p-2 mb-4 border border-gray-300 rounded" required>

            <button type="submit" name="login" class="w-full bg-gray-700 text-white p-2 rounded hover:bg-gray-600">Login</button>
        </form>
    </div>

    <!-- Modal for error message -->
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <h3 class="text-xl font-bold mb-4 text-red-600">Error</h3>
            <p id="modalMessage"></p>
            <button id="closeModal" class="mt-4 bg-gray-700 text-white p-2 rounded">Close</button>
        </div>
    </div>

    <script>
        // Display modal if there's an error message
        const errorModal = document.getElementById('errorModal');
        const closeModal = document.getElementById('closeModal');
        const modalMessage = document.getElementById('modalMessage');

        // Check if there is an error in the URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const error = urlParams.get('error');
        if (error) {
            modalMessage.textContent = decodeURIComponent(error);
            errorModal.classList.add('show');
        }

        // Close the modal when clicking the close button
        closeModal.addEventListener('click', function() {
            errorModal.classList.remove('show');
        });
    </script>
</body>
</html>
