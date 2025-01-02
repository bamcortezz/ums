<?php 
require_once('config/settings-config.php');
require_once('dashboard/controller/authentication.php'); 
include('src/partials/alert.php');

if (isset($_POST['btn-forgot-password'])) {
    $email = trim($_POST['email']);

    $forgotPassword = new Auth();
    $forgotPassword->forgotPassword($email);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
            <!-- forgot password form start -->
            <form class="space-y-4" action="forgot-password.php" method="post" id="forgot-password-form">
                <div class="flex flex-col">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Reset Password</h2>
                    <p class="text-center text-gray-700 mb-4">To Reset Password, enter your email, check your email, and reset your password.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input
                        type="email"
                        name="email"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                        placeholder="your@email.com" required />
                </div>
                <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-lg transition-colors" name="btn-forgot-password" type="submit">
                    Reset Password
                </button>
                <div class="mt-6 text-center text-sm text-gray-600">
                    Remember your password?
                    <a href="/ums/" class="text-indigo-600 hover:text-indigo-500 font-medium" id="btn-login-from-forgot">Sign in</a>
                </div>
            </form>
            <!-- forgot password form end -->
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>