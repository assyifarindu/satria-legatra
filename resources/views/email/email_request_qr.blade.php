{{-- <!DOCTYPE html>
<html>
<body>
    <p>Halo,</p>
    <p>Ini adalah pemberitahuan bahwa berkas yang Anda kirimkan
    <table>
        <tr>
            <th>File document : </th>
            <td><a href="{{ asset('storage/'.$requestDocumentQR->file) }}">Download Here</a></td>
        </tr>
        <tr>
            <th>Dibuat pada : </th>
            <td>@php echo App\Helpers\MyHelper::ubahFormatTimestamp($requestDocumentQR->created_at) @endphp</td>
        </tr>
    </table>
    <p>Menyatakan bahwa berkas tersebut Verified atau benar adanya berkas tersebut asli dikeluarkan oleh Patria Maritime Lines (PML) yang diverifikasi pada @php echo App\Helpers\MyHelper::ubahFormatTimestamp($requestDocumentQR->date_verification) @endphp</p>    
    <p>Terima kasih atas kerjasamanya.</p>
    <br>
    <p>Salam,</p>
    <p>Tim Legal Patria Maritime Lines</p>
</body>
</html> --}}


<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
    style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">

<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>LegATra</title>
</head>

<body itemscope itemtype="http://schema.org/EmailMessage"
    style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; background-color: #f6f6f6; margin: 0;"
    bgcolor="#f6f6f6">

    <table class="body-wrap"
        style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;"
        bgcolor="#f6f6f6">
        <tr
            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
            <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;"
                valign="top"></td>
            <td class="container" width="600"
                style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;"
                valign="top">
                <div class="content"
                    style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                    <table class="main" width="100%" cellpadding="0" cellspacing=" 0"
                        style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;"
                        bgcolor="#fff">
                        <tr
                            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                            <td class=""
                                style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 16px; vertical-align: top; color: #fff; font-weight: 500; text-align: center; border-radius: 3px 3px 0 0; background-color: #AF1B3F; margin: 0; padding: 20px;"
                                align="center" bgcolor="#71b6f9" valign="top">
                                <a href="#"> <svg width="274" height="70" viewBox="0 0 274 70"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_533_44)">
                                            <rect width="274" height="70" rx="5" fill="#AF1B3F" />
                                            <path
                                                d="M42.5 51V16H52.4V43.15H69.1V51H42.5ZM83.1699 51.45C79.9699 51.45 77.1699 50.85 74.7699 49.65C72.4033 48.4167 70.5533 46.75 69.2199 44.65C67.9199 42.5167 67.2699 40.1 67.2699 37.4C67.2699 34.7 67.9033 32.3 69.1699 30.2C70.4699 28.0667 72.2533 26.4167 74.5199 25.25C76.7866 24.05 79.3366 23.45 82.1699 23.45C84.8366 23.45 87.2699 24 89.4699 25.1C91.6699 26.1667 93.4199 27.75 94.7199 29.85C96.0199 31.95 96.6699 34.5 96.6699 37.5C96.6699 37.8333 96.6533 38.2167 96.6199 38.65C96.5866 39.0833 96.5533 39.4833 96.5199 39.85H75.0699V34.85H91.4699L87.8699 36.25C87.9033 35.0167 87.6699 33.95 87.1699 33.05C86.7033 32.15 86.0366 31.45 85.1699 30.95C84.3366 30.45 83.3533 30.2 82.2199 30.2C81.0866 30.2 80.0866 30.45 79.2199 30.95C78.3866 31.45 77.7366 32.1667 77.2699 33.1C76.8033 34 76.5699 35.0667 76.5699 36.3V37.75C76.5699 39.0833 76.8366 40.2333 77.3699 41.2C77.9366 42.1667 78.7366 42.9167 79.7699 43.45C80.8033 43.95 82.0366 44.2 83.4699 44.2C84.8033 44.2 85.9366 44.0167 86.8699 43.65C87.8366 43.25 88.7866 42.65 89.7199 41.85L94.7199 47.05C93.4199 48.4833 91.8199 49.5833 89.9199 50.35C88.0199 51.0833 85.7699 51.45 83.1699 51.45ZM110.8 61.15C108.2 61.15 105.717 60.85 103.35 60.25C101.017 59.65 99.0167 58.7667 97.35 57.6L100.8 50.95C101.933 51.85 103.333 52.55 105 53.05C106.7 53.5833 108.35 53.85 109.95 53.85C112.517 53.85 114.367 53.2833 115.5 52.15C116.633 51.05 117.2 49.4333 117.2 47.3V44.1L117.7 36.5L117.65 28.85V23.9H126.7V46.05C126.7 51.1833 125.317 54.9833 122.55 57.45C119.783 59.9167 115.867 61.15 110.8 61.15ZM109.15 49.6C106.75 49.6 104.533 49.0667 102.5 48C100.5 46.9 98.8833 45.3833 97.65 43.45C96.45 41.4833 95.85 39.1667 95.85 36.5C95.85 33.8333 96.45 31.5333 97.65 29.6C98.8833 27.6333 100.5 26.1167 102.5 25.05C104.533 23.9833 106.75 23.45 109.15 23.45C111.45 23.45 113.417 23.9167 115.05 24.85C116.683 25.75 117.917 27.1667 118.75 29.1C119.617 31.0333 120.05 33.5 120.05 36.5C120.05 39.5 119.617 41.9667 118.75 43.9C117.917 45.8333 116.683 47.2667 115.05 48.2C113.417 49.1333 111.45 49.6 109.15 49.6ZM111.4 42.05C112.533 42.05 113.533 41.8167 114.4 41.35C115.3 40.8833 116 40.2333 116.5 39.4C117.033 38.5667 117.3 37.6 117.3 36.5C117.3 35.4 117.033 34.4333 116.5 33.6C116 32.7667 115.3 32.1333 114.4 31.7C113.533 31.2333 112.533 31 111.4 31C110.267 31 109.25 31.2333 108.35 31.7C107.45 32.1333 106.733 32.7667 106.2 33.6C105.7 34.4333 105.45 35.4 105.45 36.5C105.45 37.6 105.7 38.5667 106.2 39.4C106.733 40.2333 107.45 40.8833 108.35 41.35C109.25 41.8167 110.267 42.05 111.4 42.05ZM170.356 51V23.85H159.606V16H190.956V23.85H180.256V51H170.356ZM190.157 51V23.9H199.207V31.8L197.857 29.55C198.657 27.5167 199.957 26 201.757 25C203.557 23.9667 205.741 23.45 208.307 23.45V32C207.874 31.9333 207.491 31.9 207.157 31.9C206.857 31.8667 206.524 31.85 206.157 31.85C204.224 31.85 202.657 32.3833 201.457 33.45C200.257 34.4833 199.657 36.1833 199.657 38.55V51H190.157ZM223.666 51V45.95L223.016 44.7V35.4C223.016 33.9 222.55 32.75 221.616 31.95C220.716 31.1167 219.266 30.7 217.266 30.7C215.966 30.7 214.65 30.9167 213.316 31.35C211.983 31.75 210.85 32.3167 209.916 33.05L206.716 26.6C208.25 25.6 210.083 24.8333 212.216 24.3C214.383 23.7333 216.533 23.45 218.666 23.45C223.066 23.45 226.466 24.4667 228.866 26.5C231.3 28.5 232.516 31.65 232.516 35.95V51H223.666ZM215.666 51.45C213.5 51.45 211.666 51.0833 210.166 50.35C208.666 49.6167 207.516 48.6167 206.716 47.35C205.95 46.0833 205.566 44.6667 205.566 43.1C205.566 41.4333 205.983 40 206.816 38.8C207.683 37.5667 209 36.6333 210.766 36C212.533 35.3333 214.816 35 217.616 35H224.016V39.95H218.916C217.383 39.95 216.3 40.2 215.666 40.7C215.066 41.2 214.766 41.8667 214.766 42.7C214.766 43.5333 215.083 44.2 215.716 44.7C216.35 45.2 217.216 45.45 218.316 45.45C219.35 45.45 220.283 45.2 221.116 44.7C221.983 44.1667 222.616 43.3667 223.016 42.3L224.316 45.8C223.816 47.6667 222.833 49.0833 221.366 50.05C219.933 50.9833 218.033 51.45 215.666 51.45Z"
                                                fill="white" />
                                            <path d="M124.393 53.25L147.661 6.37373L170.929 53.25H124.393Z"
                                                fill="white" stroke="#AF1B3F" stroke-width="3" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_533_44">
                                                <rect width="274" height="70" rx="5" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg></a> <br />
                                <span style="margin-top: 10px;display: block;">Result Request Document Validation</span>
                            </td>
                        </tr>
                        <tr
                            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                            <td class="content-wrap"
                                style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;"
                                valign="top">
                                <table width="100%" cellpadding="0" cellspacing="0"
                                    style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                    <tr
                                        style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block"
                                            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;"
                                            valign="top">
                                            <b>REMINDER!</b>
                                        </td>
                                    </tr>
                                    <tr
                                        style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block"
                                            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;"
                                            valign="top">
                                            Bersama dengan email ini kami ingin memberitahukan mengenai tindak lanjut
                                            mengenai permintaan dokumen yang anda kirimkan berikut :
                                            <br><br>
                                            <span>
                                                <b>File Document : </b> <a
                                                    href="{{ asset('storage/' . $data['file']) }}">Download Here</a>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr
                                        style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block"
                                            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;"
                                            valign="top">
                                            Menyatakan bahwa berkas tersebut Verified atau benar adanya berkas tersebut
                                            asli dikeluarkan oleh Patria Maritime Lines (PML) yang diverifikasi pada
                                            @php echo App\Helpers\MyHelper::ubahFormatTimestamp($data['date_verification']) @endphp
                                        </td>
                                    </tr>
                                    <tr
                                        style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <td class="content-block"
                                            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;"
                                            valign="top">
                                            Sekian yang dapat kami sampaikan.
                                            <br><br>
                                            Salam dan terima kasih, <br>
                                            Corporate Legal
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <div class="footer"
                        style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;">
                        <table width="100%"
                            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                            <tr
                                style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                <td class="aligncenter content-block"
                                    style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 12px; vertical-align: top; color: #999; text-align: center; margin: 0; padding: 0 0 20px;"
                                    align="center" valign="top">
                                    LegATra By Digitalization X Legal.
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
            <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;"
                valign="top"></td>
        </tr>
    </table>
</body>

</html>
