<?php
include ('../../src/partials/alert.php');
require_once('../controller/authentication.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>
   <script src="https://cdn.tailwindcss.com"></script>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
   <!-- Aside -->
   <?php include ('../../src/partials/aside.php'); ?>
    
   <div class="p-4 sm:ml-64">
      <div class="p-4">
         <h1 class="text-3xl font-bold mb-4">Product Dashboard</h1>
         <div class="grid grid-cols-3 gap-4 mb-4">
            <div class="flex items-center justify-center h-24 rounded bg-gray-50 dark:bg-gray-800">
               <p class="text-2xl text-gray-400 dark:text-gray-500">Product 1</p>
            </div>
            <div class="flex items-center justify-center h-24 rounded bg-gray-50 dark:bg-gray-800">
               <p class="text-2xl text-gray-400 dark:text-gray-500">Product 2</p>
            </div>
            <div class="flex items-center justify-center h-24 rounded bg-gray-50 dark:bg-gray-800">
               <p class="text-2xl text-gray-400 dark:text-gray-500">Product 3</p>
            </div>
         </div>
         <div class="flex items-center justify-center h-48 mb-4 rounded bg-gray-50 dark:bg-gray-800">
            <p class="text-2xl text-gray-400 dark:text-gray-500">Product Details</p>
         </div>
      </div>
   </div>

   <script>
      document.addEventListener('DOMContentLoaded', function() {
         const toggleButton = document.getElementById('sidebar-toggle');
         const sidebar = document.getElementById('default-sidebar');

         //toggle aside when small screen
         toggleButton.addEventListener('click', function() {
            if (sidebar.classList.contains('-translate-x-full')) {
               sidebar.classList.remove('-translate-x-full');
            } else {
               sidebar.classList.add('-translate-x-full');
            }
         });
         //removing aside when not target
         document.addEventListener('click', function(event) {
            if (!sidebar.contains(event.target) && !toggleButton.contains(event.target)) {
               sidebar.classList.add('-translate-x-full');
            }
         });
      });
   </script>
</body>

</html>