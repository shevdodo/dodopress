<?php
$json = json_decode(file_get_contents(__DIR__.'/button_svgs.json'), true);
$export = "<?php\nreturn " . var_export($json, true) . ";\n";
file_put_contents(__DIR__.'/../config/builder_svgs.php', $export);
echo "Done";
