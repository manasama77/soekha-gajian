<p align="center">
    <a href="https://hynon-gajian.sistegra.id" target="_blank">
        <img src="https://hybon-gajian.sistegra.id/hybon-logo-circle.png" width="100" alt="Hybon Logo">
    </a>
</p>

## About Hybon App

Hybon App adalah aplikasi yang digunakan untuk membantu Hybon mengatur kehadiran karyawan dan pembuatan slip gaji. Aplikasi ini dibangun menggunakan framework Laravel.

Fitur yang tersedia dalam Hybon App:

-   Login
-   Dashboard
-   Departement Management
-   Karyawan Management
-   Periode Cutoff Management
-   Hari Libur Management
-   Data Kasbon Management
-   Data Kehadiran Management
-   Request Kehadiran Management
-   Data Lembur Management
-   Data Ijin Management
-   Slip Gaji

_\*Fitur akan ditambahkan sesuai kebutuhan_

## License

-   [Adam Prasetya Malik](https://github.com/manasama77)

Aplikasi ini dibawah pengawasan dan kepemilikan dari [PT. Sistegra Emran Sentosa](https://sistegra.id).

## Server Requirements

-   **[PHP 8.2](https://www.php.net/)**
-   **[MariaDB 10.6.20](https://mariadb.org/)**
-   **[Node](https://nodejs.org/en/)**
-   **[PHP Extension - GD](https://www.php.net/manual/en/book.gd)**

## Support

Jika kamu merasa terbantu dengan aplikasi ini, jangan lupa traktir saya dengan bintang di [GitHub](https://github.com/manasama77/hybon-app) atau trakteer saya di:

<a href="https://trakteer.id/adam_pm" target="_blank"><img id="wse-buttons-preview" src="https://edge-cdn.trakteer.id/images/embed/trbtn-red-1.png?date=18-11-2023" height="40" style="border:0px;height:40px;" alt="Trakteer Saya"></a>

## Version Release

-   **Version 1.0.0**
    -   Initial Release
-   **Version 1.0.1**
    -   Fix Bug Kasbon tidak bisa edit dan hapus
    -   Kehadiran, perubahan dari jam terlambat menjadi menit terlambat
    -   Lembur, perubahan dari jam lembur menjadi menit lembur
-   **Version 1.1.0**
    -   Perubahan template
    -   Penambahan fitur Request Kehadiran
    -   Penambahan filter pada menu Kehadiran & Lembur
    -   Pembuatan halaman Dashboard
    -   Penambahan fitur hapus data Kehadiran
-   **Version 1.1.1**
    -   Perbaikan bug lembur multiple submit
    -   Perbaikan ijin tidak bisa di Approve atau reject karena masalah constraint
-   **Version 1.1.2**
    -   Lembur bisa dihapus setelah diapprove
-   **Version 1.1.3**
    -   Dashboard berubah grid untuk desktop version dari grid 4 ke 2
    -   Dashboard Potongan Tidak Hadir dihilangkan, diganti menjadi Gaji Kehadiran
    -   Request Ijin To Date (tanggal akhir ijin) diberi batasan sesuai periode cutoff
    -   Data Lembur penambahan filter status
    -   Karyawan dengan tipe gaji harian tidak dapat akses Request Ijin
    -   Perbaikan bug karyawan bisa akses halaman setup
-   **Version 1.1.4**
    -   Perubahan Dashboard dibuat 2 tipe, dashboard untuk admin & karyawan
    -   Ijin -> "Sakit dengan surat dokter" dirubah tidak memotong jatah cuti
-   **Version 1.1.5**
    -   Perbaikan proses approve Ijin -> "Sakit dengan surat dokter" dirubah tidak memotong jatah cuti
    -   Lembur -> Create, diberikan min dan max date time
-   **Version 1.1.6**
    -   Menghilangkan relasi periode_cutoff_id dari table data_ijins
    -   Perbaikan cara penghitungan potongan ijin pada dashboard yang sebelumnya tidak menghitung total hari ijin
    -   Perubahan field gaji_lembur dari integer menjadi decimal pada table slip_gajis
    -   Perubahan cara penghitungan gaji_lembur khusus untuk tanggal yang berbatasan dengan tanggal akhir lembur
