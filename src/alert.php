<?php

if (isset($_SESSION['message'])) {
    echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: '{$_SESSION['message_type']}',
                    title: '{$_SESSION['message']}'
                });
            });
        </script>";
        
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
