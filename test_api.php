<?php 
$ch=curl_init('https://rajaongkir.komerce.id/api/v1/destination/province'); 
curl_setopt($ch,CURLOPT_HTTPHEADER,['key: UNQgC3Uze4e080ee548ca35cjLgg6QIb']); 
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true); 
$r=curl_exec($ch); 
echo $r;
