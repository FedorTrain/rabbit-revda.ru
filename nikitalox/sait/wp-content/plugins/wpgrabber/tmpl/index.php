<?php
header("Expires: Mon, 24 apr 2118 12:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
// wpgrаbber[.]biz
header("Location: ../");
exit;