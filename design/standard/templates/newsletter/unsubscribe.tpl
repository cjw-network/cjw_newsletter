{*
    newsletter/unsubscribe.tpl => newsletter/unsubscribe_success.tpl
*}

<div class="newsletter newsletter-unsubscribe">

    <div class="border-box">
    <div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
    <div class="border-ml"><div class="border-mr"><div class="border-mc float-break">


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
        <a href={$node_url|ezurl}><input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'cjw_newsletter/subscribe' )}" /></a>
    </form>

    </div></div></div>
    <div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
    </div>

</div>