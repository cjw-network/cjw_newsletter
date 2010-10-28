{*  newsletter/mailbox_item_list.tpl
    list all collected mails
*}
{*def $limit = 50}
{if ezpreference( 'admin_mailbox_item_list_limit' )}
{switch match=ezpreference( 'admin_mailbox_item_list_limit' )}
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
{/if*}
{def $page_uri = 'newsletter/mailbox_item_list'}
<div class="newsletter newsletter-mailbox_item_list">
    <div class="context-block">
        {* DESIGN: Header START *}
        <div class="box-header">
            <div class="box-tc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tl">
                            <div class="box-tr">
                                <h1 class="context-title">{'Manage bounces'|i18n( 'cjw_newsletter/mailbox_item_list',, hash() )}</h1>
                                {* DESIGN: Mainline *}
                                <div class="header-mainline">
                                </div>
                                {* DESIGN: Header END *}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {* DESIGN: Content START *}
        <div class="box-ml">
            <div class="box-mr">
                <div class="box-content">
                    <div class="context-attributes">

                        <p>
                        {'Collect emails from bounce accounts and parse them. You may then accept the detected bounce status or manually adjust it.'|i18n( 'cjw_newsletter/mailbox_item_list',, hash() )}
                        </p>

                        <div class="block float-break">
                            {if is_set( $collect_mail_result )}<h3>{'Mailbox collect mail result'|i18n( 'cjw_newsletter/mailbox_item_list',, hash() )}</h3>
                            {foreach $collect_mail_result as $mailbox_id => $mailbox_items_status}
                            {'Collection result for mailbox %mailbox_id.'|i18n( 'cjw_newsletter/mailbox_item_list',,
                            hash( '%mailbox_id', $mailbox_id ) )}
                            <ul>
                                {if is_array($collect_mail_result[$mailbox_id])}
                                <li>
                                    {'Added'|i18n('cjw_newsletter/mailbox_item_list')}: {$collect_mail_result[$mailbox_id]['added']|count()}
                                </li>
                                <li>
                                    {'Already exists'|i18n('cjw_newsletter/mailbox_item_list')}: {$collect_mail_result[$mailbox_id]['exists']|count()}
                                </li>
                                <li>
                                    {'Failed'|i18n('cjw_newsletter/mailbox_item_list')}: {$collect_mail_result[$mailbox_id]['failed']|count()}
                                </li>
                                {else}
                                <li>
                                    {'Connection failed'|i18n('cjw_newsletter/mailbox_item_list')}
                                </li>
                                {/if}
                            </ul>
                            {/foreach}
                            {/if}
                            {if is_set( $parse_result )}<h3>{'Mailbox item parse result'|i18n( 'cjw_newsletter/mailbox_item_list',, hash() )}</h3>
                            {'E-mails parsed'|i18n('cjw_newsletter/mailbox_item_list')}: {$parse_result|count() }
                            {/if}
                        </div>
                    </div>
                    {* DESIGN: Content END *}
                </div>
            </div>
        </div>
        {* Buttons. *}
        <div class="controlbar">
            {* DESIGN: Control bar START *}
            <div class="box-bc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tc">
                            <div class="box-bl">
                                <div class="box-br">
                                    {* Edit *}
                                    <div class="left">
                                        <form action={$page_uri|ezurl} name="connectMailbox" method="get">
                                            {* Connectbutton *}
                                            <input type="submit" class="button" name="ConnectMailboxButton" value="{'Collect all mails'|i18n( 'cjw_newsletter/mailbox_item_list' )}">
                                            {* Bouncen *}
                                            <input type="submit" class="button" name="BounceMailItemButton" value="{'Parse mails'|i18n( 'cjw_newsletter/mailbox_item_list' )}">
                                        </form>
                                    </div>
                                </div>{* DESIGN: Control bar END *}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="context-block">
        {* DESIGN: Header START *}
        <div class="box-header">
            <div class="box-tc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tl">
                            <div class="box-tr">
                                <h2 class="context-title">{'Mailbox items'|i18n( 'cjw_newsletter/mailbox_item_list',, hash() )} [{$mailbox_item_list_count}]</h2>
                                {* DESIGN: Subline *}
                                <div class="header-subline"></div>
                                {* DESIGN: Header END *}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {* DESIGN:  START *}
        <div class="box-ml">
            <div class="box-mr">
                <div class="box-content">
                    <div class="context-attributes">

                        <div class="context-toolbar">
                            <div class="button-left">
                                <p class="table-preferences">
                                {switch match=$limit}
                                    {case match=25}
                                        <a href={'/user/preferences/set/admin_mailbox_item_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10</a>
                                        <span class="current">25</span>
                                        <a href={'/user/preferences/set/admin_mailbox_item_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50</a>
                                    {/case}
                                    {case match=50}
                                        <a href={'/user/preferences/set/admin_mailbox_item_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10</a>
                                        <a href={'/user/preferences/set/admin_mailbox_item_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25</a>
                                        <span class="current">50</span>
                                    {/case}
                                    {case}
                                        <span class="current">10</span>
                                        <a href={'/user/preferences/set/admin_mailbox_item_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25</a>
                                        <a href={'/user/preferences/set/admin_mailbox_item_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50</a>
                                    {/case}
                                {/switch}
                                </p>
                            </div>
                        </div>
                        <div class="break float-break">
                        </div>

                        <div class="content-navigation-childlist overflow-table">
                            <table class="list" cellspacing="0">
                                <tr>
                               {*     <th class="tight">
                                        <img src={'toggle-button-16x16.gif'|ezimage}  alt="{'Invert selection'|i18n( 'cjw_newsletter/mailbox_item_list' )}" title="{'Invert selection'|i18n( 'cjw_newsletter/mailbox_item_list' )}" onclick="ezjs_toggleCheckboxes( document.user_list, 'SubscriptionIDArray[]' ); return false;" />
                                    </th>
                                    *}
                                    <th class="tight">
                                    {'ID'|i18n('cjw_newsletter/mailbox_item_list')}</a>
                                </th>
                                <th>
                                    {'Mb'|i18n( 'cjw_newsletter/mailbox_item_list' )}
                                </th>
                                <th>
                                    {'Ms'|i18n( 'cjw_newsletter/mailbox_item_list' )}
                                </th>
                                <th>
                                    {'MI'|i18n( 'cjw_newsletter/mailbox_item_list' )}
                                </th>
                                <th>
                                    {'MSize'|i18n( 'cjw_newsletter/mailbox_item_list' )}
                                </th>
                                <th>
                                    {'Bouncecode'|i18n( 'cjw_newsletter/mailbox_item_list' )}
                                </th>
                                <th>
                                    {'IsBounce'|i18n( 'cjw_newsletter/mailbox_item_list' )}
                                </th>
                                <th>
                                    {'Nl user'|i18n( 'cjw_newsletter/mailbox_item_list' )}
                                </th>
                                <th>
                                    {'Subject'|i18n( 'cjw_newsletter/mailbox_item_list' )}
                                </th>
                                <th>
                                    {'From'|i18n( 'cjw_newsletter/mailbox_item_list' )}
                                </th>
                                <th>
                                    {'To'|i18n( 'cjw_newsletter/mailbox_item_list' )}
                                </th>
                                <th>
                                    {'Email send date'|i18n( 'cjw_newsletter/mailbox_item_list' )}
                                </th>
                                <th>
                                    {'Created'|i18n( 'cjw_newsletter/mailbox_item_list' )}
                                </th>
                                <th>
                                    {'Processed'|i18n( 'cjw_newsletter/mailbox_item_list' )}
                                </th>
                                </tr>
                                {*$mailbox_item_list|attribute(show)*}
                                {foreach $mailbox_item_list as $mailbox_item sequence array( bglight, bgdark ) as $style}
                                {if $mailbox_item.is_system_bounce}
                                <tr class="{$style}" style="background-color:#FFF6BF;">
                                {else}
                                <tr class="{$style}">
                                    {/if}
                               {*     <td>
                                        <input type="checkbox" name="SubscriptionIDArray[]" value="{$mailbox_item.id}" title="{'Select mailbox item for removal'|i18n( 'cjw_newsletter/mailbox_item_list' )}" />
                                    </td>*}
                                    <td class="number" align="right">
                                        <a href={concat( 'newsletter/mailbox_item_view/', $mailbox_item.id )|ezurl}>{$mailbox_item.id} </a>
                                    </td>
                                    <td>
                                        {$mailbox_item.mailbox_id|wash}
                                    </td>
                                    <td>
                                        {$mailbox_item.message_id|wash}
                                    </td>
                                    <td>
                                        {$mailbox_item.message_identifier|wash|shorten(50)}
                                    </td>
                                    <td>
                                        {*$mailbox_item.message_size|si( 'byte', 'kilo' )*}
                                        {$mailbox_item.message_size|div( 1024 )|round()} kB
                                    </td>
                                    <td>
                                        {$mailbox_item.bounce_code|wash}
                                    </td>
                                    <td>
                                        {if $mailbox_item.is_bounce|eq(true())}x{else}-{/if}
                                    </td>
                                    <td>
                                        {if $mailbox_item.newsletter_user_id|ne( 0 )}
                                        <a href={concat('newsletter/user_view/',$mailbox_item.newsletter_user_id)|ezurl}>{$mailbox_item.newsletter_user_id|wash} </a>
                                        {/if}
                                    </td>
                                    <td>
                                        {$mailbox_item.email_subject|wash|shorten(60)}
                                    </td>
                                    <td>
                                        {$mailbox_item.email_from|wash|shorten(30)}
                                    </td>
                                    <td>
                                        {$mailbox_item.email_to|wash|shorten(30)}
                                    </td>
                                    <td>
                                        {if eq( $mailbox_item.email_send_date, 0 )}
                                        {'n/a'|i18n( 'cjw_newsletter/mailbox_item_list' )}
                                        {else}
                                        {$mailbox_item.email_send_date|l10n( shortdatetime )}
                                        {/if}
                                    </td>
                                    <td>
                                        {$mailbox_item.created|l10n( shortdatetime )|wash}
                                    </td>
                                    <td>
                                        {if eq( $mailbox_item.processed, 0 )}
                                        {'n/a'|i18n( 'cjw_newsletter/mailbox_item_list' )}
                                        {else}
                                        {$mailbox_item.processed|l10n( shortdatetime )|wash}
                                        {/if}
                                    </td>
                                </tr>{/foreach}
                            </table>
                        </div>
                   </div>

                   <div class="context-toolbar subitems-context-toolbar">
                        {* Navigator. *}
                        <div class="context-toolbar">
                            {include name='Navigator'
                            uri='design:navigator/google.tpl'
                            page_uri=$page_uri
                            item_count=$mailbox_item_list_count
                            view_parameters=$view_parameters
                            item_limit=$limit}
                        </div>
                        {* DESIGN: Table END *}
                    </div>
                </div>
            </div>
            <div class="controlbar">
                {* DESIGN: Control bar START *}
                <div class="box-bc">
                    <div class="box-ml">
                        <div class="box-mr">
                            <div class="box-tc">
                                <div class="box-bl">
                                    <div class="box-br">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {* DESIGN: Control bar END *}
            </div>
        </div>
        {* DESIGN: Content END *}
    </div>
</div>
