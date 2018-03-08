<?php

$d = file_get_contents('fines.json');

$d = json_decode($d, true);
$d = json_decode($d['Value'], true);

$d = $d['Fines'];

//echo '<pre>';
//print_r($d);
//die;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Слуцкий.Карты</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="https://yastatic.net/bootstrap/3.3.4/css/bootstrap.min.css">
    <script>
        ymaps.ready(init);

        function init() {
            var myMap = new ymaps.Map("map", {
                    center: [55.76, 37.64],
                    zoom: 10
                });

            myMap.geoObjects
                <?php foreach ($d as $item): ?>
                    .add(new ymaps.Placemark([<?=str_replace(',','.',$item['Latitude'])?>, <?=str_replace(',','.',$item['Longitude'])?>], {
                            balloonContent: '<?=$item['ApnDetail'][0]['Value']?>',

                            <?php

                                $text = ' ';
                                $color = 'black';

                                if (preg_match('/12\.09\.2(.+)до\s([0-9]+)/iu', $item['ApnDetail'][0]['Value'], $m)) {
                                    $text = '<40 км/ч';
                                    $color = 'brown';

//                                    $m40++;
                                } elseif (preg_match('/12\.17 ч\.1\.2/iu', $item['ApnDetail'][0]['Value'], $m)) {
                                    $text = 'выделенка';
                                    $color = 'blue';

//                                    $vyd++;
                                } elseif (preg_match('/12\.0?9\.(3|6).*60 км\/ч/iu', $item['ApnDetail'][0]['Value'], $m)) {
                                    $text = '40-60 км.ч';
                                    $color = 'red';

//                                    $m60++;
                                } elseif (preg_match('/12\.15\.5/iu', $item['ApnDetail'][0]['Value'], $m)) {
                                    $text = 'встречка';
                                    $color = 'olive';

//                                    $vstr++;
                                } elseif (preg_match('/12\.15\.1/iu', $item['ApnDetail'][0]['Value'], $m)) {
                                    $text = '12.15.1';
                                    $color = 'black';

//                                    $m125++;
                                } elseif (preg_match('/^12\.9\.7/iu', $item['ApnDetail'][0]['Value'], $m)) {
                                    $text = '>60 км/ч';
                                    $color = 'violet';
//                                    $m80++;
                                } elseif (preg_match('/^([0-9\.]+)/iu', $item['ApnDetail'][0]['Value'], $m)) {
                                    $text = $m[1];
//                                    $t .= $item['ApnDetail'][0]['Value'] .'<br>';
                                    $color = 'black';
//                                    $mo++;
                                }
                            ?>

                            iconContent: '<?=$text?>',
                        }, {
                            preset: 'islands#<?=$color?>StretchyIcon',
                        }))
                <?php endforeach ?>

                .add(new ymaps.Placemark([55.713912, 37.127207], {
                    balloonContent: 'Дом Слуцкого'
                }, {
                    preset: 'islands#glyphIcon',
                    iconGlyph: 'home',
                    iconGlyphColor: 'red'
                }))
            ;
        }
    </script>
    <style>
        html, body, #map {
            width: 100%; height: 100%; padding: 0; margin: 0;
        }
    </style>
</head>
<body>
<div id="map"></div>
</body>
</html>

<?php
//var_dump($m40);
//var_dump($m60);
//var_dump($m80);
//var_dump($vyd);
//var_dump($mo);
//var_dump($vstr);
//var_dump($m125);
//
//echo $t;
?>

