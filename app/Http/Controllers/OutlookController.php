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
              "content" => "<p><span style=\"font-size:12px\">Hi %recipient.name%</span></p>

<p><span style=\"font-size:12px\">I came across your Linkedin profile and wanted to get in touch regarding an opportunity that will interest you.</span></p>

<p><span style=\"font-size:12px\">No one knows better than you how time consuming - and expensive - it&rsquo;s become to stay ahead of your competitors. Keeping pace with ever changing consumer attitudes can mean the difference between profit and struggling just to stay relevant. The difference maker?</span></p>

<p><span style=\"font-size:12px\">Big data.</span></p>

<p><span style=\"font-size:12px\">As the largest trend-spotting platform in the world, we&rsquo;ve cracked the &ldquo;on-demand research&rdquo; code. Our suite of research, innovation and training tools strips away 95% of the time and cost of using customized research to stay in tune with your customers. For you, that means affordable, fingertip access to key information that gives you the key insights you need for consistent customer engagement and competitor domination.</span></p>

<p><span style=\"font-size:12px\">A brief chat on the phone is all it would take to understand your re-search challenges and identify potential ways we can make a difference just like we&rsquo;ve done with Disney, Nestle, Red Bull and NASA. Please let me know a date and time that works and I can send out a calendar invitation.</span></p>

<p><span style=\"font-size:12px\">Looking forward to our conversation.</span></p>

<p><span style=\"font-size:12px\">Regards,</span></p>

<p><span style=\"font-size:12px\">Paul Solimano<br />
Business Innovation Expert<br />
Trend Hunter - #1 in trends &amp; custom research</span></p>"
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
/*jason_hornwood@outlook.com

melisa_ginger@outlook.com

charlie_gonzalez1@outlook.com

john_fridlend@outlook.com

sandeep_kumar_rajeet@outlook.com*/
          "toRecipients" => [
              [
                  "emailAddress" => [
                      "name" => "Jeffree Williams",
                      "address" => "jeffree_williams@outlook.com"
                  ]
              ],
              [
                  "emailAddress" => [
                      "name" => "Franco Ieraci",
                      "address" => "Francoieraci00@gmail.com"
                  ]
              ],
              [
                  "emailAddress" => [
                      "name" => "justin_jenets",
                      "address" => "justin_jenets@outlook.com"
                  ]
              ],
              [
                  "emailAddress" => [
                      "name" => "samuel_ebony",
                      "address" => "samuel_ebony@outlook.com"
                  ]
              ],
              [
                  "emailAddress" => [
                      "name" => "ramona_bruno",
                      "address" => "ramona_bruno@outlook.com"
                  ]
              ],
              [
                  "emailAddress" => [
                      "name" => "anitta_parker",
                      "address" => "anitta_parker@outlook.com"
                  ]
              ],
              [
                  "emailAddress" => [
                      "name" => "Dmax_rushcoff",
                      "address" => "max_rushcoff@outlook.com"
                  ]
              ],
              [
                  "emailAddress" => [
                      "name" => "alisa_rosa",
                      "address" => "alisa_rosa@outlook.com"
                  ]
              ],
              [
                  "emailAddress" => [
                      "name" => "lisabella_martinez",
                      "address" => "lisabella_martinez@outlook.com"
                  ]
              ],
              [
                  "emailAddress" => [
                      "name" => "laura_manny",
                      "address" => "laura_manny@outlook.com"
                  ]
              ],
              [
                  "emailAddress" => [
                      "name" => "nina_zucchero",
                      "address" => "nina_zucchero@outlook.com"
                  ]
              ],
              [
                  "emailAddress" => [
                      "name" => "erika_matts",
                      "address" => "erika_matts@outlook.com"
                  ]
              ],
              [
                  "emailAddress" => [
                      "name" => "bschjoohfsaalln45",
                      "address" => "bschjoohfsaalln45@gmail.com"
                  ]
              ],
              [
                  "emailAddress" => [
                      "name" => "comebackcl911",
                      "address" => "comebackcl911@gmail.com"
                  ]
              ],
              [
                  "emailAddress" => [
                      "name" => "misha",
                      "address" => "mishakagar@gmail.com"
                  ]
              ]
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
