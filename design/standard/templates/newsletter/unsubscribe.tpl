{*
    newsletter/unsubscribe.tpl => newsletter/unsubscribe_success.tpl
*}
<div class="newsletter newsletter-unsubscribe">

    <h1>{'Unsubscribe'|i18n( 'cjw_newsletter/unsubscribe' )}</h1>

    <p>
{'Hi %name

If you want to unsubscribe from from List "%listName"
you have to confirm this page.'|i18n( 'cjw_newsletter/unsubscribe','',
                                          hash( '%name', concat( $subscription.newsletter_user.first_name, ' ', $subscription.newsletter_user.last_name ),
                                                '%listName', $subscription.newsletter_list.name ) )|wash|nl2br}</p>


    <form method="post" action={concat('newsletter/unsubscribe/', $subscription.hash)|ezurl}>
        <input type="hidden" name="CancelUriInput" value="/" />
        <input class="button" type="submit" name="SubscribeButton" value="{'Unsubscribe'|i18n( 'cjw_newsletter/unsubscribe' )}" title="{'Unsubscribe from list.'|i18n( 'cjw_newsletter/unsubscribe' )}" />
        <input class="button" type="submit" name="CancelButton" onclick="document.forms[0].action='';" value="{'Cancel'|i18n( 'cjw_newsletter/subscribe' )}" />
    </form>
</div>