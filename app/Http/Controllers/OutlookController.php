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


    if(!$tokenCache->getAccessToken()) {
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

    $getMessagesUrl = '/me/mailfolders/inbox/messages?'.http_build_query($messageQueryParams);
    $messages = $graph->createRequest('GET', $getMessagesUrl)
                      ->addHeaders(array ('X-AnchorMailbox' => $user->getMail()))
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

  public function sendEmail()
  {
      if (session_status() == PHP_SESSION_NONE) {
          session_start();
      }

      $tokenCache = new \App\TokenStore\TokenCache;


      if(!$tokenCache->getAccessToken()) {
          flash('First you need to connect with outlook. Click connect button bellow')->warning();
          return redirect('/');
      }

      $graph = new Graph();
      $graph->setAccessToken($tokenCache->getAccessToken());

      $user = $this->getMe();

      $mailBody = array( "Message" => array(
          "subject" => "Sending email from MS Outlook API",
          "body" => array(
              "contentType" => "html",
              "content" => "<p>Hello, my dear friend. How are you? <br>This email was sent from {$user->getMail()} using Outlook API</p>"
          ),
          "sender" => array(
              "emailAddress" => array(
                  "name" => $user->getDisplayName(),
                  "address" => $user->getMail()
              )
          ),
          "from" => array(
              "emailAddress" => array(
                  "name" => $user->getDisplayName(),
                  "address" => $user->getMail()
              )
          ),
          "toRecipients" => [
              [
                  "emailAddress" => [
                      "name" => "David Nights",
                      "address" => "david_nights@outlook.com"
                  ]
              ],
              [
                  "emailAddress" => [
                      "name" => "Franco Ieraci",
                      "address" => "Francoieraci00@gmail.com"
                  ]
              ]
          ]
      )
      );

      $response = $graph->createRequest("POST", "/me/sendMail")
          ->attachBody($mailBody)
          ->execute();

      print_r($response);
  }

  private function getMe()
  {
      if (session_status() == PHP_SESSION_NONE) {
          session_start();
      }

      $tokenCache = new \App\TokenStore\TokenCache;

      if(!$tokenCache->getAccessToken()) {
          flash('First you need to connect with outlook. Click connect button bellow')->warning();
          return redirect('/');
      }

      $graph = new Graph();
      $graph->setAccessToken($tokenCache->getAccessToken());

      $user = $graph->createRequest('GET', '/me')
          ->setReturnType(Model\User::class)
          ->execute();

      return $this->user = $user;
  }
}
