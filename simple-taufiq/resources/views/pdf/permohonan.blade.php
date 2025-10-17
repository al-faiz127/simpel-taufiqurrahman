<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Permohonan Bangkom</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1.6; margin: 40px; }
        .header { text-align: right; margin-bottom: 20px; }
        .section { margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        table, th, td { border: 1px solid #555; }
        th, td { padding: 5px; }
    </style>
</head>
<body>
    <div class="header">
        Samarinda, {{ now()->translatedFormat('d F Y') }}
    </div>

    <p>
        Nomor : <br>
        Lampiran : 1 Dokumen<br>
        Perihal : Permohonan Penerbitan Sertifikat Bangkom
    </p>

    <p>
        Yth. Kepala BPSDM Prov. Kaltim<br>
        di – Tempat
    </p>

    <p>
        Permohonan kerja sama dari Badan Pengembangan Sumber Daya Manusia Provinsi Kalimantan Timur
        sebagai Penjamin Mutu untuk dapat menerbitkan sertifikat kegiatan pengembangan kompetensi,
        sebagai berikut:
    </p>

    <div class="section">
        <table>
            <tr><td><strong>Instansi Pelaksana</strong></td><td>{{ $bangkom->instansi->nama ?? '-' }}</td></tr>
            <tr><td><strong>Unit Kerja</strong></td><td>{{ $bangkom->unit ?? '-' }}</td></tr>
            <tr><td><strong>Nama Kegiatan</strong></td><td>{{ $bangkom->kegiatan ?? '-' }}</td></tr>
            <tr><td><strong>Jenis</strong></td><td>{{ $bangkom->jenis_pelatihan->jenis ?? '-' }}</td></tr>
            <tr><td><strong>Bentuk</strong></td><td>{{ $bangkom->bentuk_pelatihan->bentuk ?? '-' }}</td></tr>
            <tr><td><strong>Sasaran</strong></td><td>{{ $bangkom->sasaran->sasaran ?? '-' }}</td></tr>
            <tr><td><strong>Tanggal Mulai</strong></td><td>{{ \Carbon\Carbon::parse($bangkom->mulai)->translatedFormat('d F Y') }}</td></tr>
            <tr><td><strong>Tanggal Berakhir</strong></td><td>{{ \Carbon\Carbon::parse($bangkom->selesai)->translatedFormat('d F Y') }}</td></tr>
            <tr><td><strong>Tempat</strong></td><td>{{ $bangkom->tempat ?? '-' }}</td></tr>
            <tr><td><strong>Alamat</strong></td><td>{{ $bangkom->alamat ?? '-' }}</td></tr>
            <tr><td><strong>Kuota</strong></td><td>{{ $bangkom->kuota ?? '-' }} Peserta</td></tr>
            <tr><td><strong>Deskripsi</strong></td><td>{{ $bangkom->deskripsi ?? '-' }}</td></tr>
            <tr><td><strong>Panitia</strong></td><td>{{ $bangkom->panitia ?? '-' }}</td></tr>
            <tr><td><strong>Kontak Panitia</strong></td><td>{{ $bangkom->tlpnpanitia ?? '-' }}</td></tr>
        </table>
    </div>

    @if (!empty($bangkom->kurikulum))
        <div class="section">
            <p><strong>Lampiran Materi Pelatihan</strong></p>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Materi</th>
                        <th>Narasumber</th>
                        <th>Jam Pelajaran (JP)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bangkom->kurikulum as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item['materi'] ?? '-' }}</td>
                            <td>{{ $item['narasumber'] ?? '-' }}</td>
                            <td>{{ $item['jam'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div style="margin-top: 50px;">
        <p>Kepala</p>
        <br><br><br>
        <p><strong>_________________________</strong><br>
        Pangkat<br>
        NIP.</p>
    </div>
</body>
</html>
