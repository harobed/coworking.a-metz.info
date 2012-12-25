<?php

function mail_utf8($to, $from_user, $from_email, $subject = '(No subject)', $message = '')
{
    $from_user = "=?UTF-8?B?".base64_encode($from_user)."?=";
    $subject = "=?UTF-8?B?".base64_encode($subject)."?=";

    $headers = "From: $from_user <$from_email>\r\n".
    "MIME-Version: 1.0" . "\r\n" .
    "Content-type: text/txt; charset=UTF-8" . "\r\n";

    return mail($to, $subject, $message, $headers);
}

//print(var_export($_POST, true));
mail_utf8(
    'contact@stephane-klein.info',
    'contact@stephane-klein.info',
    'contact@stephane-klein.info',
    '[coworking.a-metz.info] Réponse au sondage',
    var_export($_POST, true)
);

print('{"result": "ok" }');

?>
