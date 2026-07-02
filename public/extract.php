<?php
$files = ['button-simple.svg', 'button-round.svg', 'button-circle.svg', 'button-outline.svg', 'button-outline-round.svg', 'button-outline-circle.svg', 'button-link.svg', 'button-underline.svg', 'button-call-to-action.svg', 'button-call-to-action-large.svg'];
$res = [];
foreach($files as $f) {
    $res[$f] = file_get_contents('c:/xampp/htdocs/flatsome/inc/builder/shortcodes/thumbnails/'.$f);
}
file_put_contents('button_svgs.json', json_encode($res));
echo "Done";
