<?php

namespace Conversio\PhpMailerAdapter;

use Conversio\Mail\Mail;

use Conversio\Mail\Mailer\MailerInterface;
use PHPMailer;

/**
 * Class PhpMailerAdapter
 * @package Conversio\PhpMailerAdapter
 */
class PhpMailerAdapter implements MailerInterface
{
    /**
     * @var PHPMailer
     */
    private $phpMailer;

    /**
     * PhpMailerAdapter constructor.
     *
     * @param PHPMailer $mailer
     */
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