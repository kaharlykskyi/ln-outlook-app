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
          "subject" => "Paul Solimano Via Linkedin Network",
          "body" => array(
              "contentType" => "html",
              "content" => "<p><span style=\"font-size:12px\">Hello dude! How are you?</span></p>"
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
//              ["emailAddress" => ["address" => "irwins@hain-celestial.com"]], //Irwin Simon
//              ["emailAddress" => ["name" => "Franco Ieraci", "address" => "frank@linkdnetwork.com"]],
//              ["emailAddress" => ["address" => "pascal@glamhouse.com"]], //Pascal Mouawad
//              ["emailAddress" => ["address" => "sgriffin@diversifiedus.com"]], //Scott Griffin
//              ["emailAddress" => ["address" => "rgraham@bgprod.com"]], //Reggie Graham
//              ["emailAddress" => ["address" => "loup@sumitomorubber-usa.com"]], //Lou Pilie
//              ["emailAddress" => ["address" => "mdibella@hain-celestial.com"]], //Mia DiBella
//              ["emailAddress" => ["address" => "andrew.nicholas@gm.com"]], //Andrew Nicholas
//              ["emailAddress" => ["address" => "jperez@uei.com"]], //Jesus Perez
//              ["emailAddress" => ["address" => "shauntae@johnnywas.com"]], //Shauntae Cartier
//              ["emailAddress" => ["address" => "jasonm@pinnacle-exhibits.com"]], //Jason MacDonald
//              ["emailAddress" => ["address" => "james.domalski@preferredhomecare.com"]], //james domalski
//              ["emailAddress" => ["address" => "pmcelwee@phmc.org"]], //Paul McElwee
//              ["emailAddress" => ["address" => "jgerkens@mission-bbq.com"]], //Jessica Gerkens
//              ["emailAddress" => ["address" => "svanguelpen@friedmanshome.com"]], //Susan Van Guelpen
//              ["emailAddress" => ["address" => "ambereen.s@nespresso.com"]], //Ambereen Renfro Sheikh
//              ["emailAddress" => ["address" => "jacobjaber@philzcoffee.com"]], //Jacob Jaber
//              ["emailAddress" => ["address" => "bree@johnnywas.com"]], //Bree Statley
//              ["emailAddress" => ["address" => "aroberts@regentsurgicalhealth.com"]], //Anne Roberts
//              ["emailAddress" => ["address" => "annika.hagstrom@stories.com"]], //Annika Hagstrom
//              ["emailAddress" => ["address" => "cvreeland@joesjeans.com"]], //Chelsea Vreeland
//              ["emailAddress" => ["address" => "tbaca@nah.org"]], //Tiffany Baca
//              ["emailAddress" => ["address" => "lmartin@cureis.com"]], //Lori Martin
//              ["emailAddress" => ["address" => "alyssa.dealmeida@booker.com"]], //Alyssa De Almeida
//              ["emailAddress" => ["address" => "madeleine.carlsen@healogics.com"]], //Madeleine Carlsen
//              ["emailAddress" => ["address" => "bertram@onedome.global"]], //Bertram Meyer
//              ["emailAddress" => ["address" => "lracey@utzsnacks.com"]], //Larry Racey
//              ["emailAddress" => ["address" => "roy@kollaboration.org"]], //Roy Choi
//              ["emailAddress" => ["address" => "alison.gresham@birchbox.com"]], //Alison Gresham
//              ["emailAddress" => ["address" => "jrendall@nbty.com"]], //Jessica Rendall
//              ["emailAddress" => ["address" => "maria.dirmandzhyan@tadashishoji.com"]], //Maria Dirmandzhya
//              ["emailAddress" => ["address" => "thomas.howland@byrnedairy.com"]], //Thomas Howland
//              ["emailAddress" => ["address" => "bjohnson@frette.com"]], //Bailey Johnson
//              ["emailAddress" => ["address" => "troy.manke@preferredhomecare.com"]], //Troy Manke
//              ["emailAddress" => ["address" => "sebastien.dufourmantelle@autoalert.com"]], //Sebastien Dufourmantelle
//              ["emailAddress" => ["address" => "megan.adorno@rwnewyork.com"]], //Megan Adorno
//              ["emailAddress" => ["address" => "michellelord@easternnational.org"]], //Michelle A Lord
//              ["emailAddress" => ["address" => "roberts@zekecapital.com"]], //Joseph Roberts
//              ["emailAddress" => ["address" => "stefanr@insomniacookies.com"]], //Stefan Ragland
//              ["emailAddress" => ["address" => "michael.scarpellini@shophappiness.com"]], //Michael Scarpellini
//              ["emailAddress" => ["address" => "mmcdonough@advantage.com"]], //Michelle McDonough
//              ["emailAddress" => ["address" => "mschwartz@nutrisystem.com"]], //Michele Schwartz
//              ["emailAddress" => ["address" => "laura.ali@starkist.com"]], //Laura Molseed Ali
//              ["emailAddress" => ["address" => "lauribalbinot@texasdebrazil.com"]], //Lauri Balbinot
//              ["emailAddress" => ["address" => "jennie.stolmayer@coloniallife.com"]], //Jennie Stolmayer
//              ["emailAddress" => ["address" => "ktomann@amscan.com"]], //AREN TOMANN
//              ["emailAddress" => ["address" => "grafi@premierfixtures.com"]], //Rafi Goodman
//              ["emailAddress" => ["address" => "jake.shingleton@travelinc.com"]], //Jake Shingleton
//              ["emailAddress" => ["address" => "dtanksley@walser.com"]] //Derek Tanksley
//              ["emailAddress" => ["address" => "holly.johnston@herrs.com"]] //Holly Johnston
              ["emailAddress" => ["address" => "mishakagar@gmail.com"]] //Steve Ewing
          ]
      )
      );

      $response = $graph->createRequest("POST", "/me/sendMail")
          ->attachBody($mailBody)
          ->execute();

      echo "<pre>"; print_r($response);
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
