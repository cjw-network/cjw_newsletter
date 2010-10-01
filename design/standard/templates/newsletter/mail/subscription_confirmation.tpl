{*?template charset=utf-8?*}{set-block variable=$subject scope=root}{ezini('NewsletterMailSettings', 'EmailSubjectPrefix', 'cjw_newsletter.ini')} {'Subscription verification'|i18n( 'cjw_newsletter/subscription_confirmation' )}{/set-block}
{*
$newsletter_user
$hostname
*}
{def $subscriptionListString = ''}
{foreach $newsletter_user.subscription_array as $subscription}
{set $subscriptionListString = concat( $subscriptionListString, "\n- ", $subscription.newsletter_list.data_map.title.content|wash() )}
{/foreach}
{'Hello %name

Thank you for subscribing to the following newsletter:
%subscriptionList

To activate or edit your subscription, please visit this link:

%configureLink
'|i18n( 'cjw_newsletter/mail/subscription_confirmation',,
                                         hash( '%name', concat( $newsletter_user.first_name, ' ', $newsletter_user.last_name ),
                                               '%subscriptionList', $subscriptionListString,
                                               '%listName', $newsletter_list.name,
                                               '%configureLink', concat( 'http://', $hostname, concat( '/newsletter/configure/', cond( $newsletter_user, $newsletter_user.hash, '#' ) )|ezurl(no) ),
                                                ) )}
{include uri="design:newsletter/mail/footer.tpl"}