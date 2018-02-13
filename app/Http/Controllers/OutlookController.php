<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

class OutlookController extends Controller
{
    private $user;

    public function mail()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $tokenCache = new \App\TokenStore\TokenCache;


        if (!$tokenCache->getAccessToken()) {
            flash('First you need to connect with outlook. Click connect button bellow')->warning();
            return redirect('/');
        }

        $graph = new Graph();
        $graph->setAccessToken($tokenCache->getAccessToken());

        $user = $graph->createRequest('GET', '/me')
            ->setReturnType(Model\User::class)
            ->execute();

        $messageQueryParams = [
            // Only return Subject, ReceivedDateTime, and From fields
            "\$select" => "subject,receivedDateTime,from,bodyPreview",
            // Sort by ReceivedDateTime, newest first
            "\$orderby" => "receivedDateTime DESC",
            // Return at most 10 results
            "\$top" => "20"
        ];

        $getMessagesUrl = '/me/mailfolders/inbox/messages?' . http_build_query($messageQueryParams);
        $messages = $graph->createRequest('GET', $getMessagesUrl)
            ->addHeaders(array('X-AnchorMailbox' => $user->getMail()))
            ->setReturnType(Model\Message::class)
            ->execute();

        return view('mail', [
            'user' => [
                'full_name' => $user->getDisplayName(),
                'email' => $user->getMail(),
            ],
            'messages' => $messages
        ]);
    }

    public function sendForm()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        return view('email-form');
    }

    public function sendEmail(Request $request)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $tokenCache = new \App\TokenStore\TokenCache;


        if (!$tokenCache->getAccessToken()) {
            flash('First you need to connect with outlook. Click connect button bellow')->warning();
            return redirect('/');
        }

        $sender_name = $request->input('sender_name');
        $subject = $request->input('subject');
        $email = $request->input('email');
        $to_emails = $request->input('to_emails');
        $names = $request->input('names');
        $html = $request->input('html');
        $plain = $request->input('plain');

        $array_emails = explode(",", $to_emails);
        $array_names = explode(",", $names);

//        dd(trim($array_emails[1]));

        foreach ($array_emails as $i => $to_email) {
            $message = $this->getMessage($html, $array_names[$i]);
            $this->send($sender_name, trim($email), trim($to_email), trim($subject), $message);
        }

    }

    private function getMessage($html, $name)
    {
        $search   = ["%recipient.name%"];
        $replace = [$name];
        return str_replace($search, $replace, $html);
    }

    private function send($sender_name, $sender_email, $recipient, $subject, $message)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $tokenCache = new \App\TokenStore\TokenCache;


        if (!$tokenCache->getAccessToken()) {
            flash('First you need to connect with outlook. Click connect button bellow')->warning();
            return redirect('/');
        }

        $graph = new Graph();
        $graph->setAccessToken($tokenCache->getAccessToken());

        $mailBody = array( "Message" => array(
            "subject" => $subject,
            "body" => array(
                "contentType" => "html",
                "content" => $message
            ),
            "sender" => array(
                "emailAddress" => array(
                    "name" => $sender_name,
                    "address" => $sender_email
                )
            ),
            "from" => array(
                "emailAddress" => array(
                    "name" => $sender_name,
                    "address" => $sender_email
                )
            ),
            "toRecipients" => [
                ["emailAddress" => ["address" => $recipient]] //Steve Ewing
            ]
        )
        );

//        dd($mailBody);

        $response = $graph->createRequest("POST", "/me/sendMail")
            ->attachBody($mailBody)
            ->execute();

        if($response) echo "sent<br>" ;
    }

  private function getMe()
  {
      if (session_status() == PHP_SESSION_NONE) {
          session_start();
      }

      $tokenCache = new \App\TokenStore\TokenCache;

      if(!$tokenCache->getAccessToken()) {
          exit('No token');
      }

      $graph = new Graph();
      $graph->setAccessToken($tokenCache->getAccessToken());

      $user = $graph->createRequest('GET', '/me')
          ->setReturnType(Model\User::class)
          ->execute();

      return $this->user = $user;
  }
}
