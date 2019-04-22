<?php
    require 'PHPMailer/src/PHPMailer.php';
    if(isset($_GET['mail'])){
        $mail = new PHPMailer();
        $mail->isSMTP();   
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );                               // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                      // Enable SMTP authentication
        $mail->Username = 'oliva.raul12@gmail.com';  // SMTP username
        $mail->Password = 'rauloliva12';               // SMTP password
        $mail->From = 'oliva.raul12@gmail.com';
        $mail->FromName = 'Gerente del Restaurante';
        $mail->addAddress($_GET['correo'], $_GET['nom_cliente']);     // Add a recipient
        $mail->SMTPDebug = 0;
        $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Here is a copy of your recent ticket';
        $url_image = "/Restaurante_website/Imagenes/logo_mail.png";
        $obj = json_decode($_GET['data'],false);
        $body = "<table><thead><tr><th>Platillos</th><th>Cantidades</th><th>Precios</th></tr></thead>";
        for ($i=0; $i < count($obj); $i++) { 
            $body .= "<tr><td style='text-align:center'>".$obj[$i]->platillo."</td>";
            $body .= "<td style='text-align:center'>".$obj[$i]->cantidad."</td>";
            $body .= "<td style='text-align:center'>".$obj[$i]->precio."</td></tr>";
        }
        $body .= "</table><br><h3>Total: ".$_GET['total']."</h3><img src='data:image;base64,".$url_image."'>";
        $mail->Body = $body;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        $arr[] = array("msg" => !$mail->send() ? "Issue" : "Done");
        echo json_encode($arr);
    }
?>