<?php 
function checkAdmin($token) {
    $tokensPath = __DIR__ . '/../data/admin_tokens.json';
    $validTokens = json_decode(file_get_contents($tokensPath), true);

    foreach ($validTokens as $validToken) {
        if (password_verify($token, $validToken)) {
            return [
                "success" => true,
                "http_code" => 200,
                "message" => "Token valide."
            ];
        }
    }

    return [
        "success"=> false,
        "http_code" => 401,
        "message"=> "Token invalide ou manquant.",
        "token" => $token
    ];
}

function checkToken($token) {
    $tokensPath = __DIR__ . '/../data/tokens.json';
    $validTokens = json_decode(file_get_contents($tokensPath), true);

    foreach ($validTokens as $validToken) {
        if (password_verify($validToken, $token)) {
            return [
                "success" => true,
                "http_code" => 200,
                "message" => "Token valide.",
                "token" => $validToken
            ];
        }
    }

    return [
        "success" => false,
        "http_code" => 402,
        "message" => "Token invalide ou manquant."
    ];
}
?>
