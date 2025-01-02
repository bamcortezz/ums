<?php 
require_once('config/settings-config.php');
require_once('dashboard/controller/authentication.php'); 
include('src/partials/alert.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
            <!-- reset password form start -->
            <form class="space-y-4" action="reset-password.php" method="post" id="reset-password-form">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Reset Password</h2>
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input
                        type="password"
                        name="new_password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                        placeholder="••••••••" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <input
                        type="password"
                        name="confirm_password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                        placeholder="••••••••" required />
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-lg transition-colors" name="btn-reset-password">
                    Reset Password
                </button>
                <div class="mt-6 text-center text-sm text-gray-600">
                    Remember your password?
                    <a href="/ums/" class="text-indigo-600 hover:text-indigo-500 font-medium" id="btn-login-from-reset">Sign in</a>
                </div>
            </form>
            <!-- reset password form end -->
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
