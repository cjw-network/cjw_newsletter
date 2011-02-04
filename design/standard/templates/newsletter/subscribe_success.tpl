{* subsribe_success.tpl is shown after successfully subscribe to a newsletter list

    $newsletter_user
    $mail_send_result
    $user_email_already_exists
    $subscription_result_array
    $back_url_input
*}
<div class="newsletter newsletter-subscribe_success">

    <div class="border-box">
    <div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
    <div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

    <h1>{'Newsletter - subscribe success'|i18n( 'cjw_newsletter/subscribe_success' )}</h1>

    <p class="newsletter-maintext">
        {'You are registered for our newsletter.'|i18n( 'cjw_newsletter/subscribe_success' )}
    </p>
    <p>
        {'An email was sent to your address %email.'|i18n('cjw_newsletter/subscribe_success',, hash( '%email' , $newsletter_user.email ) ) }
    </p>
    <p>
        {'Please note that your subscription is only active if you clicked confirmation link in these email.'|i18n( 'cjw_newsletter/subscribe_success' )}
        <br />
        {'You have the possibility of changing your personal profile at any time.'|i18n( 'cjw_newsletter/subscribe_success' )}
    </p>
    <p><a href="{$back_url_input}">{'back'|i18n( 'cjw_newsletter/subscribe_success' )}</a></p>

    </div></div></div>
    <div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
    </div>

</div>

