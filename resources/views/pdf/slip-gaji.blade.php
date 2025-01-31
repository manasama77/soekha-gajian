<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <style>
        @font-face {
            font-family: 'Roboto';
            src: url({{ storage_path('fonts/static/Roboto-Black.ttf') }}) format("truetype");
            font-weight: 900;
            font-style: normal;
        }


        @font-face {
            font-family: 'Roboto';
            src: url({{ storage_path('fonts/static/Roboto-Bold.ttf') }}) format("truetype");
            font-weight: 700;
            font-style: normal;
        }

        @font-face {
            font-family: 'Roboto';
            src: url({{ storage_path('fonts/static/Roboto-Medium.ttf') }}) format("truetype");
            font-weight: 500;
            font-style: italic;
        }

        @font-face {
            font-family: 'Roboto';
            src: url({{ storage_path('fonts/static/Roboto-Regular.ttf') }}) format("truetype");
            font-weight: 400;
            font-style: normal;
        }

        @font-face {
            font-family: 'Roboto';
            src: url({{ storage_path('fonts/static/Roboto-Light.ttf') }}) format("truetype");
            font-weight: 300;
            font-style: normal;
        }

        @font-face {
            font-family: 'Roboto';
            src: url({{ storage_path('fonts/static/Roboto-Italic.ttf') }}) format("truetype");
            font-weight: 100;
            font-style: normal;
        }

        /* Start Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            font-family: Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
        }

        /* End Reset CSS */

        body {
            font-family: "Roboto", Arial, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        hr {
            border: 1px solid #000;
        }

        .header>.title>h1 {
            font-size: 1.8rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .content {
            margin-top: 0.2rem;
        }

        .content .table-info {
            width: 100%;
            margin-bottom: 1.5rem;
        }

        .content .table-info tr>td.left {
            width: 50%;
            vertical-align: top;
        }

        .content .table-info tr>td.right {
            width: 50%;
            vertical-align: top;
        }

        .content .table-info tr>td.left h1 {
            text-align: left;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .content .table-info tr>td.left p {
            text-align: left;
            font-size: .7rem;
        }

        .info-karyawan {
            width: 100%;
            font-size: .8rem;
        }

        .info-karyawan tr>td {
            font-size: .8rem;
            vertical-align: top;
            padding: 5px;
        }

        .info-karyawan tr>td:nth-child(1) {
            font-weight: bold;
        }

        .info-karyawan tr>td:nth-child(2) {
            width: 10px;
            text-align: center;
        }

        .content h6 {
            text-align: center;
            font-size: 0.9rem;
            font-weight: bold;
            margin-bottom: 0.3rem;
        }

        .table-gaji table {
            width: 100%;
        }

        .table-gaji table tr td:nth-child(1) {
            width: 25%;
            vertical-align: top;
        }

        .table-gaji table tr td:nth-child(2) {
            width: 75%;
            vertical-align: top;
        }

        .table-gaji table tr th,
        td {
            padding: 5px;
            font-size: .7rem;
        }

        .table-gaji>table td table tr th {
            text-align: left;
            font-weight: bold;
            width: 50%;
        }

        .table-gaji table tr td {
            text-align: right;
            width: 50%;
        }

        .small-info {
            font-size: .6rem;
            font-style: italic;
            text-align: left !important;
            font-weight: normal;
        }

        .grand-total h2 {
            font-size: 1.2rem;
            font-weight: bold;
            text-align: right;
        }

        .grand-total h3 {
            font-size: 1rem;
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="title">
                <h1>Slip Gaji</h1>
            </div>
        </div>

        <hr />

        <div class="content">

            <table class="table-info">
                <tr>
                    <td class="left">
                        <h1>Hybon Composite</h1>
                        <p>
                            Jl. Rawa Beunteur No. 49 RT 002/002 Ciriung<br />Cibinong Kab. Bogor Jawa Barat Indonesia
                            16918<br />Telp. 087802092214
                        </p>
                    </td>
                    <td class="right">
                        <table class="info-karyawan">
                            <tr>
                                <td>Periode</td>
                                <td>:</td>
                                <td>
                                    {{ $data->periode_cutoff->kehadiran_start->translatedFormat('d M y') }} -
                                    {{ $data->periode_cutoff->kehadiran_end->translatedFormat('d M y') }} (Hari
                                    Kerja)<br />
                                    {{ $data->periode_cutoff->lembur_start->translatedFormat('d M y') }} -
                                    {{ $data->periode_cutoff->lembur_end->translatedFormat('d M y') }} (Lembur)
                                </td>
                            </tr>
                            <tr>
                                <td>Nama Karyawan</td>
                                <td>:</td>
                                <td>{{ $data->karyawan->name }}</td>
                            </tr>
                            <tr>
                                <td>Departemen</td>
                                <td>:</td>
                                <td>{{ $data->karyawan->departement->name }}</td>
                            </tr>
                            @if ($data->karyawan->tipe_gaji == 'bulanan')
                                <tr>
                                    <td>Gaji Pokok</td>
                                    <td>:</td>
                                    <td>Rp {{ number_format($data->gaji_pokok, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td>Gaji Perhari</td>
                                <td>:</td>
                                <td>
                                    Rp {{ number_format($data->gaji_harian, 2, ',', '.') }}<br />
                                    @if ($data->karyawan->tipe_gaji == 'bulanan')
                                        <p class="small-info">
                                            (Gaji Pokok / Hari Kerja)
                                        </p>
                                        <p class="small-info">
                                            Rp. {{ number_format($data->gaji_pokok, 0, ',', '.') }} /
                                            {{ $data->periode_cutoff->hari_kerja }} Hari
                                        </p>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <hr />

            <div class="table-gaji">
                <table>
                    <tbody>
                        <tr>
                            <td>
                                <h6>Data Kehadiran & Lembur</h6>
                                <hr />
                                <table>
                                    <tr>
                                        <th>Hari Kerja</th>
                                        <td>{{ $data->periode_cutoff->hari_kerja }} Hari</td>
                                    </tr>
                                    <tr>
                                        <th>Total Presensi</th>
                                        <td>{{ $data->total_hari_kerja }} Hari</td>
                                    </tr>
                                    @if ($data->karyawan->tipe_gaji == 'bulanan')
                                        <tr>
                                            <th>Total Absensi</th>
                                            <td>{{ $data->total_hari_tidak_kerja }} Hari</td>
                                        </tr>
                                        <tr>
                                            <th>Cuti</th>
                                            <td>{{ $data->total_cuti }} Hari</td>
                                        </tr>
                                        <tr>
                                            <th>Sakit</th>
                                            <td>{{ $data->total_sakit }} Hari</td>
                                        </tr>
                                        <tr>
                                            <th>Ijin</th>
                                            <td>{{ $data->total_hari_ijin }} Hari</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th>Keterlambatan</th>
                                        <td>{{ $data->menit_terlambat }} Menit</td>
                                    </tr>
                                    <tr>
                                        <th>Lembur</th>
                                        <td>{{ $data->total_menit_lembur }} Menit</td>
                                    </tr>
                                </table>
                            </td>
                            <td>
                                <table>
                                    <tr>
                                        <td style="margin: 0px; padding: 4px;">
                                            <h6 style="margin: 0px; padding: 0px;">Penerimaan</h6>
                                            <hr />
                                            <table>
                                                <tr>
                                                    <th>
                                                        @if ($data->karyawan->tipe_gaji == 'bulanan')
                                                            Gaji Pokok
                                                        @else
                                                            Gaji
                                                        @endif
                                                    </th>
                                                    <td>
                                                        @if ($data->karyawan->tipe_gaji == 'bulanan')
                                                            Rp {{ number_format($data->gaji_pokok, 2, ',', '.') }}
                                                        @else
                                                            Rp {{ number_format($data->gaji_kehadiran, 2, ',', '.') }}
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Lembur<br />
                                                        <p class="small-info">
                                                            Rp.
                                                            {{ number_format(config('app.lembur_rate') / 60, 2, ',', '.') }}
                                                            x
                                                            {{ $data->total_menit_lembur }} Menit
                                                        </p>
                                                    </th>
                                                    <td>Rp {{ number_format($data->gaji_lembur, 2, ',', '.') }}</td>
                                                </tr>
                                            </table>

                                            <h6>Potongan</h6>
                                            <hr />
                                            <table>
                                                @if ($data->karyawan->tipe_gaji == 'bulanan')
                                                    <tr>
                                                        <th>
                                                            Absensi<br />
                                                            <p class="small-info">
                                                                Rp.
                                                                {{ number_format($data->gaji_harian, 2, ',', '.') }} x
                                                                {{ $data->total_hari_tidak_kerja }} Hari
                                                            </p>
                                                        </th>
                                                        <td>Rp
                                                            {{ number_format($data->potongan_tidak_kerja, 2, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <th>
                                                        Keterlambatan<br />
                                                        <p class="small-info">
                                                            Rp.
                                                            {{ number_format(config('app.potongan_terlambat') / 60, 0, ',', '.') }}
                                                            x
                                                            {{ $data->menit_terlambat }} Menit
                                                        </p>
                                                    </th>
                                                    <td>
                                                        Rp {{ number_format($data->potongan_terlambat, 2, ',', '.') }}
                                                    </td>
                                                </tr>
                                                @if ($data->karyawan->tipe_gaji == 'bulanan')
                                                    <tr>
                                                        <th>
                                                            Ijin<br />
                                                            <p class="small-info">
                                                                Rp.
                                                                {{ number_format($data->gaji_harian, 2, ',', '.') }} x
                                                                {{ $data->total_hari_ijin }} Hari
                                                            </p>
                                                        </th>
                                                        <td>Rp {{ number_format($data->potongan_ijin, 2, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <th>
                                                        Kasbon
                                                    </th>
                                                    <td>Rp {{ number_format($data->potongan_kasbon, 2, ',', '.') }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <hr />

            <div class="grand-total">
                <h2>
                    Take Home Pay:
                    <span>Rp. {{ number_format($data->take_home_pay, 2, ',', '.') }}</span>
                </h2>
            </div>
        </div>
    </div>
</body>

</html>
