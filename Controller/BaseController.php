<?php

namespace Controller;

class BaseController
{
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    protected function getTwig()
    {
        return $this->twig;
    }

    protected function renderView($view, $data = [])
    {
        $template = $this->getTwig()->load($view);
        return $template->render($data);
    }

    protected function redirect($route)
    {
        header('Location: ?action=' . $route);
        exit(0);
    }

    protected function sendMail($to, $object, $content, $altContent = null)
    {
        /*global $privateConfig;
        $mail_config = $privateConfig['mail_config'];*/

        $mail = new \PHPMailer;
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'tls://smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'tritusfundation@gmail.com';                 // SMTP username
        $mail->Password = 'password9154';                           // SMTP password
        $mail->SMTPSecure = 'tls';                           // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;
        $mail->setFrom('tritusfundation@gmail.com', 'Tritus-no-replay');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $mail->Subject = $object;
        $mail->Body = $content;
        if ($altContent === null) {
            $altContent = $content;
        }
        $mail->AltBody = $altContent;


        if (!$mail->send()) {    //for debugging!
            //echo 'Message could not be sent.';
            //echo 'Mailer Error: ' . $mail->ErrorInfo;
        }

        //var_dump($mail);
    }
    protected function sendMailBis($object, $content, $altContent = null)
    {
        /*global $privateConfig;
        $mail_config = $privateConfig['mail_config'];*/

        $mail = new \PHPMailer;
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'tls://smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'chesow94@gmail.com';                 // SMTP username
        $mail->Password = 'password9154';                           // SMTP password
        $mail->SMTPSecure = 'tls';                           // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;
        $mail->setFrom("chesow94@gmail.com", "Devenir partenaire");
        $mail->addAddress("tritusfundation@gmail.com");
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $mail->Subject = $object;
        $mail->Body = $content;
        if ($altContent === null) {
            $altContent = $content;
        }
        $mail->AltBody = $altContent;


        if (!$mail->send()) {    //for debugging!
            //echo 'Message could not be sent.';
            //echo 'Mailer Error: ' . $mail->ErrorInfo;
        }

        //var_dump($mail);
    }

}