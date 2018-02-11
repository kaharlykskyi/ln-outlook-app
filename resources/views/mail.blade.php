@extends('layout')

@section('content')
<div id="inbox" class="panel panel-default">
  <div class="panel-body">
    <h1 class="panel-title">Inbox <span style="color: #717171; font-size: 14px"><?=$user['email'] ?></span></h1>
  </div>
</div>
  {{--<div class="panel-body">
    Here are the 10 most recent messages in your inbox.
      </div>--}}
    <?php if (isset($messages)) {
    foreach($messages as $message) { ?>

      <div class="panel panel-default">
          <div class="panel-heading">
              <h4 class="list-group-item-heading"><?= $message->getSubject() ?></h4>
              <h4 class="list-group-item-heading"><?= $message->getFrom()->getEmailAddress()->getName() ?> "<?= $message->getFrom()->getEmailAddress()->getAddress() ?>"</h4>
          </div>
          <div class="panel-body">
              <p class="list-group-item-heading text-muted"><em>Received: <?= $message->getReceivedDateTime()->format(DATE_RFC2822) ?></em></p>
              <p class="list-group-item-heading text-muted"><em><?= $message->getBodyPreview() ?></em></p>
          </div>
      </div>
    <?php  }
    } ?>
@endsection



