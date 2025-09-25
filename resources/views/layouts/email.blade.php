@php
    $appName = config('app.name', 'Laravel');
    $logoPath = config('filament-users.email.logo');
    if (!empty($logoPath)) {
        $logoPath = public_path($logoPath);
    }
    $footerText = config('filament-users.email.footer_text', '© ' . $appName . ' ' . date('Y') . '. Tutti i diritti riservati.');
@endphp
    <!DOCTYPE html>
<html lang="it" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>@yield('title', $appName)</title>

    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->

    <style>
        /* Reset styles */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }

        /* Base styles */
        body {
            margin: 0 !important;
            padding: 0 !important;
            background-color: #f8f9fa !important;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .email-header {
            background-color: #e9ecef;
            padding: 40px 20px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
        }

        .email-body {
            padding: 40px 30px;
            background-color: #ffffff;
        }

        .email-footer {
            background-color: #f1f3f4;
            padding: 30px 20px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }

        .logo {
            max-width: 200px;
            height: auto;
            display: block;
            margin: 0 auto;
            filter: grayscale(20%);
        }

        .title {
            color: #495057;
            font-size: 28px;
            font-weight: 600;
            margin: 20px 0 0 0;
            line-height: 1.3;
            letter-spacing: -0.5px;
        }

        .content h1 {
            color: #212529;
            font-size: 24px;
            font-weight: 600;
            margin: 0 0 20px 0;
            line-height: 1.3;
        }

        .content h2 {
            color: #495057;
            font-size: 20px;
            font-weight: 500;
            margin: 30px 0 15px 0;
            line-height: 1.3;
        }

        .content p {
            color: #6c757d;
            font-size: 16px;
            line-height: 1.6;
            margin: 0 0 20px 0;
        }

        .content .lead {
            font-size: 18px;
            color: #495057;
            font-weight: 400;
            margin-bottom: 30px;
        }

        .button {
            display: inline-block;
            padding: 14px 28px;
            background-color: #6c757d;
            color: #ffffff !important;
            text-decoration: none !important;
            border-radius: 6px;
            font-weight: 500;
            font-size: 16px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(108, 117, 125, 0.2);
            transition: all 0.2s ease;
            border: 1px solid #5a6268;
        }

        .button:hover {
            background-color: #5a6268;
            box-shadow: 0 4px 8px rgba(108, 117, 125, 0.25);
            transform: translateY(-1px);
        }

        .button-light {
            background-color: #e9ecef;
            color: #495057 !important;
            border: 1px solid #ced4da;
        }

        .button-light:hover {
            background-color: #dee2e6;
            color: #212529 !important;
        }

        .credentials-box {
            background-color: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 25px;
            margin: 25px 0;
        }

        .credential-item {
            margin: 15px 0;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        .credential-label {
            font-weight: 500;
            color: #495057;
            display: inline-block;
            width: 80px;
            margin-right: 10px;
            margin-bottom: 5px;
        }

        .credential-value {
            color: #212529;
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
            background-color: #ffffff;
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid #ced4da;
            flex: 1;
            min-width: 200px;
            font-size: 14px;
        }

        .alert {
            padding: 18px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 4px solid;
        }

        .alert-info {
            background-color: #f8f9fa;
            border-left-color: #adb5bd;
            color: #495057;
            border: 1px solid #e9ecef;
        }

        .alert-warning {
            background-color: #fefefe;
            border-left-color: #ced4da;
            color: #6c757d;
            border: 1px solid #e9ecef;
        }

        .alert-success {
            background-color: #f8f9fa;
            border-left-color: #adb5bd;
            color: #495057;
            border: 1px solid #e9ecef;
        }

        .footer-text {
            color: #868e96;
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
        }

        .footer-links {
            margin-top: 15px;
        }

        .footer-link {
            color: #6c757d;
            text-decoration: none;
            font-size: 14px;
            margin: 0 10px;
            padding-bottom: 2px;
            border-bottom: 1px solid transparent;
            transition: border-bottom-color 0.2s ease;
        }

        .footer-link:hover {
            border-bottom-color: #adb5bd;
        }

        .divider {
            border: 0;
            height: 1px;
            background: linear-gradient(to right, transparent, #dee2e6, transparent);
            margin: 30px 0;
        }

        .text-center {
            text-align: center;
        }

        .text-muted {
            color: #868e96 !important;
        }

        .text-small {
            font-size: 14px;
        }

        /* Responsive styles */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
                margin: 0 !important;
                border-radius: 0 !important;
            }

            .email-header {
                padding: 30px 20px !important;
            }

            .email-body {
                padding: 30px 20px !important;
            }

            .title {
                font-size: 24px !important;
            }

            .content h1 {
                font-size: 22px !important;
            }

            .content p {
                font-size: 15px !important;
            }

            .button {
                display: block !important;
                width: 100% !important;
                text-align: center !important;
                box-sizing: border-box !important;
            }

            .credentials-box {
                padding: 20px !important;
            }

            .credential-item {
                flex-direction: column !important;
                align-items: flex-start !important;
            }

            .credential-label {
                width: auto !important;
                margin-bottom: 8px !important;
                margin-right: 0 !important;
            }

            .credential-value {
                width: 100% !important;
                min-width: auto !important;
                box-sizing: border-box !important;
            }
        }

        /* Print styles */
        @media print {
            .email-container {
                box-shadow: none !important;
            }

            .button {
                background-color: transparent !important;
                color: #495057 !important;
                border: 2px solid #495057 !important;
            }
        }
    </style>

    @yield('styles')
</head>
<body style="margin: 0; padding: 0; background-color: #f8f9fa;">
<div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
    @yield('preheader', 'Email da ' . $appName)
</div>

<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0; padding: 0;">
    <tr>
        <td style="padding: 20px 10px;">
            <div class="email-container">
                <!-- Header -->
                <div class="email-header">
                    @if(is_file($logoPath) && file_exists($logoPath))
                        <img src="{{ $message->embed(public_path($logoPath)) }}" alt="{{ $appName }}" class="logo">
                    @else
                        <h1 class="title">{{ $appName }}</h1>
                    @endif

                    @yield('header')
                </div>

                <!-- Body -->
                <div class="email-body">
                    <div class="content">
                        @yield('content')
                    </div>
                </div>

                <!-- Footer -->
                <div class="email-footer">
                    <p class="footer-text">{!! $footerText !!}</p>

                    @hasSection('footer-links')
                        <div class="footer-links">
                            @yield('footer-links')
                        </div>
                    @endif

                    @yield('footer')
                </div>
            </div>
        </td>
    </tr>
</table>
</body>
</html>
