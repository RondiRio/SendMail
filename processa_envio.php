<?php
    require 'bibliotecas\PHPMailer\Exception.php';
    require 'bibliotecas\PHPMailer\PHPMailer.php';
    require 'bibliotecas\PHPMailer\SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    class Mensagem{
        private $para = null;
        private $assunto = null;
        private $mensagem = null;
        public function __get($atributo){
            return $this->$atributo;
        }
        public function __set($atributo, $valor){
            return $this->$atributo = $valor;
        }
        public function mensagemValida(){
            if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)){
                return false;
            }else{
                return true;
            }   
        }
    }
    $mensagem = new Mensagem();
    $mensagem->__set('para', $_POST['para']);
    $mensagem->__set('assunto', $_POST['assunto']);
    $mensagem->__set('mensagem', $_POST['mensagem']);
    if(!$mensagem->mensagemValida()){
        echo 'parâmetros inválidos';
        die();        
    }
    if(preg_match('/@(hotmail|gmail|yahoo|outlook|icloud)/', $mensagem->para))
    {
            $mail = new PHPMailer(true);
            try 
            {
                // configurando server SMTP
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'seuemail';                     //SMTP username -- Não achou que eu ia deixar o meu né? kkk
                $mail->Password   = 'sua_senha';                               //SMTP password -- Não achou que eu ia deixar a minha né? kkk
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                //Recipients
                $mail->setFrom('rondi.rio@gmail.com', 'email');
                $mail->addAddress($mensagem->__get('para'));     //Add a recipient
                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = $mensagem->__get('assunto');
                $mail->Body    = $mensagem->__get('mensagem');
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                $mail->send();
                echo 'Message has been sent';
            } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
    }
?>
