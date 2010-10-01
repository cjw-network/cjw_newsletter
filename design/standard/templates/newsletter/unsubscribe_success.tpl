
<div class="newsletter newsletter-unsubscribe_success">

    <h1>{'Unsubscribe success'|i18n( 'cjw_newsletter/unsubscribe' )}</h1>

    <p>
        {'Hi %name

        You unsubscribe successfully from List "%listName".'|i18n( 'cjw_newsletter/unsubscribe',,
                                                                    hash( '%name', concat( $subscription.newsletter_user.first_name, ' ', $subscription.newsletter_user.last_name ),
                                                                          '%listName', $subscription.newsletter_list.name ) )|wash|nl2br}</p>
</div>
