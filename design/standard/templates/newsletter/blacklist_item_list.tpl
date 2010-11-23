{*
newsletter/blacklist_list.tpl
list all blacklist items
*}

{def $page_uri = 'newsletter/blacklist_item_list'}
<div class="newsletter newsletter-mailbox_item_list">
    <div class="context-block">
        {* DESIGN: Header START *}
        <div class="box-header">
            <div class="box-tc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tl">
                            <div class="box-tr">
                                <h1 class="context-title">{'Manage blacklist'|i18n( 'cjw_newsletter/blacklist_item_list',, hash() )}</h1>
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
                        <div class="block float-break">
                            {'By adding an user to the blacklist, you can make sure that he will never get a newsletter again from this system.'|i18n('cjw_newsletter/blacklist_item_list')}
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
                                        <form method="post" action={'newsletter/blacklist_item_add'|ezurl}>
                                            <input class="button" type="submit" name="CreateBlacklistEntryButton" value="{'Add email address to blacklist'|i18n( 'cjw_newsletter/blacklist_item_list' )}" title="{'Create a new blacklist entry.'|i18n( 'cjw_newsletter/blacklist_item_list' )}" />
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
                                <h2 class="context-title">{'Blacklisted users'|i18n( 'cjw_newsletter/blacklist_item_list',, hash( ) )} [{$blacklist_item_list_count}]</h2>
                                {* DESIGN: Subline *}
                                <div class="header-subline">
                                </div>
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
                                        <a href={'/user/preferences/set/admin_blacklist_item_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10</a>
                                        <span class="current">25</span>
                                        <a href={'/user/preferences/set/admin_blacklist_item_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50</a>
                                    {/case}
                                    {case match=50}
                                        <a href={'/user/preferences/set/admin_blacklist_item_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10</a>
                                        <a href={'/user/preferences/set/admin_blacklist_item_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25</a>
                                        <span class="current">50</span>
                                    {/case}
                                    {case}
                                        <span class="current">10</span>
                                        <a href={'/user/preferences/set/admin_blacklist_item_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25</a>
                                        <a href={'/user/preferences/set/admin_blacklist_item_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50</a>
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
                              {*  <th class="tight">
                                    <img src={'toggle-button-16x16.gif'|ezimage}  alt="{'Invert selection'|i18n( 'cjw_newsletter/blacklist_item_list' )}" title="{'Invert selection'|i18n( 'cjw_newsletter/blacklist_item_list' )}" onclick="ezjs_toggleCheckboxes( document.user_list, 'SubscriptionIDArray[]' ); return false;" />
                                </th>*}
                                <th class="tight">
                                    {'ID'|i18n('cjw_newsletter/blacklist_item_list')}
                                </th>
                                {*<th>
                                    {'Email hash'|i18n( 'cjw_newsletter/blacklist_item_list' )}
                                </th>*}
                                <th>
                                    {'Email'|i18n( 'cjw_newsletter/blacklist_item_list' )}
                                </th>
                                <th>
                                    {'Newsletter UID'|i18n( 'cjw_newsletter/blacklist_item_list' )}
                                </th>
                                <th>
                                    {'Created'|i18n( 'cjw_newsletter/blacklist_item_list' )}
                                </th>
                                <th>
                                    {'Creator'|i18n( 'cjw_newsletter/blacklist_item_list' )}
                                </th>
                                <th>
                                    {'Note'|i18n( 'cjw_newsletter/blacklist_item_list' )}
                                </th>

                                </tr>

                                {foreach $blacklist_item_list as $blacklist_item sequence array( bglight, bgdark ) as $style}

                                <tr class="{$style}">
                                  {*  <td>
                                        <input type="checkbox" name="SubscriptionIDArray[]" value="{$blacklist_item.id|wash}" title="{'Select items for removal'|i18n( 'cjw_newsletter/blacklist_item_list' )}" />
                                    </td> *}
                                    <td class="number" align="right" title="{$blacklist_item.email_hash|wash}">
                                        {$blacklist_item.id|wash}
                                    </td>
                                   {* <td>
                                        {$blacklist_item.email_hash|wash}
                                    </td> *}
                                    <td>
                                        {$blacklist_item.email|wash}
                                    </td>
                                    <td>
                                        {if $blacklist_item.newsletter_user_id|ne( 0 )}
                                        <a href={concat('newsletter/user_view/',$blacklist_item.newsletter_user_id)|ezurl}>{$blacklist_item.newsletter_user_id|wash} </a>
                                        {else}
                                        N/A
                                        {/if}
                                    </td>
                                    <td>
                                        {$blacklist_item.created|l10n( shortdatetime )|wash}
                                    </td>
                                    <td title="{$blacklist_item.creator_contentobject_id|wash}">
                                        {if is_object( $blacklist_item.creator )}
                                            {$blacklist_item.creator.name|wash}
                                        {/if}
                                    </td>
                                    <td>
                                        {$blacklist_item.note|wash}
                                    </td>

                                </tr>
                                {/foreach}
                            </table>
                        </div>
                        {* Navigator. *}
                        <div class="context-toolbar subitems-context-toolbar">
                            {include name='Navigator'
                            uri='design:navigator/google.tpl'
                            page_uri=$page_uri
                            item_count=$blacklist_item_list_count
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

                                    </div>{* DESIGN: Control bar END *}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>{* DESIGN: Content END *}
    </div>
</div>