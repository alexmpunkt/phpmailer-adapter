<?php

/**
 * Created by PhpStorm.
 * User: alex
 * Date: 21.12.16
 * Time: 20:12
 */
namespace Conversio\PhpMailerAdapter;

use Conversio\Mail\Mail;
use Conversio\Mail\Mailer\Adapter\MailerAdapterInterface;

use \PHPMailer;

class PhpMailerAdapter implements MailerAdapterInterface
{
    /**
     * @var PHPMailer
     */
    private $phpMailer;

    public function __construct(PHPMailer $mailer)
    {
        $this->phpMailer = $mailer;
    }

    /**
     * @param Mail $mail
     *
     * @return bool
     */
    public function send(Mail $mail): bool
    {
        $this->phpMailer->setFrom($mail->sender()->getAddress(), $mail->sender()->getName());
        foreach ($mail->recipients()->asArray() as $item) {
            $this->phpMailer->addAddress($item->getAddress(), $item->getName());
        }
        foreach ($mail->ccs()->asArray() as $cc) {
            $this->phpMailer->addCC($cc->getAddress(), $cc->getName());
        }
        foreach ($mail->bccs()->asArray() as $bcc) {
            $this->phpMailer->addBCC($bcc->getAddress(), $bcc->getName());
        }
        //ToDo ReplyTos
        foreach ($mail->attachments()->asArray() as $attachment) {
            $this->phpMailer->addStringAttachment($attachment->getContent(), $attachment->getFullname());
        }
        $this->phpMailer->Subject = $mail->getSubject();
        $this->phpMailer->Body    = $mail->content()->getHtml();
        $this->phpMailer->AltBody = $mail->content()->getText();
        if (!$this->phpMailer->send()) {
            print_r($this->phpMailer->ErrorInfo);
        }

        return $this->phpMailer->send();
    }

    /**
     * @return string
     */
    public function getErrorInfo(): string
    {
        return $this->phpMailer->ErrorInfo;
    }

}