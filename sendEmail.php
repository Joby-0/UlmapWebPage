<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = htmlspecialchars(trim($_POST["name"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    $to = "ulrika.maars@ulmap.se";
    $subject = "Nytt meddelande från hemsidan";

    $body = "
Namn: $name

E-post: $email

Meddelande:
$message
";

    $headers = "From: ulrika.maars@ulmap.se\r\n";
    $headers .= "Reply-To: $email\r\n";

    if (mail($to, $subject, $body, $headers)) {
        header("Location: tack-for-meddelandet");
        exit;
    } else {
        echo "Fel vid skickning av meddelandet.";
    }
}
?>
