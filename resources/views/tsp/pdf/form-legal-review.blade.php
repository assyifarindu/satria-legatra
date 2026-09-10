<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Form Legal Review</title>
    <style>
        @page {
            margin: 20px 25px;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            margin: 0;
            color: #222;
        }

        /* .header {
            width: 100%;
            margin-bottom: 12px;
        } */

        .logo {
            text-align: left;
            margin-bottom: 5px;
        }

        .logo img {
            width: 100px;
            height: auto;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            line-height: 1.3;
            margin-bottom: 2px;
        }

        .company-name {
            text-align: center;
            font-size: 9px;
            font-weight: bold;
        }

        /*  SECTION TITLE */

        .section-title {
            font-weight: bold;
            font-size: 10px;
            margin-top: 10px;
            margin-bottom: 3px;
        }

        /* TABLE */

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            border: 1px solid #555;
            padding: 3px 5px;
            vertical-align: middle;
        }

        /* DOCUMENT INFORMATION */

        .document-info {
            width: 100%;
        }

        .document-info .label {
            width: 22%;
            font-weight: bold;
        }

        .document-info .separator {
            width: 3%;
            text-align: center;
        }

        .document-info .value {
            width: 75%;
        }

        .document-info tr {
            height: 17px;
        }

        /*  DOCUMENT CONTENT*/

        /* .content-table {
            width: 100%;
        }

        .content-table td {
            width: 33.33%;
            height: 17px;
        }

        .content-table .empty {
            height: 17px;
        } */
        .content-table {
            width: 100%;
            border-collapse: collapse;
        }

        .content-table td {
            height: 17px;
            padding: 2px 5px;
            vertical-align: middle;
        }

        .content-table .content-col {
            width: 32%;
        }

        .content-table .separator-col {
            width: 2%;
        }

        .completeness-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .completeness-table th,
        .completeness-table td {
            border: 1px solid #555;
            padding: 3px 5px;
            vertical-align: middle;
            font-size: 9px;
        }

        .completeness-table th {
            text-align: center;
            font-weight: bold;
        }

        .completeness-table .number-col {
            width: 3%;
            text-align: center;
        }

        .completeness-table .form-col {
            width: 43%;
        }

        .completeness-table .pic-dept-col {
            width: 18%;
            text-align: center;
        }

        .completeness-table .note-col {
            width: 36%;
        }

        .summary-col {
            vertical-align: top;
        }

        .special-info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2px;
        }

        .special-info-table td {
            border: none;
            padding: 0;
            vertical-align: top;
        }

        .special-info-table .info-code {
            display: inline-block;
            width: 5px;
            font-weight: bold;
        }

        .special-info-table .info-content {
            width: auto;
            margin-left: 0;
        }

        .info-name {
            font-weight: bold;
            line-height: 1.1;
        }

        .info-status {
            margin-left: 5px;
            line-height: 1.1;
        }

        /* INPUT LINE */

        .input-line {
            display: inline-block;
            width: 100%;
            min-height: 12px;
        }

        .dept {
            width: 30%;
            display: inline-block;
            font-weight: bold;
        }

        .pic-table {
            width: 100%;
            border-collapse: collapse;
        }

        .pic-table td {
            border: none;
            padding: 0;
        }

        .pic-name {
            width: 55%;
        }

        .pic-dept-label {
            width: 10%;
            font-weight: bold;
        }

        .pic-dept {
            width: 35%;
        }

        /* APPROVAL SECTION */
        .approval-section {
            margin-top: 250px;
            width: 100%;
        }

        .approval-date {
            text-align: center;
            font-size: 10px;
            margin-bottom: 3px;
        }

        .approval-title {
            text-align: center;
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 15px;
        }

        .approval-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .approval-table td {
            border: none;
            padding: 0 5px;
            text-align: center;
            vertical-align: top;
        }

        .approval-position {
            margin-top: 0;
            font-weight: bold;
            text-decoration: underline;
            height: 20px;
        }

        /* AREA TANDA TANGAN */
        .signature-space-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
            margin: 0;
            padding: 0;
        }

        .signature-space-table td {
            border: none !important;
            height: 50px;
            padding: 0;
            margin: 0;
            font-size: 1px;
            line-height: 1px;
        }

        /* GARIS TANDA TANGAN */
        .signature-line {
            border-top: 1px solid #333;
            width: 100%;
            height: 1px;
            margin: 0 0 3px 0;
        }

        .approval-name {
            font-size: 10px;
            text-align: center;
        }

        .approval-note {
            margin-top: 65px;
            font-size: 9px;
            line-height: 1.2;
            margin-left: 30px;
            margin-right: 30px;
        }

        /*  FOOTER */

        /* @page {
            margin-top: 15px;
            margin-right: 15px;
            margin-bottom: 45px;
            margin-left: 15px;

            footer: page-footer;
        } */
        @page {
            margin-top: 100px;
            margin-right: 15px;
            margin-bottom: 45px;
            margin-left: 15px;

            header: legal-review-header;
            footer: page-footer;
        }
    </style>
</head>

<body>
    <htmlpageheader name="legal-review-header">

        <table width="100%" style="border: none; border-collapse: collapse;">
            <tr>
                <td width="25%" style="border: none; vertical-align: middle; text-align: left;">
                    <img src="{{ public_path('assets/images/tsp-logo.png') }}" style="width: 100px;">
                </td>

                <td width="25%" style="border: none;">
                    &nbsp;
                </td>
            </tr>
        </table>

    </htmlpageheader>
    <div class="title">FORM<br>LEGAL REVIEW<br><small>PT TRIATRA SINERGIA PRATAMA</small></div>

    <div class="section-title">
        A. &nbsp; Data dan Informasi Dokumen
    </div>


    <table class="document-info">

        <tr>
            <td class="label">
                Tanggal Dokumen
            </td>

            <td class="separator">
                :
            </td>

            <td class="value">
                {{ $flr->date ?? '' }}
            </td>
        </tr>


        <tr>
            <td class="label">
                PIC Dokumen
            </td>

            <td class="separator">
                :
            </td>

            <td class="value">

                <table class="pic-table">
                    <tr>
                        <td class="pic-name">
                            {{ $flr->pic_name ?? '' }}
                        </td>

                        <td class="pic-dept-label">
                            Dept :
                        </td>

                        <td class="pic-dept">
                            {{ $flr->department ?? '' }}
                        </td>
                    </tr>
                </table>

            </td>
        </tr>


        <tr>
            <td class="label">
                Nama Pihak
            </td>

            <td class="separator">
                :
            </td>

            <td class="value">
                {{ $flr->customer_name ?? '' }}
            </td>
        </tr>


        <tr>
            <td class="label">
                Judul Dokumen
            </td>

            <td class="separator">
                :
            </td>

            <td class="value">
                {{ $flr->title ?? '' }}
            </td>
        </tr>


        <tr>
            <td class="label">
                Nomor Dokumen
            </td>

            <td class="separator">
                :
            </td>

            <td class="value">
                {{ $flr->document_number ?? '' }}
            </td>
        </tr>


        <tr>
            <td class="label">
                Objektif Dokumen
            </td>

            <td class="separator">
                :
            </td>

            <td class="value">
                {{ $flr->document_objective ?? '' }}
            </td>
        </tr>


        <tr>
            <td class="label">
                Jangka Waktu
            </td>

            <td class="separator">
                :
            </td>

            <td class="value">
                {{ $flr->period_time ?? '' }}
            </td>
        </tr>


        <tr>
            <td class="label">
                Incoterms
            </td>

            <td class="separator">
                :
            </td>

            <td class="value">
                {{ $flr->incoterm ?? '' }}
            </td>
        </tr>


        <tr>
            <td class="label">
                Lokasi Pekerjaan
            </td>

            <td class="separator">
                :
            </td>

            <td class="value">
                {{ $flr->work_location ?? '' }}
            </td>
        </tr>


        <tr>
            <td class="label">
                Lokasi Pengiriman
            </td>

            <td class="separator">
                :
            </td>

            <td class="value">
                {{ $flr->delivery_location ?? '' }}
            </td>
        </tr>


        <tr>
            <td class="label">
                TOP
            </td>

            <td class="separator">
                :
            </td>

            <td class="value">
                {{ $flr->term_of_payment ?? '' }}
            </td>
        </tr>

    </table>

    <div class="section-title">
        B. &nbsp; Daftar Isi Dokumen
    </div>

    <table class="content-table">

        <tr>
            <td class="separator-col"></td>
            <td class="content-col">
                Identitas Para Pihak
            </td>

            <td class="separator-col"></td>

            <td class="content-col">
                Perubahan Perjanjian
            </td>

            <td class="separator-col"></td>

            <td class="content-col">
                Kerahasiaan
            </td>
        </tr>

        <tr>
            <td class="separator-col"></td>
            <td class="content-col">
                Maksud dan Tujuan
            </td>

            <td class="separator-col"></td>

            <td class="content-col">
                Pengakhiran Perjanjian
            </td>

            <td class="separator-col"></td>

            <td class="content-col">
                Force Majeure
            </td>
        </tr>

        <tr>
            <td class="separator-col"></td>
            <td class="content-col">
                Jangka Waktu
            </td>

            <td class="separator-col"></td>

            <td class="content-col">
                Peralihan Perjanjian
            </td>

            <td class="separator-col"></td>

            <td class="content-col">
                Perpajakan
            </td>
        </tr>

        <tr>
            <td class="separator-col"></td>
            <td class="content-col">
                Hak dan Kewajiban Para Pihak
            </td>

            <td class="separator-col"></td>

            <td class="content-col">
                Sanksi/Denda
            </td>

            <td class="separator-col"></td>

            <td class="content-col">
                Hukum yang Berlaku dan Penyelesaian Sengketa
            </td>
        </tr>

        <tr>
            <td class="separator-col"></td>
            <td class="content-col">
                Harga dan Cara Pembayaran
            </td>

            <td class="separator-col"></td>

            <td class="content-col">
                Perlindungan Data Pribadi
            </td>

            <td class="separator-col"></td>

            <td class="content-col"></td>
        </tr>

        <tr>
            <td class="separator-col"></td>
            <td class="content-col">
                Korespondensi
            </td>

            <td class="separator-col"></td>

            <td class="content-col">
                Pernyataan dan Jaminan
            </td>

            <td class="separator-col"></td>

            <td class="content-col"></td>
        </tr>

    </table>
    <div class="section-title">
        C. &nbsp; Form Kelengkapan
    </div>

    <table class="completeness-table">

        <thead>
            <tr>
                {{-- <th class="number-col"></th> --}}

                <th class="form-col" colspan="2">
                    Form
                </th>

                <th class="pic-dept-col">
                    PIC Dept
                </th>

                <th class="note-col">
                    Catatan
                </th>
            </tr>
        </thead>

        <tbody>

            <tr>
                <td class="number-col">

                </td>

                <td class="form-col">
                    Identifikasi Bahaya Potensi Resiko (IBPR)
                </td>

                <td class="pic-dept-col">
                    Sustainability
                </td>

                <td class="note-col">
                    Wajib untuk Perjanjian Service
                </td>
            </tr>


            <tr>
                <td class="number-col">

                </td>

                <td class="form-col">
                    Job Safety Analysis (JSA)
                </td>

                <td class="pic-dept-col">
                    Sustainability
                </td>

                <td class="note-col">
                    Wajib untuk Perjanjian Service
                </td>
            </tr>


            <tr>
                <td class="number-col">

                </td>

                <td class="form-col">
                    Simulasi Profit
                </td>

                <td class="pic-dept-col">
                    Marketing
                </td>

                <td class="note-col">
                    Wajib untuk VHS dan Perjanjian Service
                </td>
            </tr>

        </tbody>

    </table>

    <div class="section-title">
        D. &nbsp; Penandatangan Dokumen
    </div>

    <table class="content-table">

        <tr>
            <td class="separator-col"></td>
            <td class="content-col">
                David (Presiden Direktur)
            </td>

            <td class="separator-col"></td>

            <td class="content-col">
                Erwin Oktariyanto (Direktur)
            </td>
        </tr>

        <tr>
            <td class="separator-col"></td>
            <td class="content-col">
                Chrisman Wibowo (Direktur)
            </td>

            <td class="separator-col"></td>

            <td class="content-col">
                Ceisar Centiaga (Direktur)
            </td>

        </tr>

    </table>
    <span style="color: red; font-style: italic;">
        *dicentang setelah Dokumen ditandatangani.
    </span>

    <div class="section-title">
        E. &nbsp; Resume Kontrak dan Catatan Legal
    </div>

    <table class="completeness-table">

        <thead>
            <tr>
                <th class="number-col">No</th>

                <th class="form-col">
                    Resume Kontrak
                </th>

                <th class="pic-dept-col">
                    Catatan Legal
                </th>

                <th class="note-col">
                    Wajib Divalidasi oleh
                </th>
            </tr>
        </thead>

        <tbody>

            <tr>
                <td class="number-col">
                    1
                </td>

                <td>
                    -
                </td>

                <td>
                    -
                </td>

                <td>
                    SUS-FIN-PIN
                </td>
            </tr>


            <tr>
                <td class="number-col">
                    2.
                </td>

                <td class="summary-col">

                    <strong>Informasi Khusus</strong>

                    <table class="special-info-table">

                        <tr>
                            <td class="info-code">a.</td>
                            <td class="info-content">
                                <div class="info-name">Investasi</div>
                                <div class="info-status">Tersedia/ Tidak tersedia</div>
                            </td>
                        </tr>

                        <tr>
                            <td class="info-code">b.</td>
                            <td class="info-content">
                                <div class="info-name">Penyediaan Manpower</div>
                                <div class="info-status">Tersedia/ Tidak tersedia</div>
                            </td>
                        </tr>

                        <tr>
                            <td class="info-code">c.</td>
                            <td class="info-content">
                                <div class="info-name">Sanksi</div>
                                <div class="info-status">Tersedia/ Tidak tersedia</div>
                            </td>
                        </tr>

                        <tr>
                            <td class="info-code">d.</td>
                            <td class="info-content">
                                <div class="info-name">Denda</div>
                                <div class="info-status">Tersedia/ Tidak tersedia</div>
                            </td>
                        </tr>

                        <tr>
                            <td class="info-code">e.</td>
                            <td class="info-content">
                                <div class="info-name">Asuransi</div>
                                <div class="info-status">Tersedia/ Tidak tersedia</div>
                            </td>
                        </tr>

                        <tr>
                            <td class="info-code">f.</td>
                            <td class="info-content">
                                <div class="info-name">SLA</div>
                                <div class="info-status">Tersedia/ Tidak tersedia</div>
                            </td>
                        </tr>

                    </table>

                </td>

                <td></td>

                <td></td>
            </tr>

        </tbody>

    </table>

    <div class="approval-section">

        <div class="approval-date">
            Jakarta, {{ \Carbon\Carbon::now()->format('d M Y') }}
        </div>

        <div class="approval-title">
            Mengetahui dan Menyetujui
        </div>


        <table class="approval-table">

            <tr>

                @foreach ($committees as $committee)
                    <td class="approval-column">

                        <div class="approval-position">
                            {{ $committee->committee_department ?? 'Committee' }}
                        </div>

                        {{-- AREA TANDA TANGAN --}}
                        <table class="signature-space-table">
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                        </table>

                        {{-- GARIS TANDA TANGAN --}}
                        <div class="signature-line"></div>

                        <div class="approval-name">
                            {{ $committee->committee_name }}
                        </div>

                    </td>
                @endforeach

            </tr>

        </table>

        <div class="approval-note">

            <div class="note-title">
                Catatan:
            </div>

            <div class="note-content">
                Dengan ini kami menyatakan bahwa penandatangan Form Legal Review ini
                secara sadar dan tanpa paksaan telah membaca, menelaah, memahami,
                serta menyetujui seluruh isi dokumen, termasuk setiap ketentuan,
                implikasi hukum, dan potensi risiko yang terkandung di dalamnya.
                Dengan demikian, masing-masing Pihak sepakat bahwa setiap
                konsekuensi dan/atau risiko yang timbul di kemudian hari sehubungan
                dengan pelaksanaan Perjanjian/Kontrak merupakan tanggung jawab
                bersama para pihak.
            </div>

        </div>

    </div>

    <htmlpagefooter name="page-footer">
        <table width="100%"
            style="
            border: none;
            border-collapse: collapse;
            font-size: 8px;
            background: transparent;
        ">
            <tr>
                <td width="70%"
                    style="
                    text-align: left;
                    padding-top: 5px;
                    border: none;
                    background: transparent;
                ">
                    <span
                        style="
                        color: #d9534f;
                        font-weight: bold;
                        font-style: italic;
                    ">
                        DOKUMEN BERSIFAT RAHASIA
                    </span>
                    <br>
                    <span style="color: #777;">
                        dari {PAGENO}
                    </span>
                </td>

                <td width="30%"
                    style="
                    text-align: right;
                    padding-top: 5px;
                    color: #777;
                    border: none;
                    background: transparent;
                ">
                    Halaman&nbsp;&nbsp; {PAGENO}
                </td>
            </tr>
        </table>
    </htmlpagefooter>

</body>

</html>
