<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply from Wet Water Resort</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .email-container {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #572906;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #572906;
            margin-bottom: 10px;
        }
        .tagline {
            color: #666;
            font-size: 14px;
        }
        .greeting {
            font-size: 18px;
            color: #572906;
            margin-bottom: 20px;
        }
        .original-message {
            background-color: #f8f9fa;
            border-left: 4px solid #572906;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .original-message h4 {
            margin: 0 0 10px 0;
            color: #572906;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .reply-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            margin: 20px 0;
        }
        .signature {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .contact-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .contact-info h4 {
            color: #572906;
            margin: 0 0 15px 0;
        }
        .contact-item {
            margin: 8px 0;
            font-size: 14px;
        }
        .contact-item strong {
            color: #572906;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #666;
        }
        .cta-button {
            display: inline-block;
            background-color: #572906;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            margin: 15px 0;
            font-weight: bold;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            color: #572906;
            text-decoration: none;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">Wet Water Resort</div>
            <div class="tagline">Your Dream Wedding Destination</div>
        </div>

        <div class="greeting">
            Dear {{ $customerName }},
        </div>

        <p>Thank you for contacting Wet Water Resort! We're delighted to respond to your inquiry and help make your special day unforgettable.</p>

        @if($originalSubject)
        <div class="original-message">
            <h4>Your Original Message:</h4>
            <strong>Subject:</strong> {{ $originalSubject }}
        </div>
        @endif

        <div class="reply-content">
            <h4 style="color: #572906; margin-bottom: 15px;">Our Response:</h4>
            {!! nl2br(e($replyContent)) !!}
        </div>

        <div class="signature">
            <p>Best regards,<br>
            <strong>{{ $managerName }}</strong><br>
            <em>Wedding Coordinator</em><br>
            Wet Water Resort</p>
        </div>

        <div class="contact-info">
            <h4>Contact Information</h4>
            <div class="contact-item">
                <strong>Phone:</strong> 0332 226 886 (24/7 Front Desk)
            </div>
            <div class="contact-item">
                <strong>Email:</strong> info@wetwaterresort.com
            </div>
            <div class="contact-item">
                <strong>Address:</strong> No- 136/D, "Lumbini Uyana", Ja Ela-Ekala-Gampaha-Yakkala Hwy, Gampaha
            </div>
            <div class="contact-item">
                <strong>Office Hours:</strong> Mon-Fri: 9am-6pm, Sat: 10am-4pm
            </div>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('booking') }}" class="cta-button">Book Your Wedding</a>
        </div>

        <div style="text-align: center;">
            <p><strong>Ready to visit us?</strong></p>
            <p>Schedule a venue tour to see our beautiful facilities and discuss your wedding plans in person.</p>
        </div>

        <div class="social-links" style="text-align: center;">
            <p>Follow us on social media for wedding inspiration:</p>
            <a href="#">Facebook</a> |
            <a href="#">Instagram</a> |
            <a href="#">YouTube</a>
        </div>

        <div class="footer">
            <p>This email was sent in response to your inquiry (Message ID: #{{ $messageId }})</p>
            <p>If you have any questions, please don't hesitate to contact us.</p>
            <p>&copy; {{ date('Y') }} Wet Water Resort. All rights reserved.</p>
        </div>
    </div>
</body>
</html>