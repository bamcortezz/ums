<?php 
require_once('config/settings-config.php');
require_once ('dashboard/controller/authentication.php');
include('src/partials/alert.php');


if (isset($_POST['btn-login'])) {
    $csrf_token = $_POST['csrf_token'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $login = new Auth();
    $login->login($csrf_token, $email, $password);
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
            <!-- login form start -->
            <form action="index.php" method="POST" class="space-y-4" id="login-form">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Sign In</h2>
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input
                        type="email"
                        name="email"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                        placeholder="your@email.com" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input
                        type="password"
                        name="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                        placeholder="••••••••" required />
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                    <a href="/ums/forgot-password.php" class="text-sm text-indigo-600 hover:text-indigo-500" id="btn-forgot">Forgot password?</a>
                </div>
                <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-lg transition-colors" name="btn-login">
                    Sign In
                </button>
                <div class="mt-6 text-center text-sm text-gray-600">
                    Don't have an account?
                    <a href="/ums/registration.php" class="text-indigo-600 hover:text-indigo-500 font-medium" id="btn-register">Sign up</a>
                </div>
            </form>
            <!-- login form end -->
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>