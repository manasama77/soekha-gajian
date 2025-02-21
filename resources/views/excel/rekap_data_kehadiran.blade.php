<table>
    <thead>
        <tr>
            <th>KARYAWAN</th>
            <th>TANGGAL</th>
            <th>SHIFT</th>
            <th>KEHADIRAN</th>
            <th>PULANG</th>
            <th>TERLAMBAT</th>
            <th>PER 30 MENIT</th>
            <th>STATUS</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data_kehadirans as $data_kehadiran)
            <tr>
                <td>{{ $data_kehadiran['nama_karyawan'] }}</td>
                <td>{{ $data_kehadiran['tanggal'] }}</td>
                <td>{{ $data_kehadiran['shift'] }}</td>
                <td>{{ $data_kehadiran['kehadiran'] }}</td>
                <td>{{ $data_kehadiran['pulang'] }}</td>
                <td>{{ $data_kehadiran['terlambat'] }}</td>
                <td>{{ $data_kehadiran['nilai_keterlambatan'] }}</td>
                <td>{{ $data_kehadiran['status'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
