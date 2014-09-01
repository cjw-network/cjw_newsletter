{def $user_count_statistic = $list_node.data_map.newsletter_list.content.user_count_statistic}

{if is_set( $icons )}


{def $subscription_icon_css_class_array = hash( '0', 'icon12 icon_s_pending',
                                                '1', 'icon12 icon_s_confirmed',
                                                '2', 'icon12 icon_s_approved',
                                                '3', 'icon12 icon_s_removed',
                                                '4', 'icon12 icon_s_removed',
                                                '6', 'icon12 icon_s_bounced',
                                                '7', 'icon12 icon_s_bounced',
                                                '8', 'icon12 icon_s_blacklisted',
                                                'bounced', 'icon12 icon_s_bounced',
                                                'removed', 'icon12 icon_s_removed',
                                                 )}

{def $string = "Subscriptions statistic"|i18n( 'cjw_newsletter/subscription_list' )}

<div class="context-block ">
<table class="list" width="100%" border="0" cellspacing="0">
<tr>
<td>
    <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_user.png'|ezdesign} width="16" height="16" title="{"Subscriptions statistic"|i18n( 'cjw_newsletter/subscription_list' )}" />
    <a href={concat( 'newsletter/subscription_list/', $list_node.node_id )|ezurl}>{'Subscriptions'|i18n('cjw_newsletter/contentstructuremenu')} ({$user_count_statistic.all|wash})</a>
</td>
<td>
    {* Pending *}
    <img src={'1x1.gif'|ezimage} alt="" title="" class="{$subscription_icon_css_class_array[0]}" />
    <a href={concat( 'newsletter/subscription_list/', $list_node.node_id, '/(status)/pending' )|ezurl}>{'Pending'|i18n('cjw_newsletter/subscription_list')} ({$user_count_statistic.pending|wash})</a>
</td>
<td>
    {* Confirmed *}
    <img src={'1x1.gif'|ezimage} alt="" title="" class="{$subscription_icon_css_class_array[1]}" />
    <a href={concat( 'newsletter/subscription_list/', $list_node.node_id, '/(status)/confirmed' )|ezurl}>{'Confirmed'|i18n('cjw_newsletter/subscription_list')} ({$user_count_statistic.confirmed|wash})</a>
</td>
<td>
    {* Approved *}
    <img src={'1x1.gif'|ezimage} alt="" title="" class="{$subscription_icon_css_class_array[2]}" />
    <a href={concat( 'newsletter/subscription_list/', $list_node.node_id, '/(status)/approved' )|ezurl}>{'Approved'|i18n('cjw_newsletter/subscription_list')} ({$user_count_statistic.approved|wash})</a>
</td>
<td>
    <img src={'1x1.gif'|ezimage} alt="" title="" class="{$subscription_icon_css_class_array['bounced']}" />
    {* Bounced *}
    <a href={concat( 'newsletter/subscription_list/', $list_node.node_id, '/(status)/bounced' )|ezurl}>{'Bounced'|i18n('cjw_newsletter/subscription_list')} ({$user_count_statistic.bounced|wash})</a>
</td>
<td>
    <img src={'1x1.gif'|ezimage} alt="" title="" class="{$subscription_icon_css_class_array['removed']}" />
    {* Removed *}
    <a href={concat( 'newsletter/subscription_list/', $list_node.node_id, '/(status)/removed' )|ezurl}>{'Removed'|i18n('cjw_newsletter/subscription_list')} ({$user_count_statistic.removed|wash})</a>
</td>
<td>
    <img src={'1x1.gif'|ezimage} alt="" title="" class="{$subscription_icon_css_class_array[8]}" />
    {* Blacklisted *}
    <a href={concat( 'newsletter/subscription_list/', $list_node.node_id, '/(status)/blacklisted' )|ezurl}>{'Blacklisted'|i18n('cjw_newsletter/subscription_list')} ({$user_count_statistic.blacklisted|wash})</a>
</td>
</tr>
</table>
</div>



{else}

<div class="context-block">
    {*<p> <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_user.png'|ezdesign} width="16" height="16" /> <b>{"Subscriptions statistic"|i18n( 'cjw_newsletter/subscription_list' )}</b></p>*}
    <table class="list" cellspacing="0">
    <tr>
        <th>{'All'|i18n('cjw_newsletter/subscription_list')}</th>
        <th>{'Pending'|i18n('cjw_newsletter/subscription_list')}</th>
        <th>{'Confirmed'|i18n('cjw_newsletter/subscription_list')}</th>
        <th>{'Approved'|i18n('cjw_newsletter/subscription_list')}</th>
        <th>{'Bounced'|i18n('cjw_newsletter/subscription_list')}</th>
        <th>{'Removed'|i18n('cjw_newsletter/subscription_list')}</th>
        <th>{'Blacklisted'|i18n('cjw_newsletter/subscription_list')}</th>
    </tr>
    <tr>
        <td><a href={concat( 'newsletter/subscription_list/', $list_node.node_id )|ezurl}>{$user_count_statistic.all|wash}</a></td>
        <td><a href={concat( 'newsletter/subscription_list/', $list_node.node_id, '/(status)/pending' )|ezurl}>{$user_count_statistic.pending|wash}</a></td>
        <td><a href={concat( 'newsletter/subscription_list/', $list_node.node_id, '/(status)/confirmed' )|ezurl}>{$user_count_statistic.confirmed|wash}</a></td>
        <td><a href={concat( 'newsletter/subscription_list/', $list_node.node_id, '/(status)/approved' )|ezurl}><b>{$user_count_statistic.approved|wash}</b></a></td>
        <td><a href={concat( 'newsletter/subscription_list/', $list_node.node_id, '/(status)/bounced' )|ezurl}>{$user_count_statistic.bounced|wash}</a></td>
        <td><a href={concat( 'newsletter/subscription_list/', $list_node.node_id, '/(status)/removed' )|ezurl}>{$user_count_statistic.removed|wash}</a></td>
        <td><a href={concat( 'newsletter/subscription_list/', $list_node.node_id, '/(status)/blacklisted' )|ezurl}>{$user_count_statistic.blacklisted|wash}</a></td>
    </tr>
    </table>
</div>

{/if}