<?php

include_once (BASE_PATH . 'backend/flash_messages.php');

display_flash_message();
?>
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const flashMessage = document.querySelector('.flash-message');
        if (flashMessage) {
            setTimeout(() => {
                flashMessage.style.display = 'none';
            }, 10000);
        }
    });
</script>