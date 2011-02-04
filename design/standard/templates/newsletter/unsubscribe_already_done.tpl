{*
    newsletter/unsubscribe_already_done.tpl
*}

<div class="newsletter newsletter-unsubscribe_already_done">

    <div class="border-box">
    <div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
    <div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

    <h1>{'Unsubscription already done'|i18n( 'cjw_newsletter/unsubscribe' )}</h1>

    <p>
        {'Hi %name

        You already unsubscribed from List "%listName".'|i18n( 'cjw_newsletter/unsubscribe','',
                                                                    hash( '%name', concat( $subscription.newsletter_user.first_name, ' ', $subscription.newsletter_user.last_name ),
                                                                          '%listName', $subscription.newsletter_list.name ) )|wash|nl2br}</p>


    </div></div></div>
    <div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
    </div>

</div>

