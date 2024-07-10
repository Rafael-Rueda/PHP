<?php
include_once ("../../utils/base_url.php");
include_once (BASE_PATH . 'backend/flash_messages.php');

display_flash_message();
?>
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const flashMessage = document.querySelector('.flash-message');
        if (flashMessage) {
            setTimeout(() => {
                flashMessage.style.display = 'none';
            }, 5000); // 5 segundos
        }
    });
</script>