<?php
unset($_SESSION["user"]);
echo "<p>Logged out successfully.</p><script>function x(){window.location.href = 'index.php';}setTimeout(x,3000);</script>";
?>