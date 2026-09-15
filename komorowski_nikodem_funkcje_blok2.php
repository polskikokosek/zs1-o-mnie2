<?php

// Wyniki zawodów biegowych. Czasy okrążeń w sekundach, po jednym na ukończone okrążenie.
$zawodnicy = [
    ['nazwisko' => 'Anna Kowalska',    'okrazenia' => [312, 298, 305]],
    ['nazwisko' => 'Piotr Nowak',      'okrazenia' => [355, 430, 341]],
    ['nazwisko' => 'Marek Zieliński',  'okrazenia' => []],
    ['nazwisko' => 'Ewa Wiśniewska',   'okrazenia' => [402, 377, 395]],
];



function formatCzas($sekundy, $separator = ':') {

    $minuty = floor($sekundy / 60);
    $sekundy = $sekundy % 60;

    return $minuty . $separator . str_pad($sekundy, 2, '0', STR_PAD_LEFT);
}


?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="utf-8">
<title>Wyniki zawodów</title>

<style>
    body { font-family: sans-serif; margin: 2rem; }
    table { border-collapse: collapse; }
    th, td { border: 1px solid #999; padding: 0.4rem 0.8rem; text-align: left; }
    caption { font-weight: bold; margin-bottom: 0.5rem; }
    tfoot td { font-weight: bold; }
    .elita        { background: #d4f5d4; }
    .zaawansowany { background: #fff3c4; }
    .amator       { background: #f5d4d4; }
</style>

</head>

<body>

<h1>Zawody biegowe: czasy okrążeń</h1>

<table>

    <caption>Wyniki zawodników</caption>

    <thead>

        <tr>
            <th scope="col">Zawodnik</th>
            <th scope="col">Okrążenia</th>
            <th scope="col">Najlepsze</th>
            <th scope="col">Średnie</th>
            <th scope="col">Kategoria</th>
            <th scope="col">Uwagi</th>
        </tr>

    </thead>


    <tbody>

        <?php

            $liczbaZawodnikow = 0;
            $liczbaElita = 0;
            $najlepszeZawody = null;
            $liczbaOkrazen = 0;


            foreach ($zawodnicy as $zawodnik) {

                $liczbaZawodnikow++;

                $nazwisko = $zawodnik['nazwisko'];
                $okrazenia = $zawodnik['okrazenia'];


                if (count($okrazenia) == 0) {

                    echo '<tr>';

                    echo '<td>' . $nazwisko . '</td>';

                    echo '<td colspan="5">brak ukończonych okrążeń</td>';

                    echo '</tr>';

                    continue;
                }


                $czasy = [];


                foreach ($okrazenia as $czas) {

                    $czasy[] = formatCzas($czas);
                }


                $najlepsze = min($okrazenia);

                $najlepszeFormat = formatCzas($najlepsze);


                $srednia = round(array_sum($okrazenia) / count($okrazenia));

                $sredniaFormat = formatCzas($srednia);


                // Kategoria
                if ($najlepsze < 300) {

                    $kategoria = 'elita';
                    $liczbaElita++;

                } elseif ($najlepsze < 360) {

                    $kategoria = 'zaawansowany';

                } else {

                    $kategoria = 'amator';
                }


                // Uwagi
                $uwagi = '';


                foreach ($okrazenia as $czas) {

                    if ($czas >= 420) {

                        $uwagi = 'słabe okrążenie';

                        break;
                    }
                }


                $rowneTempo = true;


                foreach ($okrazenia as $czas) {

                    if ($czas > $najlepsze + 15) {

                        $rowneTempo = false;

                        break;
                    }
                }


                if ($rowneTempo) {

                    if ($uwagi != '') {

                        $uwagi .= ', ';
                    }

                    $uwagi .= 'równe tempo';
                }


                // Liczba wszystkich ukończonych okrążeń
                $liczbaOkrazen += count($okrazenia);


                // Najlepsze okrążenie zawodów
                if ($najlepszeZawody === null || $najlepsze < $najlepszeZawody) {

                    $najlepszeZawody = $najlepsze;
                }


                // Wiersz zawodnika
                echo '<tr class="' . $kategoria . '">';

                echo '<td>' . $nazwisko . '</td>';

                echo '<td>' . implode(', ', $czasy) . '</td>';

                echo '<td>' . $najlepszeFormat . '</td>';

                echo '<td>' . $sredniaFormat . '</td>';

                echo '<td>' . $kategoria . '</td>';

                echo '<td>' . $uwagi . '</td>';

                echo '</tr>';
            }

        ?>

    </tbody>


    <tfoot>

        <?php

            $najlepszeZawodyFormat = formatCzas($najlepszeZawody);


            echo '<tr>';

            echo '<td colspan="6">';

            echo 'Zawodników: ' . $liczbaZawodnikow;

            echo ', elita: ' . $liczbaElita;

            echo ', najlepsze okrążenie zawodów: ' . $najlepszeZawodyFormat;

            echo ', okrążeń łącznie: ' . $liczbaOkrazen;

            echo '</td>';

            echo '</tr>';

        ?>

    </tfoot>

</table>

</body>
</html>
