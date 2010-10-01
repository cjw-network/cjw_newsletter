{*
    newsletter/unsubscribe_already_done.tpl
*}

<div class="newsletter newsletter-unsubscribe_already_done">

    <h1>{'Unsubscription already done'|i18n( 'cjw_newsletter/unsubscribe' )}</h1>

    <p>
        {'Hi %name

        You already unsubscribed from List "%listName".'|i18n( 'cjw_newsletter/unsubscribe','',
                                                                    hash( '%name', concat( $subscription.newsletter_user.first_name, ' ', $subscription.newsletter_user.last_name ),
                                                                          '%listName', $subscription.newsletter_list.name ) )|wash|nl2br}</p>

</div>
