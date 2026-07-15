<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemberitahuan Pendaftaran — AETH Clinic</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            background-color: #F5F0EB;
            color: #3A3530;
            line-height: 1.6;
        }
        .wrapper {
            max-width: 560px;
            margin: 40px auto;
            background: #FFFFFF;
            border: 1px solid #E0D8D0;
        }
        .header {
            background-color: #3A3530;
            padding: 32px 40px;
            text-align: center;
        }
        .header h1 {
            color: #F5F0EB;
            font-size: 22px;
            font-weight: normal;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .header p {
            color: #C9A882;
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 4px;
        }
        .body {
            padding: 40px;
        }
        .salutation {
            font-size: 18px;
            color: #3A3530;
            margin-bottom: 16px;
        }
        .text {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 14px;
            color: #6B5E52;
            margin-bottom: 16px;
        }
        .reason-box {
            background-color: #FEF2F2;
            border-left: 3px solid #EF4444;
            padding: 16px 20px;
            margin: 24px 0;
        }
        .reason-label {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #EF4444;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .reason-text {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 14px;
            color: #7F1D1D;
            font-style: italic;
        }
        .divider {
            border: none;
            border-top: 1px solid #E0D8D0;
            margin: 32px 0;
        }
        .cta {
            text-align: center;
            margin: 24px 0;
        }
        .cta a {
            display: inline-block;
            background-color: #3A3530;
            color: #F5F0EB;
            text-decoration: none;
            padding: 12px 32px;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .footer {
            background-color: #F5F0EB;
            padding: 24px 40px;
            text-align: center;
            border-top: 1px solid #E0D8D0;
        }
        .footer p {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 11px;
            color: #9B8E85;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>AETH Clinic</h1>
            <p>Beauty & Wellness</p>
        </div>

        <div class="body">
            <p class="salutation">Yth. {{ $user->name }},</p>

            <p class="text">
                Terima kasih telah mendaftar di <strong>AETH Clinic</strong>. Kami telah meninjau permohonan pendaftaran akun Anda.
            </p>

            <p class="text">
                Mohon maaf, setelah melalui proses peninjauan, pendaftaran akun Anda saat ini <strong>tidak dapat kami setujui</strong> dengan alasan sebagai berikut:
            </p>

            <div class="reason-box">
                <p class="reason-label">Alasan Penolakan</p>
                <p class="reason-text">{{ $rejectionReason }}</p>
            </div>

            <p class="text">
                Jika Anda memiliki pertanyaan atau ingin mengajukan permohonan kembali setelah melengkapi persyaratan, jangan ragu untuk menghubungi kami atau mendaftar ulang.
            </p>

            <hr class="divider">

            <div class="cta">
                <a href="{{ config('app.url') }}">Kunjungi Website Kami</a>
            </div>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} AETH Clinic. All rights reserved.</p>
            <p style="margin-top: 4px;">Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
