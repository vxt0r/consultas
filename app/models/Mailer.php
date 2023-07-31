<?php

namespace app\models;

require __DIR__.'/../Config.php';

use Exception;
use stdClass;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer{

    /** 
    * @var PHPMailer 
    */
    private $mail;

    /** 
    * @var stdClass 
    */
    private $data;

    /** 
    * @var Exception 
    */
    private $error;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        $this->data = new stdClass();

        $this->mail->isSMTP();
        $this->mail->isHTML();
        $this->mail->setLanguage('br');

        $this->mail->SMTPAuth = true;
        $this->mail->SMTPSecure = 'tls';
        $this->mail->CharSet = 'utf-8';

        $this->mail->Host = MAIL['host'];
        $this->mail->Port = MAIL['port'];
        $this->mail->Username = MAIL['user'];
        $this->mail->Password = MAIL['password'];
    }

    /**
     * @param string
     * @param string
     * @param string
     * @param string
     * @return Mailer
     */
    private function add($subject,$body,$recipient_name,$recipient_email)
    {
        $this->data->subject = $subject;
        $this->data->body = $body;
        $this->data->recipient_name = $recipient_name;
        $this->data->recipient_email = $recipient_email;

        return $this;
    }

    /**
     * @param string
     * @param string
     * @return Mailer
     */
    private function attach($file_path,$file_name)
    {
        $this->data->attach[$file_path] = $file_name;
        return $this;
    }

    /**
     * @param string
     * @param string
     * @return bool
     */
    private function send($from_name = MAIL['from_name'] ,$from_email = MAIL['from_email']):bool
    {
        try {

            $this->mail->Subject = $this->data->subject;
            $this->mail->msgHTML($this->data->body);
            $this->mail->addAddress($this->data->recipient_email,$this->data->recipient_name);
            $this->mail->setFrom($from_email,$from_name);

            if(!empty($this->data->attach)){
                foreach($this->data->attach as $path=>$name){
                    $this->mail->addAttachment($path,$name);
                }
            }

            $this->mail->send();
            return true;

        } catch (Exception $e) {
            $this->error = $e;
            return false;
        }
    }

    /**
     * @return ?Exception 
     */
    public function error()
    {
        return $this->error;
    }

    /**
     * @param string
     * @param array
     * @param string
     * @return void
     */
    public function sendConfirmEmail($email,$datetime,$name)
    {
        $this->add(
            'Confirmação de consulta',
            '<p>
                Bom dia, '.$name.'. Lembre-se de sua consulta do dia '.$datetime[0].
                ' às '.$datetime[1].'. Estamos te aguardando.
            </p>',
            $name,$email
        )->send();

        if($this->error()){
            var_dump($email->error()->getMessage());
        }
    }

    /**
     * @param string
     * @param string
     * @return void
     */
    public function sendCancelEmail($email,$name)
    {
        $this->add(
            'Cancelamento de consulta',
            '<p>Lamentamos informar que sua consulta foi desmarcada. Favor entrar em contato para possível reagendamento</p>',
            $name,$email
        )->send();

        if($this->error()){
            var_dump($email->error()->getMessage());
        }
    }
}
