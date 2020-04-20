<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */

  include("includes/config.php");
?>
{
    "name": "<?= APP_NAME ?>",
    "short_name": "<?= APP_NAME ?>",
    "icons": [
        {
            "src": "/img/android-chrome-192x192.png",
            "sizes": "192x192",
            "type": "image/png"
        },
        {
            "src": "/img/android-chrome-512x512.png",
            "sizes": "512x512",
            "type": "image/png"
        }
    ],
    "theme_color": "<?= BRANDING_COLOR ?>",
    "display": "standalone",
    "orientation": "landscape"
}





