<?php
// oauth_config.php
require_once('topmain.php');
// === CONFIG ===
//
//const GOOGLE_CLIENT_ID = '458875996217-mlha8j2lkddsa2dau95huk79cio016cd.apps.googleusercontent.com';
//const GOOGLE_CLIENT_SECRET = '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';
//const GOOGLE_REDIRECT_URI = 'https://www.hajatha.ir/verify-token.php'; // must match console
//define('GOOGLE_REDIRECT_URI', $sitenamelink . "/verify-token.php");

define('GOOGLE_CLIENT_ID', $row_setting['google_client_id']);
define('GOOGLE_CLIENT_SECRET', $row_setting['google_client_secret']);
define('GOOGLE_REDIRECT_URI', $sitenamelink . "/register-gmail/");
const GOOGLE_AUTH_ENDPOINT = 'https://accounts.google.com/o/oauth2/v2/auth';
const GOOGLE_TOKEN_ENDPOINT = 'https://oauth2.googleapis.com/token';
const GOOGLE_TOKENINFO_ENDPOINT = 'https://oauth2.googleapis.com/tokeninfo'; // for ID token verification (simple)
// === Helpers ===

// Generate a cryptographically secure random string
function random_string($length = 64) {
    return rtrim(strtr(base64_encode(random_bytes($length)), '+/', '-_'), '=');
}

// Base64url-encode
function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

// Create a PKCE code verifier (store in session)
function create_pkce_verifier() {
    $verifier = base64url_encode(random_bytes(32));
    $_SESSION['pkce_code_verifier'] = $verifier;
    return $verifier;
}

// Create code challenge from verifier (SHA256 -> base64url)
function pkce_code_challenge($verifier) {
    return base64url_encode(hash('sha256', $verifier, true));
}

// Build Google Auth URL
function build_auth_url() {
    // Create and store state for CSRF protection
    $state = bin2hex(random_bytes(16));
    $_SESSION['oauth2_state'] = $state;

    // PKCE
    $verifier = create_pkce_verifier();
    $challenge = pkce_code_challenge($verifier);

    $params = [
        'response_type' => 'code',
        'client_id' => GOOGLE_CLIENT_ID,
        'redirect_uri' => GOOGLE_REDIRECT_URI,
        'scope' => 'openid profile email',
        'state' => $state,
        'code_challenge' => $challenge,
        'code_challenge_method' => 'S256',
        'access_type' => 'offline', // if you want refresh_token
        'prompt' => 'select_account' // optional
    ];

    return GOOGLE_AUTH_ENDPOINT . '?' . http_build_query($params);
}

// Perform POST to token endpoint (exchange code)
function http_post($url, $postFields) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    // In production verify SSL (default true). If dev, don't disable.
    $resp = curl_exec($ch);
    if ($resp === false) {
        throw new Exception('cURL error: ' . curl_error($ch));
    }
    $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['status' => $status, 'body' => $resp];
}

// Verify ID token using Google's tokeninfo endpoint (simple approach)
// Alternative: verify JWT signature locally against Google's certs (more complex)

function verify_id_token($id_token) {
    // tokeninfo accepts id_token query param
    $url = GOOGLE_TOKENINFO_ENDPOINT . '?id_token=' . urlencode($id_token);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $resp = curl_exec($ch);
    if ($resp === false) {
        return false;
    }
    $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($status !== 200) return false;
    $data = json_decode($resp, true);
    // Basic checks
    if (!isset($data['aud']) || $data['aud'] !== GOOGLE_CLIENT_ID) return false;
    // You can check expiry (exp) too
    if (isset($data['exp']) && time() > (int)$data['exp']) return false;
    return $data; // contains sub, email, email_verified, name, picture, etc.
}