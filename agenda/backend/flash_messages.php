<?php

session_start();

function display_flash_message() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        echo '<div class="flash-message ' . htmlspecialchars($message['type']) . '">';
        echo htmlspecialchars($message['message']);
        echo '</div>';
        unset($_SESSION['flash_message']);
    }
}

function test($test) {
    echo "asfdjgfd";
    echo "$test";
}
function set_flash_message($message, $type) {
    $_SESSION['flash_message'] = [
        'message' => $message,
        'type' => $type
    ];
}
