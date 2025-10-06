<?php 

   // mostra TODOS os erros do php
   ini_set ( 'display_errors' , 1); 
   error_reporting (E_ALL);

   //// PARA ENVIO DE EMAILS PHPMAILER //////
   require __DIR__ . '/vendor/autoload.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    

   //////////////////////////////////////////

   // inicia a sessao    
   session_start();
   
  //////////////////////////////////////////////////////////////// 
  // Envio de emails
  // Marcelo C Peres 2023
  /* Exemplo: 
     if ( EnviaEmail ('fulano@fulano','Feliz Aniversario',
                      '<html><body>Feliz niver</body></html>') 
     {
      echo 'enviado com sucesso';
     }
  */   
     
  ////////////////////////////////////////////////////////////////
  function EnviaEmail ( $pEmailDestino, $pAssunto, $pHtml, 
                        $pUsuario = "ecommerce@efesonet.com", 
                        $pSenha = "u!G8mDRr6PBXkH6", 
                        $pSMTP = "smtp.efesonet.com" )  {    
   try {
 
     //cria instancia de phpmailer
     echo "<br>Tentando enviar para $pEmailDestino...";
     $mail = new PHPMailer(true);
     $mail->IsSMTP();  // diz ao php que o servidor eh SMTP
  
     // servidor smtp
     $mail->Host = $pSMTP;  // configura o servidor
     $mail->SMTPAuth = true;      // requer autenticacao com o servidor                         
     
     $mail->SMTPSecure = 'tls';  // nivel de seguranca                           
     $mail-> SMTPOptions = array ( 'ssl' => array ( 'verificar_peer' => false, 'verify_peer_name' => false,
       'allow_self_signed' => true ) );
      
     $mail->Port = 587;  // porta do servi�o no servidor     
      
     $mail->Username = $pUsuario; 
     $mail->Password = $pSenha; 
     $mail->From = $pUsuario; 
     $mail->FromName = "Recuperacao de senhas"; 
  
     $mail->AddAddress($pEmailDestino, "Usuario"); 
     $mail->IsHTML(true); // o conteudo enviado eh html (poderia ser txt comum sem formato)
     $mail->Subject = $pAssunto; 
     $mail->Body = $pHtml;
     $enviado = $mail->Send(); // disparo
       
     if (!$enviado) {
        echo "<br>Erro: " . $mail->ErrorInfo;
     } 
     
     return $enviado;


        } catch (Exception $e) {
            echo "PHPMailer erro: " . $e->getMessage();
        } catch (\Exception $e) {
            echo "Erro geral: " . $e->getMessage();
        }
    }


?>