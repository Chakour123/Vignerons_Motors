<?php
include("config/auth.php");
include("include/header.php");
include("include/menu.php");

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    $to = "chacourdeen@gmail.com";
    $email_subject = "Nouveau message de contact: $subject";
    
    $email_body = "Vous avez reçu un nouveau message depuis le formulaire de contact de Vignerons Motors.\n\n".
                  "Détails :\n".
                  "Nom: $nom $prenom\n".
                  "Email: $email\n".
                  "Téléphone: $phone\n\n".
                  "Message :\n$message";

    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    if (@mail($to, $email_subject, $email_body, $headers)) {
        $success = "Votre message a été envoyé avec succès !";
    } else {
        // Since mail() often fails on local development without SMTP, we might simulate success or give a clear error.
        $error = "Une erreur est survenue lors de l'envoi du message. Veuillez réessayer plus tard.";
        
        // Debug note for the user if they're on local MAMP
        if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) {
             $error .= " (Note: L'envoi de mail peut nécessiter une configuration SMTP sur un serveur local comme MAMP).";
        }
    }
}
?>

    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Contacts</h1>
    </div>

    <div class="container-fluid contact py-5">
        <div class="container py-5">
            <div class="p-5 bg-light rounded">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 900px;">
                            <h4 class="text-primary border-bottom border-primary border-2 d-inline-block pb-2">Contactez nous</h4>
                            <p class="mb-5 fs-5 text-dark">Une préoccupation ? Laissez nous un message !
                            </p>
                        </div>
                    </div>
                    <?php if ($success): ?>
                        <div class="col-12">
                            <div class="alert alert-success wow fadeInUp" data-wow-delay="0.1s"><?php echo $success; ?></div>
                        </div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="col-12">
                            <div class="alert alert-danger wow fadeInUp" data-wow-delay="0.1s"><?php echo $error; ?></div>
                        </div>
                    <?php endif; ?>
                    <div class="col-lg-7">
                        <h5 class="text-primary wow fadeInUp" data-wow-delay="0.1s">Que peut-on faire ?</h5>
                        <h1 class="display-5 mb-4 wow fadeInUp" data-wow-delay="0.3s">Envoyez Votre Message</h1>
                        <p class="mb-4 wow fadeInUp" data-wow-delay="0.5s">Vous avez un problème ou n'importe quelle autre préoccupation, n'hésitez pas à nous envoyer un message à travers le formulaire ci-dessous.</p>
                        <form action="contact.php" method="POST">
                            <div class="row g-4 wow fadeInUp" data-wow-delay="0.1s">
                                <div class="col-lg-12 col-xl-6">
                                    <div class="form-floating">
                                        <input type="text" name="nom" class="form-control" id="name" placeholder="Your Name" required>
                                        <label for="name">votre nom</label>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-6">
                                    <div class="form-floating">
                                        <input type="text" name="prenom" class="form-control" id="project" placeholder="Project" required>
                                        <label for="project">votre prénom</label>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-6">
                                    <div class="form-floating">
                                        <input type="email" name="email" class="form-control" id="email" placeholder="Your Email" required>
                                        <label for="email">votre email</label>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-6">
                                    <div class="form-floating">
                                        <input type="text" name="phone" class="form-control" id="phone" placeholder="Phone">
                                        <label for="phone">votre numéro</label>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" name="subject" class="form-control" id="subject" placeholder="Subject" required>
                                        <label for="subject">Objet</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea name="message" class="form-control" placeholder="Leave a message here" id="message"
                                            style="height: 160px" required></textarea>
                                        <label for="message">Message</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100 py-3 text-white">Envoyer</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-5 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="h-100 rounded">
                            <iframe class="rounded w-100" style="height: 100%;"
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5251.495875252706!2d2.4286019755167216!3d48.843946471330234!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e6729785ba6e51%3A0x2495ac8fbd2f8b52!2s22%20Rue%20des%20Vignerons%2C%2094300%20Vincennes!5e0!3m2!1sfr!2sfr!4v1771319360408!5m2!1sfr!2sfr" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
include("include/footer.php");
?>
