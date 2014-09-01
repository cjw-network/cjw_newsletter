{def $email_receiver_test = $newsletter_list_attribute_content.email_receiver_test}

<form action={'newsletter/send'|ezurl()} method="post" style="display:inline;">




{*<label title="'test1@example.com;test2@example.com'">{"Send Test Newsletter at"|i18n("cjw_newsletter/send")}</label>*}

    {* Testemail input*}
    <input type="text" name="EmailReseiverTestInput" value="{$email_receiver_test}"  title="test1@example.com;test2@example.com" />

    <input type="hidden" name="TopLevelNode" value="{$node.object.main_node_id}" />
    <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
    <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
    <input type="hidden" name="mail_newsletter" value="true">

   {* Newsletter test email button. *}
    <input type="submit" class="defaultbutton" name="SendNewsletterTestButton" value="{"Send Test Newsletter"|i18n("cjw_newsletter/send")}">

</form>