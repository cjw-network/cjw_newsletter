{*  newsletter/subscription_list.tpl

    list all subscription for a newsletter list
*}
<div class="newsletter newsletter-subscription_list">

{if $node.class_identifier|eq( 'cjw_newsletter_list' )}


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
{def $subscription_icon_css_class_16_array = hash( '0', 'icon16 icon_s_pending',
                                                '1', 'icon16 icon_s_confirmed',
                                                '2', 'icon16 icon_s_approved',
                                                '3', 'icon16 icon_s_removed',
                                                '4', 'icon16 icon_s_removed',
                                                '6', 'icon16 icon_s_bounced',
                                                '7', 'icon16 icon_s_bounced',
                                                '8', 'icon16 icon_s_blacklisted',
                                                'bounced', 'icon16 icon_s_bounced',
                                                'removed', 'icon16 icon_s_removed',
                                                 )}

{* icon test *}
{*
<hr />
{foreach $subscription_icon_css_class_array as $status_id => $css_class}
<img src={'16x16.gif'|ezimage} alt="{$subscription.status_string|wash}" class="{$css_class}" /> {$css_class} <br />
{/foreach}
<hr />
*}
{def $limit = 50}

{if ezpreference( 'admin_subscription_list_limit' )}
    {switch match=ezpreference( 'admin_subscription_list_limit' )}
        {case match=1}
            {set $limit=10}
        {/case}
        {case match=2}
            {set $limit=25}
        {/case}
        {case match=3}
            {set $limit=50}
        {/case}
    {/switch}
{/if}

{* subscription list *}
{def $newsletter_list_node = $node}
{def $status = ''}

{if is_set( $view_parameters.status )}
    {set $status = $view_parameters.status}
{/if}


{def $subscription_list = fetch('newsletter', 'subscription_list', hash( 'list_contentobject_id', $node.contentobject_id,
                                                                         'offset', $view_parameters.offset,
                                                                         'status', $status,
                                                                         'limit', $limit ))
     $subscription_list_count = fetch('newsletter', 'subscription_list_count', hash( 'list_contentobject_id', $node.contentobject_id,
                                                                                     'status', $status ))
     $base_uri = concat( 'newsletter/subscription_list/', $node.node_id )
     $uri_csv_import = concat( 'newsletter/subscription_list_csvimport/', $node.node_id )
     $uri_csv_export = concat( 'newsletter/subscription_list_csvexport/', $node.node_id )
     }

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title"><img src={'images/newsletter/icons/crystal-newsletter/32x32/newsletter_user.png'|ezdesign} width="32" height="32" /> {'Subscription list <%subscription_list_name>'|i18n( 'cjw_newsletter/subscription_list',, hash( '%subscription_list_name', $newsletter_list_node.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">



    {*<div class="block float-break">
        <p><b>{"Subscriptions statistic"|i18n( 'cjw_newsletter/subscription_list' )}</b></p>

        {def $user_count_statistic = $node.data_map.newsletter_list.content.user_count_statistic}
        <table class="list">
        <tr>
            {foreach $user_count_statistic as $status_name => $status_count}
            <th>{$status_name|wash}</th>
            {/foreach}
        </tr>
        <tr>
            {foreach $user_count_statistic as $status_name => $status_count}
            <td>{$status_count|wash}</td>
            {/foreach}
        </tr>
        </table>
    </div>*}

    {include uri='design:cjw_newsletter_list_statistic.tpl'
             name='Statistic'
             list_node=$node
             icons=true()}

{* DESIGN: Content END *}</div></div></div>
    {* Buttons. *}
    <div class="controlbar" >
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
        {* Edit *}
        <div class="left">

            <form name="CreateNewNewsletterUser" method="post" style="display:inline;" action={'newsletter/user_create'|ezurl}>
                <input type="hidden" name="Subscription_IdArray[]" value="{$node.contentobject_id}" />
                <input type="hidden" name="Subscription_ListArray[]" value="{$node.contentobject_id}" />
                <input type="hidden" name="RedirectUrlActionCancel" value="newsletter/subscription_list/{$node.node_id}" />
                <input type="hidden" name="RedirectUrlActionStore" value="newsletter/subscription_list/{$node.node_id}" />
                <input class="defaultbutton" type="submit" name="NewSubscriptionButton" value="{'Create new Subscription'|i18n( 'cjw_newsletter/subscription_list' )}" />
            </form>

            <form name="CsvImport" method="post" action={$uri_csv_import|ezurl} style="display:inline">
                <input class="button" type="submit" name="importcsv" value="{'Import CSV'|i18n( 'cjw_newsletter/subscription_list' )}" title="{'Import contact from CSV file.'|i18n( 'cjw_newsletter/newsletter_list_subscription' )}" />
            </form>

            <form name="CsvExport" method="post" action={$uri_csv_export|ezurl} style="display:inline">
                <input class="button" type="submit" name="importcsv" value="{'Export CSV'|i18n( 'cjw_newsletter/subscription_list' )}" title="{'Export to CSV file.'|i18n( 'cjw_newsletter/newsletter_list_subscription' )}" />
            </form>



        </div>
    </div>


{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>
</div>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h2 class="context-title">{'Subscribers'|i18n( 'cjw_newsletter/subscription_list' )} [{$subscription_list_count}]</h2>
{* DESIGN: Subline *}<div class="header-subline"></div>
{* DESIGN: Header END *}</div></div></div></div></div></div>
{* DESIGN:  START *}<div class="box-ml"><div class="box-mr"><div class="box-content">
<div class="context-attributes">

    <div class="context-toolbar">
        <div class="button-left">
            <p class="table-preferences">
            {switch match=$limit}
                {case match=25}
                    <a href={'/user/preferences/set/admin_subscription_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10</a>
                    <span class="current">25</span>
                    <a href={'/user/preferences/set/admin_subscription_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50</a>
                {/case}
                {case match=50}
                    <a href={'/user/preferences/set/admin_subscription_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10</a>
                    <a href={'/user/preferences/set/admin_subscription_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25</a>
                    <span class="current">50</span>
                {/case}
                {case}
                    <span class="current">10</span>
                    <a href={'/user/preferences/set/admin_subscription_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25</a>
                    <a href={'/user/preferences/set/admin_subscription_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50</a>
                {/case}
            {/switch}
            </p>
        </div>


        <div class="button-left">
            {* newsletter list selection *}
        <p class="table-preferences">

            {if $status|eq('')}
                <span class="current">
                    {'All'|i18n('cjw_newsletter/subscription_list')}
                </span>
            {else}
                <a href={concat( $base_uri,'')|ezurl}>
                    {'All'|i18n('cjw_newsletter/subscription_list')}
                </a>
            {/if}

            {if $status|eq('pending')}
                <span class="current">
                   <img src={'1x1.gif'|ezimage} alt="{'Pending'|i18n('cjw_newsletter/subscription_list')}" title="{'Pending'|i18n('cjw_newsletter/subscription_list')}" class="{$subscription_icon_css_class_array[0]}" /> {'Pending'|i18n('cjw_newsletter/subscription_list')}
                </span>
            {else}
                <a href={concat($base_uri, '/(status)/pending' )|ezurl}>
                    <img src={'1x1.gif'|ezimage} alt="{'Pending'|i18n('cjw_newsletter/subscription_list')}" title="{'Pending'|i18n('cjw_newsletter/subscription_list')}" class="{$subscription_icon_css_class_array[0]}" /> {'Pending'|i18n('cjw_newsletter/subscription_list')}
                </a>
            {/if}

            {if $status|eq('confirmed')}
                <span class="current">
                    <img src={'1x1.gif'|ezimage} alt="{'Confirmed'|i18n('cjw_newsletter/subscription_list')}" title="{'Confirmed'|i18n('cjw_newsletter/subscription_list')}" class="{$subscription_icon_css_class_array[1]}" /> {'Confirmed'|i18n('cjw_newsletter/subscription_list')}
                </span>
            {else}
                <a href={concat($base_uri, '/(status)/confirmed' )|ezurl}>
                    <img src={'1x1.gif'|ezimage} alt="{'Confirmed'|i18n('cjw_newsletter/subscription_list')}" title="{'Confirmed'|i18n('cjw_newsletter/subscription_list')}" class="{$subscription_icon_css_class_array[1]}" /> {'Confirmed'|i18n('cjw_newsletter/subscription_list')}
                </a>
            {/if}
            {if $status|eq('approved')}
                <span class="current">
                    <img src={'1x1.gif'|ezimage} alt="{'Approved'|i18n('cjw_newsletter/subscription_list')}" title="{'Approved'|i18n('cjw_newsletter/subscription_list')}" class="{$subscription_icon_css_class_array[2]}" /> {'Approved'|i18n('cjw_newsletter/subscription_list')}
                </span>
            {else}
                <a href={concat($base_uri, '/(status)/approved' )|ezurl}>
                    <img src={'1x1.gif'|ezimage} alt="{'Approved'|i18n('cjw_newsletter/subscription_list')}" title="{'Approved'|i18n('cjw_newsletter/subscription_list')}" class="{$subscription_icon_css_class_array[2]}" /> {'Approved'|i18n('cjw_newsletter/subscription_list')}
                </a>
            {/if}
            {if $status|eq('bounced')}
                <span class="current">
                    <img src={'1x1.gif'|ezimage} alt="{'Bounced'|i18n('cjw_newsletter/subscription_list')}" title="{'Bounced'|i18n('cjw_newsletter/subscription_list')}" class="{$subscription_icon_css_class_array['bounced']}" /> {'Bounced'|i18n('cjw_newsletter/subscription_list')}
                </span>
            {else}
                <a href={concat($base_uri, '/(status)/bounced' )|ezurl}>
                    <img src={'1x1.gif'|ezimage} alt="{'Bounced'|i18n('cjw_newsletter/subscription_list')}" title="{'Bounced'|i18n('cjw_newsletter/subscription_list')}" class="{$subscription_icon_css_class_array['bounced']}" /> {'Bounced'|i18n('cjw_newsletter/subscription_list')}
                </a>
            {/if}
            {if $status|eq('removed')}
                <span class="current">
                    <img src={'1x1.gif'|ezimage} alt="{'Removed'|i18n('cjw_newsletter/subscription_list')}" title="{'Removed'|i18n('cjw_newsletter/subscription_list')}" class="{$subscription_icon_css_class_array['removed']}" /> {'Removed'|i18n('cjw_newsletter/subscription_list')}
                </span>
            {else}
                <a href={concat($base_uri, '/(status)/removed' )|ezurl}>
                    <img src={'1x1.gif'|ezimage} alt="{'Removed'|i18n('cjw_newsletter/subscription_list')}" title="{'Removed'|i18n('cjw_newsletter/subscription_list')}" class="{$subscription_icon_css_class_array['removed']}" /> {'Removed'|i18n('cjw_newsletter/subscription_list')}
                </a>
            {/if}
            {if $status|eq('blacklisted')}
                <span class="current">
                    <img src={'1x1.gif'|ezimage} alt="{'Blacklisted'|i18n('cjw_newsletter/subscription_list')}" title="{'Blacklisted'|i18n('cjw_newsletter/subscription_list')}" class="{$subscription_icon_css_class_array[8]}" /> {'Blacklisted'|i18n('cjw_newsletter/subscription_list')}
                </span>
            {else}
                <a href={concat($base_uri, '/(status)/blacklisted' )|ezurl}>
                    <img src={'1x1.gif'|ezimage} alt="{'Blacklisted'|i18n('cjw_newsletter/subscription_list')}" title="{'Blacklisted'|i18n('cjw_newsletter/subscription_list')}" class="{$subscription_icon_css_class_array[8]}" /> {'Blacklisted'|i18n('cjw_newsletter/subscription_list')}
                </a>
            {/if}


            </p>
        </div>


    </div>
    <div class="break float-break">
    </div>

    {* Subscription list table. *}
    <div class="content-navigation-childlist overflow-table">

        <table class="list" cellspacing="0">
        <tr>
        {*
            <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection'|i18n( 'cjw_newsletter/subscription_list' )}" title="{'Invert selection'|i18n( 'cjw_newsletter/subscription_list' )}" onclick="ezjs_toggleCheckboxes( document.subscription_list, 'SubscriptionIDArray[]' ); return false;" /></th>
        *}
            <th class="tight">{'ID'|i18n('cjw_newsletter/subscription_list')}</th>
            <th>{'Email'|i18n( 'cjw_newsletter/subscription_list' )}</th>
            <th>{'First name'|i18n( 'cjw_newsletter/subscription_list' )}</th>
            <th>{'Last name'|i18n( 'cjw_newsletter/subscription_list' )}</th>
            <th>{'eZ Publish User'|i18n('cjw_newsletter/subscription_list')}</th>
            <th>{'Format'|i18n( 'cjw_newsletter/subscription_list' )}</th>
            <th>{'Status'|i18n( 'cjw_newsletter/subscription_list' )}</th>
            <th>{'Modified'|i18n( 'cjw_newsletter/subscription_list' )}</th>
            <th></th>
        </tr>


        {foreach $subscription_list as $subscription sequence array( bglight, bgdark ) as $style}

        <tr class="{$style}">
        {*
            <td><input type="checkbox" name="SubscriptionIDArray[]" value="{$subscription.id|wash}" title="{'Select subscriber for removal'|i18n( 'cjw_newsletter/subscription_list' )}" /></td>
        *}
            <td>{$subscription.id|wash}</td>
            <td><a href={concat('newsletter/user_view/',$subscription.newsletter_user.id)|ezurl} title="{$subscription.newsletter_user.first_name} {$subscription.newsletter_user.last_name}">{$subscription.newsletter_user.email}</a></td>
            <td>{$subscription.newsletter_user.first_name|wash}</td>
            <td>{$subscription.newsletter_user.last_name|wash}</td>
            <td>
                {if $subscription.newsletter_user.ez_user_id|gt( '0' )}
                    {def $user_object = fetch( 'content', 'object', hash( 'object_id', $subscription.newsletter_user.ez_user_id ) )}
                    {if $user_object}
                        <a href="{$user_object.main_node.url_alias|ezurl( 'no' )}">{$user_object.name|wash}</a>
                    {/if}
                    {undef $user_object}
                {/if}
            </td>
            <td>{$subscription.output_format_array|implode(', ')}</td>
            <td><img src={'16x16.gif'|ezimage} alt="{$subscription.status_string|wash}" class="{$subscription_icon_css_class_array[$subscription.status]}" title="{$subscription.status_string|wash} ({$subscription.status|wash})" /></td>
            <td>{cond( $subscription.modified|gt(0), $subscription.modified|l10n( shortdatetime ), 'n/a'|i18n( 'cjw_newsletter/subscription_list' ) )}</td>
            <td style="white-space: nowrap;">
                <form class="inline" action="{concat('newsletter/subscription_view/', $subscription.id )|ezurl( 'no' )}">
                    <input class="button" type="submit" value="{'Details'|i18n( 'cjw_newsletter/user_list' )}" title="{'Subscription details'|i18n( 'cjw_newsletter/user_list' )}" name="SubscriptionDetails" />
                </form>
                <form class="inline" action="{concat( '/newsletter/subscription_view/', $subscription.id )|ezurl( 'no' )}" method="post">
                    <input  {if or( $subscription.status|eq(2), $subscription.status|eq(3), $subscription.status|eq(8) )}class="button-disabled" disabled="disabled"{else}class="button"{/if} type="submit" value="{'Approve'|i18n( 'cjw_newsletter/subscription_list' )}" name="SubscriptionApproveButton" title="{'Approve subscription'|i18n( 'cjw_newsletter/subscription_list' )}" />
                </form>
                <form class="inline" action="{concat( 'newsletter/user_edit/', $subscription.newsletter_user.id, '?RedirectUrl=', $base_uri, '/(offset)/', $view_parameters.offset )|ezurl( 'no' )}" method="post">
                    <input class="button" type="submit" value="{'Edit'|i18n( 'cjw_newsletter/user_list' )}" title="{'Edit newsletter user'|i18n( 'cjw_newsletter/user_list' )}" name="EditNewsletterUser" />
                </form>
            </td>
        </tr>
        {/foreach}
        </table>

    </div>

    {* Navigator. *}
    <div class="context-toolbar subitems-context-toolbar">
        {include name='Navigator'
                 uri='design:navigator/google.tpl'
                 page_uri=$base_uri
                 item_count=$subscription_list_count
                 view_parameters=$view_parameters
                 item_limit=$limit}
    </div>

{* DESIGN: Table END *}</div></div></div>

    <div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

{*
    <input class="button" type="submit" name="RemoveSubscriptionButton" value="{'Remove selected'|i18n( 'cjw_newsletter/subscription_list' )}" title="{'Remove selected subscription.'|i18n( 'cjw_newsletter/subscription_list' )}" />
    <input class="button" type="submit" name="CreateSubscriptionButton" value="{'New subscription'|i18n( 'cjw_newsletter/subscription_list' )}" title="{'Create a new subscription.'|i18n( 'cjw_newsletter/subscription_list' )}" />
*}
</form>
    </div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
    </div>
{* DESIGN: Content END *}</div></div></div>


</div>


{else}
    This View is only available for 'Newsletter List' objects
{/if}

</div>