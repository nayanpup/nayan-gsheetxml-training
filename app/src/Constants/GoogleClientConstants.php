<?php

declare(strict_types=1);

namespace App\Constants;

class GoogleClientConstants
{
    const GS_TYPE = 'service_account';
    const GS_AUTH_URI = 'https://accounts.google.com/o/oauth2/auth';
    const GS_TOKEN_URI = 'https://oauth2.googleapis.com/token';
    const GS_AUTH_CERT_URL = 'https://www.googleapis.com/oauth2/v1/certs';
}