@extends('layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12" id="email-test-form">
                    <div class="well well-sm">
                        <form id="test-email" class="form-horizontal" action="/sendemail" method="post">
                            <fieldset>
                                <legend class="text-center">Send emails</legend>
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="sender_name">Sender name</label>
                                    <div class="col-md-9">
                                        <input id="sender_name" name="sender_name" type="text" placeholder="Sender name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="subject">Subject</label>
                                    <div class="col-md-9">
                                        <input id="subject" name="subject" type="text" placeholder="Subject" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="email">Campaign mailbox</label>
                                    <div class="col-md-9">
                                        <input id="email" name="email" type="email" placeholder="Campaign mailbox" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="to_email">Recipients</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" id="to_emails" name="to_emails" placeholder="recipients here" rows="5" required></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="names">Names</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" id="names" name="names" placeholder="Lead names here" rows="5" required></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="message">Html text</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" id="html" name="html" placeholder="Please enter your message here..." rows="5" required></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="message">Plain text</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" id="plain" name="plain" placeholder="Please enter your message here..." rows="5" required></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-primary btn-lg" id="send-mail-tester">Send</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



