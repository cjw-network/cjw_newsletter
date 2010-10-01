{* List of all stored mailboxes *}

{def $page_uri = 'newsletter/mailbox_list'}

<div class="newsletter newsletter-mailboxlist">

    <div class="context-block">
        {* DESIGN: Header START *}
        <div class="box-header">
            <div class="box-tc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tl">
                            <div class="box-tr">
                                <h1 class="context-title">{'Manage mail accounts'|i18n( 'cjw_newsletter/mailbox_list',, hash() )}</h1>
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
                        <p>{'Define mail accounts that will collect mails for bounce handling'|i18n( 'cjw_newsletter/mailbox_list',, hash() )}</p>
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
                                        <a href={concat( '/newsletter/mailbox_edit/', 0, '/(redirect)', $view_parameters.redirect_uri )|ezurl}>
                                            <input class="button" type="submit" name="AddMailbox" value="{'Add mail account'|i18n( 'cjw_newsletter/mailbox_list' )}" title="{'Add new mailbox.'|i18n( 'cjw_newsletter/mailbox_list' )}" />
                                        </a>
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
                                <h2 class="context-title">{'Mail accounts'|i18n( 'cjw_newsletter/mailbox_list',, hash() )} [{$mailbox_list_count}]</h2>
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
                                    <th class="tight">
                                        <img src={'toggle-button-16x16.gif'|ezimage}  alt="{'Invert selection'|i18n( 'cjw_newsletter/mailbox_list' )}" title="{'Invert selection'|i18n( 'cjw_newsletter/mailbox_list' )}" onclick="ezjs_toggleCheckboxes( document.user_list, 'SubscriptionIDArray[]' ); return false;" />
                                    </th>
                                    <th class="tight">
                                        {'ID'|i18n('cjw_newsletter/mailbox_list')}</a>
                                    </th>
                                    <th>
                                        {'Email'|i18n( 'cjw_newsletter/mailbox_edit' )}
                                    </th>
                                    <th>
                                        {'Server'|i18n( 'cjw_newsletter/mailbox_edit' )}
                                    </th>
                                    <th>
                                        {'Port'|i18n( 'cjw_newsletter/mailbox_edit' )}
                                    </th>
                                    <th>
                                        {'User'|i18n( 'cjw_newsletter/mailbox_edit' )}
                                    </th>
                                    <th>
                                        {'Password'|i18n( 'cjw_newsletter/mailbox_edit' )}
                                    </th>
                                    <th>
                                        {'Type'|i18n( 'cjw_newsletter/mailbox_edit' )}
                                    </th>
                                    <th>
                                        {'Active'|i18n( 'cjw_newsletter/mailbox_edit' )}
                                    </th>
                                    <th>
                                        {'SSL'|i18n( 'cjw_newsletter/mailbox_edit' )}
                                    </th>
                                    <th>
                                        {'Delete mails from server'|i18n( 'cjw_newsletter/mailbox_edit' )}
                                    </th>
                                    <th>
                                        {'Last connect'|i18n( 'cjw_newsletter/mailbox_list' )}
                                    </th>
                                    <th>
                                    </th>
                                </tr>
                                {foreach $mailbox_list as $mailbox_item sequence array( bglight, bgdark ) as $style}
                                <tr class="{$style}">
                                    <td>
                                        <input type="checkbox" name="SubscriptionIDArray[]" value="{$mailbox_item.id}" title="{'Select subscriber for removal'|i18n( 'cjw_newsletter/mailbox_list' )}" />
                                    </td>
                                    <td class="number" align="right">
                                        {$mailbox_item.id|wash}
                                    </td>
                                    <td>
                                        {$mailbox_item.email|wash}
                                    </td>
                                    <td>
                                        {$mailbox_item.server|wash}
                                    </td>
                                    <td>
                                        {$mailbox_item.port|wash}
                                    </td>
                                    <td>
                                        {$mailbox_item.user|wash}
                                    </td>
                                    <td>
                                        ***{*$mailbox_item.password|wash*}
                                    </td>
                                    <td>
                                        {$mailbox_item.type|wash}
                                    </td>
                                    <td>
                                        {$mailbox_item.is_activated|wash}
                                    </td>
                                    <td>
                                        {$mailbox_item.is_ssl|wash}
                                    </td>
                                    <td>
                                        {$mailbox_item.delete_mails_from_server|wash}
                                    </td>
                                    <td>
                                        {if eq( $mailbox_item.last_server_connect, 0 )}
                                            {'n/a'|i18n( 'cjw_newsletter/mailbox_list' )}
                                        {else}
                                            {$mailbox_item.last_server_connect|l10n( shortdatetime )}
                                        {/if}
                                    </td>
                                    <td>
                                        <a href={concat( '/newsletter/mailbox_edit/', $mailbox_item.id, '/(redirect)' , $view_parameters.redirect_uri )|ezurl}><img src={'edit.gif'|ezimage}  alt="{'Edit'|i18n( 'cjw_newsletter/mailbox_list' )}" title="{'Edit mailbox.'|i18n( 'cjw_newsletter/mailbox_list' )}" /></a>
                                    </td>
                                </tr>
                                {/foreach}
                            </table>
                        </div>
                   </div>

                   <div class="context-toolbar subitems-context-toolbar">
                            {include name='Navigator'
                                     uri='design:navigator/google.tpl'
                                     page_uri=$page_uri
                                     item_count=$mailbox_list_count
                                     view_parameters=$view_parameters
                                     item_limit=$limit}
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
