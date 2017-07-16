<?php

namespace Conversio\PhpMailerAdapter\Tests;

use Conversio\Mail\Address\Address;
use Conversio\Mail\Mail;
use Conversio\Mail\Mailer\Mailer;
use Conversio\PhpMailerAdapter\PhpMailerAdapter;
use PHPUnit\Framework\TestCase;
use PHPMailer;

/**
 * Class PhpMailerAdapterTest
 * @package Conversio\PhpMailerAdapter\Tests
 */
class PhpMailerAdapterTest extends TestCase
{
    /**
     * @return PHPMailer
     */
    private function getPhpMailer()
    {
        $mailer = new PHPMailer();
        $mailer->isSMTP();
        $mailer->Host       = '';
        $mailer->SMTPAuth   = true;
        $mailer->Username   = '';
        $mailer->Password   = '';
        $mailer->SMTPSecure = 'tls';
        $mailer->Port       = 587;

        return $mailer;
    }

    /**
     * @return Mail
     */
    private function getMail()
    {
        $mail = new Mail(new Address('Testmail@test.de', 'John Doe'));
        $mail->setSubject('This is the Subject');
        $mail->content()->setHtml('<b>This is the HTML-Body</b>');
        $mail->content()->setText('This is the Text-Body');

        return $mail;
    }

    public function testSend()
    {
        $adapter = new PhpMailerAdapter($this->getPhpMailer());
        $mail    = $this->getMail();

        $mailer = new Mailer($adapter);
        $this->assertTrue($mailer->send($mail));
    }
}