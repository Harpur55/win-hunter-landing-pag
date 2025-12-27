<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Sertifikat Taekwondo</title>

    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            margin: 0;
            font-family: "Times New Roman", Times, serif;
        }

        .certificate {
            width: 595px;
            height: 842px;
            padding: 40px;
            box-sizing: border-box;
            position: relative;
        }

        /* BORDER */
        .border-gold {
            border: 2px solid #D4AF37;
            width: 100%;
            height: 100%;
            padding: 5px;
            box-sizing: border-box;
            position: relative;
        }

        .border-inner {
            border: 1px solid #D4AF37;
            width: 100%;
            height: 100%;
            padding-top: 20px;
            box-sizing: border-box;
            text-align: center;
            position: relative;
        }

        /* HEADER */
        .pengurus-besar {
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #555;
            margin: 10px 0;
        }

        .taekwondo-title {
            font-size: 22px;
            font-weight: bold;
            color: #D4AF37;
            margin: 5px 0;
        }

        .no-sertifikat {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 10px;
            color: #555;
        }

        /* WATERMARK */
        .watermark {
            position: absolute;
            top: 300px;
            left: 170px;
            opacity: 0.15;
            z-index: 0;
        }

        .watermark img {
            width: 260px;
        }

        /* CONTENT */
        .content {
            position: relative;
            z-index: 2;
            margin-top: 30px;
        }

        .sertifikat-label {
            font-size: 34px;
            font-weight: bold;
            letter-spacing: 4px;
            color: #0d1b3e;
        }

        .sub-label {
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .given-to {
            font-size: 12px;
            font-style: italic;
            margin-top: 30px;
        }

        .name {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }

        /* TABLE INFO */
        .detail-table {
            width: 100%;
            font-size: 14px;
            margin: 20px auto;
        }

        .detail-table td {
            padding: 6px 5px;
            vertical-align: bottom;
        }

        .label {
            width: 170px;
            text-align: left;
        }

        .colon {
            width: 10px;
            text-align: center;
        }

        .value {
            border-bottom: 1px solid #000;
            padding-left: 10px;
        }

        .main-text {
            font-size: 13px;
            line-height: 1.6;
            margin-top: 20px;
            padding: 0 40px;
        }

        /* SIGNATURE */
        .footer-signatures {
            margin-top: 70px;
            text-align: end;
            font-size: 11px;
        }

        .sign-name {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 60px;
        }

        /* FOOTER */
        .bottom-strip {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 25px;
            background-color: #fbc02d;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            line-height: 25px;
        }
    </style>
</head>

<body>

    <div class="certificate">
        <div class="border-gold">
            <div class="border-inner">

                <div class="no-sertifikat">
                    No: {{ $noSertifikat }}
                </div>

                <div class="watermark">
                    <img src="{{ public_path('assets/images/download.jpg') }}">
                </div>

                <p class="pengurus-besar">Pengurus Besar</p>
                <h1 class="taekwondo-title">TAEKWONDO INDONESIA</h1>

                <div class="content">
                    <h2 class="sertifikat-label">SERTIFIKAT</h2>
                    <p class="sub-label">Kenaikan Tingkat</p>

                    <p class="given-to">Diberikan kepada</p>
                    <div class="name">{{ $pivot->nama_lengkap }}</div>

                    <table class="detail-table" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="label">No Register</td>
                            <td class="colon">:</td>
                            <td class="value">{{ $pivot->no_register }}</td>
                        </tr>
                        <tr>
                            <td class="label">Tempat / Tgl. Lahir</td>
                            <td class="colon">:</td>
                            <td class="value">
                                {{ $siswa->tempat_lahir ?? '-' }},
                                {{ \Carbon\Carbon::parse($pivot->tanggal_lahir)->translatedFormat('d F Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label">PENGPROV</td>
                            <td class="colon">:</td>
                            <td class="value">DKI Jakarta</td>
                        </tr>
                        <tr>
                            <td class="label">SABUK</td>
                            <td class="colon">:</td>
                            <td class="value">{{ strtoupper($pivot->next_belt_level) }}</td>
                        </tr>
                    </table>

                    <p class="main-text">
                        Telah dinyatakan <b>LULUS UJIAN KENAIKAN TINGKAT</b> yang diselenggarakan oleh
                        Pengurus Besar Taekwondo Indonesia (P.B.T.I.)
                    </p>

                    <p style="font-size:13px;">
                        Pada tanggal
                        {{ \Carbon\Carbon::parse($event->tanggal_ujian)->translatedFormat('d F Y') }}
                        di {{ $event->lokasi_ujian }}
                    </p>

                    <div class="footer-signatures">
                        <p>Pembina Club</p>
                        <p class="sign-name">SYAMSUL ARIPIN</p>
                        <p>DAN 4 / Kukkiwon</p>
                    </div>
                </div>

                <div class="bottom-strip">
                    Sertifikat ini Dikeluarkan oleh: Sacti Club Win Hunter
                </div>

            </div>
        </div>
    </div>

</body>

</html>
