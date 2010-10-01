{*
newsletter/import_list.tpl
list all import sets
*}
{def $page_uri = 'newsletter/import_list'}
<div class="newsletter newsletter-import_list">
    <div class="context-block">
        {* DESIGN: Header START *}
        <div class="box-header">
            <div class="box-tc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tl">
                            <div class="box-tr">
                                <h1 class="context-title">{'Manage imports'|i18n( 'cjw_newsletter/import_list',, hash() )}</h1>
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
                        {'Here you find a list of all data imports!'|i18n('cjw_newsletter/import_list')}</a>
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
                                <h2 class="context-title">{'Imports'|i18n( 'cjw_newsletter/import_list',, hash( )} [{$$import_list_count}]</h2>
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
                                    {case match=25}<a href={'/user/preferences/set/admin_import_list_limit/1'|ezurl}  title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10 </a>
                                    <span class="current">25</span>
                                    <a href={'/user/preferences/set/admin_import_list_limit/3'|ezurl}  title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50 </a>
                                    {/case}
                                    {case match=50}<a href={'/user/preferences/set/admin_import_list_limit/1'|ezurl}  title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10 </a>
                                    <a href={'/user/preferences/set/admin_import_list_limit/2'|ezurl}  title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25 </a>
                                    <span class="current">50</span>
                                    {/case}
                                    {case}<span class="current">10</span>
                                    <a href={'/user/preferences/set/admin_import_list_limit/2'|ezurl}  title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25 </a>
                                    <a href={'/user/preferences/set/admin_import_list_limit/3'|ezurl}  title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50 </a>
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
                                    {*
                                    <th class="tight">
                                        <img src={'toggle-button-16x16.gif'|ezimage}  alt="{'Invert selection'|i18n( 'cjw_newsletter/import_list' )}" title="{'Invert selection'|i18n( 'cjw_newsletter/import_list' )}" onclick="ezjs_toggleCheckboxes( document.user_list, 'SubscriptionIDArray[]' ); return false;" />
                                    </th>*}
                                <th class="tight">
                                    {'ID'|i18n('cjw_newsletter/import_list')}</a>
                                </th>
                                <th>
                                    {'Type'|i18n( 'cjw_newsletter/import_list' )}
                                </th>
                                <th>
                                    {'List Id'|i18n( 'cjw_newsletter/import_list' )}
                                </th>
                                <th>
                                    {'Creator'|i18n( 'cjw_newsletter/import_list' )}
                                </th>
                                <th>
                                    {'Note'|i18n( 'cjw_newsletter/import_list' )}
                                </th>
                                <th>
                                    {'Created'|i18n( 'cjw_newsletter/import_list' )}
                                </th>
                                <th>
                                    {'Imported'|i18n( 'cjw_newsletter/import_list' )}
                                </th>
                                <th title="{'Imported subscription count'|i18n( 'cjw_newsletter/import_list' )}">
                                    {'Count'|i18n( 'cjw_newsletter/import_list' )}
                                </th>
                                </tr>
                                {*$mailbox_item_list|attribute(show)*}
                                {foreach $import_list as $import_item sequence array( bglight, bgdark ) as $style}
                                <tr class="{$style}">
                                    {*
                                    <td>
                                        <input type="checkbox" name="SubscriptionIDArray[]" value="{$blacklist_item.id|wash}" title="{'Select items for removal'|i18n( 'cjw_newsletter/import_list' )}" />
                                    </td>*}
                                    <td class="number" align="right">
                                        <a href={concat( 'newsletter/import_view/', $import_item.id )|ezurl}>{$import_item.id|wash} </a>
                                    </td>
                                    <td>
                                        {$import_item.type|wash}
                                    </td>
                                    <td title="{$import_item.list_contentobject_id|wash}">
                                        {def $list_contentobject = $import_item.list_contentobject }
                                        {if is_object( $list_contentobject )}<a href={concat( 'content/view/full/', $list_contentobject.main_node_id)|ezurl}>{$list_contentobject.name|wash} </a>
                                        {/if}
                                        {undef $list_contentobject}
                                    </td>
                                    <td title="{$import_item.creator_contentobject_id|wash}">
                                        {if is_object( $import_item.creator )}
                                        {$import_item.creator.name|wash}
                                        {/if}
                                    </td>
                                    <td>
                                        {$import_item.note|wash}
                                    </td>
                                    <td>
                                        {$import_item.created|l10n( shortdatetime )|wash}
                                    </td>
                                    <td>
                                        {if $import_item.imported|gt(0)}{$import_item.imported|l10n( shortdatetime )|wash}{/if}
                                    </td>
                                    <td>
                                        {if $import_item.is_imported}<span title="{'Subscription count after import'|i18n( 'cjw_newsletter/import_list',, hash('%importId', $import_item.id ) )}">{$import_item.imported_subscription_count|wash}</span>
                                        | <span title="{'Subscriptions in current system with import id %importId'|i18n( 'cjw_newsletter/import_list',, hash('%importId', $import_item.id ) )}">{$import_item.imported_subscription_count_live|wash}</span>
                                        | <span title="{'Approved subscriptions in current system with import id %importId'|i18n( 'cjw_newsletter/import_list',, hash('%importId', $import_item.id ) )}"><b>{$import_item.imported_subscription_count_live_approved|wash}</b></span>{/if}
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
                                    item_count=$import_list_count
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
        </div>
        {* DESIGN: Content END *}
    </div>
</div>
