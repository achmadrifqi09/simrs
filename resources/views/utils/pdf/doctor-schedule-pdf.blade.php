<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Jadwal Poliklinik {{$currentMonth}} RSU UMM</title>
    <link rel="stylesheet" href="css/pdf-styles.css">
</head>
<body class="antialiased">
<table class="schedule-table">
    <thead>
    <tr>
        <th>Dokter / Praktisi</th>
        <th>Senin</th>
        <th>Selasa</th>
        <th>Rabu</th>
        <th>Kamis</th>
        <th>Jum'at</th>
        <th>Sabtu</th>
    </tr>
    </thead>
    <tbody>
    @foreach($polyclinics as $polyclinic)
        <tr class="sub-header">
            <td class="doctor-name">KLINIK {{$polyclinic->nama}}</td>
            <td colspan="6">Jam Praktik</td>
        </tr>
        @foreach($polyclinic->dokter as $doctor)
            <tr>
                <td>{{$doctor['nama_dokter']}}</td>

                @for($i = 0; $i < 6; $i++)
                        <?php $found = false; ?>
                        <?php $times = []; ?>
                    @foreach($doctor['jadwal'] as $schedule)
                        @if($i == (int) $schedule->hari - 1)
                                <?php
                                $timeRange = explode('-', $schedule->jam_praktek);
                                $start_time = substr($timeRange[0], 0, 5);
                                $end_time = substr($timeRange[1], 0, 5);
                                $times[] = [$start_time, $end_time];
                                $found = true;
                                ?>
                        @endif
                    @endforeach
                    @if($found)
                            <?php
                            usort($times, function ($a, $b) {
                                return strcmp($a[0], $b[0]);
                            });
                            ?>
                        <td>
                            {{ implode(', ', array_map(function($time) {
                                return $time[0] . '-' . $time[1];
                            }, $times)) }}
                        </td>
                    @else
                        <td></td>
                    @endif
                @endfor
            </tr>
        @endforeach

    @endforeach
    </tbody>
</table>

</body>
</html>
