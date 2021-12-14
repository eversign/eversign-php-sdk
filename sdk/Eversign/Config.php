<?php

namespace Eversign;

class Config {

    const API_URL = "https://api.eversign.com/api/";
    const OAUTH_URL = "https://eversign.com/oauth/";

    const BUSINESS_URL = "business";
    const DOCUMENT_URL = "document";
    const DOCUMENT_FINAL_URL = "download_final_document";
    const DOCUMENT_RAW_URL = "download_raw_document";

    const FILE_URL = "file";
    const REMINDER_URL = "send_reminder";

    const DEBUG_MODE = false;

    const GUZZLE_TIMEOUT = 30;

    public static $AVAILABLE_LANGUAGES = [
        'en',
        'da',
        'nl',
        'fr',
        'de',
        'hi',
        'it',
        'pl',
        'ru',
        'es',
        'se',
        'tr',
        'pt',
    ];

}
