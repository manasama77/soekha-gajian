<p align="center">
    <a href="https://soekha.solusikami.co.id" target="_blank">
        <img src="https://soekha.solusikami.co.id/SOEKHA-MINI-LOGO.jpeg" width="100" alt="Hybon Logo">
    </a>
</p>

## About Soekha Gajian App

Soekha Gajian App adalah aplikasi yang digunakan untuk membantu Hybon mengatur kehadiran karyawan dan pembuatan slip gaji. Aplikasi ini dibangun menggunakan framework Laravel.

Fitur yang tersedia dalam Soekha Gajian App:

-   Login
-   Dashboard
-   Departement Management
-   Karyawan Management
-   Periode Cutoff Management
-   Shift Management
-   Work Day Management
-   Data Kehadiran Management
-   Request Kehadiran Management
-   Data Lembur Management
-   Data Ijin Management
-   Slip Gaji
-   Excel Biaya Pengeluaran untuk gaji

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

Jika kamu merasa terbantu dengan aplikasi ini, jangan lupa traktir saya dengan bintang di [GitHub](https://github.com/manasama77/soekha-gajian) atau trakteer saya di:

<a href="https://trakteer.id/adam_pm" target="_blank"><img id="wse-buttons-preview" src="https://edge-cdn.trakteer.id/images/embed/trbtn-red-1.png?date=18-11-2023" height="40" style="border:0px;height:40px;" alt="Trakteer Saya"></a>

## Version Release

-   **Version 1.0.0**
    -   Initial Release
-   **Version 1.0.1**
    -   Bug fix Delete Karyawan
    -   Bug fix Tambah Work Day tidak menghitung hari libur
    -   Penambahan Gaji Perbantuan Shift pada menu Karyawan
    -   Penambahan Tipe Perbantuan Shift pada menu Shift
    -   Penambahan flag perbantuan shift pada saat presensi
    -   Penambahan flag perbantuan shift pada saat request kehadiran
    -   Penambahan data perbantuan shift pada saat generate slip gaji
    -   Penambahan data perbantuan shift pada saat export excel biaya pengeluaran gaji
-   **Version 1.0.2**
    -   **Setup > Karyawan**. Bug fix Reset Password Karyawan
    -   **Presensi**. Penambahan fitur Export Excel Data Kehadiran (Admin)
    -   **Presensi**. Perubahan jenis filter dari Bulan dan Tahun menjadi Periode Cutoff
    -   **Presensi**. Perubahan dropdown filter mempengaruhi data ketika export excel
    -   **Presensi**. Perbaikan ordering list karyawan menjadi **alphabetically ascending**
    -   **Presensi**. Menambahkan pilihan tipe kehadiran **Clock In** dan **Clock Out** pada saat input data kehadiran
-   **Version 1.0.3**
    -   **Dashboard**. Perbaikan penghitungan dashboard untuk persiapan versi 2.0.0
-   **Version 1.0.4**
    -   **Dashboard**. Perbaikan penghitungan keterlambatan dashboard untuk persiapan versi 2.0.0
-   **Version 1.0.5**
    -   **Slip Gaji**. Perbaikan permission untuk download, persiapan untuk versi 2.0.0
