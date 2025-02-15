<table>
    <thead>
        <tr>
            <th>NAMA</th>
            <th>GAJI KEHADIRAN</th>
            <th>LEMBUR</th>
            <th>PERBANTUAN SHIFT</th>
            <th>ABSENSI</th>
            <th>KETERLAMBATAN</th>
            <th>IJIN</th>
            <th>TAKE HOME PAY</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($recaps as $recap)
            <tr>
                <td>{{ $recap['nama_karyawan'] }}</td>
                <td>{{ $recap['gaji'] }}</td>
                <td>{{ $recap['lembur'] }}</td>
                <td>{{ $recap['perbantuan_shift'] }}</td>
                <td>{{ $recap['absensi'] }}</td>
                <td>{{ $recap['keterlambatan'] }}</td>
                <td>{{ $recap['ijin'] }}</td>
                <td>{{ $recap['take_home_pay'] }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="7" align="right">Grand Total</th>
            <th>{{ $gt }}</th>
        </tr>
    </tfoot>
</table>
