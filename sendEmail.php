<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $subject = trim($_POST["subject"] ?? "");
    $message = trim($_POST["message"] ?? "");

    $errors = [];

    if ($name === "" || strlen($name) < 2) {
        $errors[] = "Namn saknas eller är för kort.";
    }

    if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Ogiltig eller saknad e-postadress.";
    }

    if ($subject === "") {
        $errors[] = "Du måste välja ett område.";
    }

    if ($message === "" || strlen($message) < 10) {
        $errors[] = "Meddelandet måste vara minst 10 tecken.";
    }

    if (!empty($errors)) {
        http_response_code(400);
        echo implode("<br>", $errors);
        exit;
    }

    $to = "ulrika.maars@ulmap.se";
    $mailSubject = "Nytt meddelande från hemsidan";

    $body =
        "Namn: " . $name . "\n\n" .
        "E-post: " . $email . "\n" .
        "Telefon: " . $phone . "\n\n" .
        "Ämne: " . $subject . "\n\n" .
        "Meddelande:\n" . $message . "\n";

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "Content-Transfer-Encoding: 8bit\r\n";
    $headers .= "From: ulrika.maars@ulmap.se\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";

    if (mail($to, $mailSubject, $body, $headers)) {
        header("Location: tack-for-meddelandet");
        exit;
    } else {
        http_response_code(500);
        echo "Fel vid skickning av meddelandet.";
    }
}
?>
