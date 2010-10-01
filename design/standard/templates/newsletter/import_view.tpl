{*  newsletter/import_view.tpl
$newsletter_user_object
full view of an newsletter user - with all related data
*}
{def $page_uri = concat( 'newsletter/import_view/', $import_object.id )}
<div class="newsletter newsletter-import_view">
    <div class="context-block">
        {* DESIGN: Header DB Vars START *}
        <div class="box-header">
            <div class="box-tc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tl">
                            <div class="box-tr">
                                <h1 class="context-title">{$import_object.id|wash} [{'Import details'|i18n( 'cjw_newsletter/import_view',, hash() )|wash}]</h1>
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
        {def $list_contentobject = $import_object.list_contentobject }
        <div class="box-ml">
            <div class="box-mr">
                <div class="box-content">
                    <div class="context-attributes">
                        <div class="block float-break">
                            <table class="list">
                                <tr>
                                    <th>
                                        {'Id'|i18n( 'cjw_newsletter/import_view' )}
                                    </th>
                                    <td>
                                        {$import_object.id|wash}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Subscription list'|i18n( 'cjw_newsletter/import_view' )}
                                    </th>
                                    <td title="{$import_object.list_contentobject_id|wash}">

                                        {if is_object( $list_contentobject )}
                                            <a href={concat( 'content/view/full/', $list_contentobject.main_node_id)|ezurl}>{$list_contentobject.name|wash}</a>
                                        {/if}

                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Import type'|i18n( 'cjw_newsletter/import_view' )}
                                    </th>
                                    <td>
                                        {if and( $import_object.type|eq( 'cjwnl_csv' ), is_object( $list_contentobject ))}
                                            <a href={concat('newsletter/subscription_list_csvimport/', $list_contentobject.main_node_id, '/', $import_object.id )|ezurl}>{$import_object.type|wash}</a>
                                        {else}
                                            {$import_object.type|wash}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Created'|i18n( 'cjw_newsletter/import_view' )}
                                    </th>
                                    <td>
                                        {$import_object.created|l10n( shortdatetime )}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Creator'|i18n( 'cjw_newsletter/import_view' )}
                                    </th>
                                    <td title="{$import_object.creator.id|wash}">
                                        {$import_object.creator.name|wash}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Import note'|i18n( 'cjw_newsletter/import_view' )}
                                    </th>
                                    <td>
                                        {$import_object.note|wash}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Data text'|i18n( 'cjw_newsletter/import_view' )}
                                    </th>
                                    <td>
                                        {$import_object.data_text|wash}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Remote id'|i18n( 'cjw_newsletter/import_view' )}
                                    </th>
                                    <td>
                                        {$import_object.remote_id|wash}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Imported'|i18n( 'cjw_newsletter/import_view' )}
                                    </th>
                                    <td>
                                        {if $import_object.imported|gt(0)}{$import_object.imported|l10n( shortdatetime )|wash}{/if}
                                    </td>
                                </tr>
                                <tr>
                                    {def $imported_subscription_count_live_approved = $import_object.imported_subscription_count_live_approved}
                                    <th>
                                        {'Imported subscription count'|i18n( 'cjw_newsletter/import_view' )} | {'Live count'|i18n( 'cjw_newsletter/import_view' )} | {'Live count approved'|i18n( 'cjw_newsletter/import_view' )}
                                    </th>
                                    <td>
                                        <span title="{'Subscription count after import'|i18n( 'cjw_newsletter/import_view',, hash('%importId', $import_object.id ) )}">{$import_object.imported_subscription_count|wash}</span> |
                                        <span title="{'Subscriptions in current system with import id %importId'|i18n( 'cjw_newsletter/import_view',, hash('%importId', $import_object.id ) )}">{$import_object.imported_subscription_count_live|wash}</span> |
                                        <span title="{'Approved subscriptions in current system with import id %importId'|i18n( 'cjw_newsletter/import_view',, hash('%importId', $import_object.id ) )}"><b>{$import_object.imported_subscription_count_live_approved|wash}</b></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Imported user count'|i18n( 'cjw_newsletter/import_view' )} | {'Live count'|i18n( 'cjw_newsletter/import_view' )} | {'Live count confirmed'|i18n( 'cjw_newsletter/import_view' )}
                                    </th>
                                    <td>
                                        <span title="{'Newsletter user count after import'|i18n( 'cjw_newsletter/import_view',, hash('%importId', $import_object.id ) )}">{$import_object.imported_user_count|wash}</span> |
                                        <span title="{'Newsletter user in current system with import id %importId'|i18n( 'cjw_newsletter/import_view',, hash('%importId', $import_object.id ) )}">{$import_object.imported_user_count_live|wash}</span> |
                                        <span title="{'Confirmed Newsletter user in current system with import id %importId'|i18n( 'cjw_newsletter/import_view',, hash('%importId', $import_object.id ) )}"><b>{$import_object.imported_user_count_live_confirmed|wash}</b></span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        {* DESIGN: Content END *}
                    </div>
                </div>
            </div>
            {undef $list_contentobject}
            {* Buttons. *}
            <div class="controlbar">
                {* DESIGN: Control bar START *}
                <div class="box-bc">
                    <div class="box-ml">
                        <div class="box-mr">
                            <div class="box-tc">
                                <div class="box-bl">
                                    <div class="box-br">
                                        <form action={concat( 'newsletter/import_view/', $import_object.id )|ezurl} method="post">
                                        {* Edit *}

                                        <div class="left">
                                            <input class="button" type="submit" name="RemoveSubsciptionsByAdminButton" value="{'Remove %count_active_subscriptions active subscriptions by admin'|i18n( 'cjw_newsletter/import_view', ,hash( '%count_active_subscriptions', $imported_subscription_count_live_approved))}" onclick="return confirm('{'Do you really want to set status removed by admin to all active subscriptions (%count_active_subscriptions)?'|i18n( 'cjw_newsletter/import_view', ,hash( '%count_active_subscriptions', $imported_subscription_count_live_approved ) )}')" />
                                        </div>
                                        <div class="right">
                                        </div>
                                        </form>
                                    </div>{* DESIGN: Control bar END *}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {* =============  object header vars ============ *}
    {def $import_limit = 50
         $import_subscription_list = fetch( 'newsletter', 'import_subscription_list', hash( 'import_id' , $import_object.id,
                                                                                            'limit', $import_limit,
                                                                                            'offset', $view_parameters.offset ) )
         $import_subscription_list_count = fetch( 'newsletter', 'import_subscription_list_count', hash( 'import_id' , $import_object.id ) )
                                                                                                                    }


    <div class="context-block">

        {* DESIGN: Header START *}
        <div class="box-header">
            <div class="box-tc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tl">
                            <div class="box-tr">
                                <h2 class="context-title">{'Subscriptions created by import'|i18n( 'cjw_newsletter/import_view',, hash(  ) )} [{$import_subscription_list_count|wash}]</h2>
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
                        <div class="overflow-table">
                            <table class="list" cellspacing="0">
                            <tr>
                                <th class="tight">
                                    {'Id'|i18n( 'cjw_newsletter/subscription_list' )}
                                </th>
                                <th class="tight">
                                    {'List name'|i18n( 'cjw_newsletter/subscription_list' )}
                                </th>
                                <th class="tight">
                                    {'Newsletter User'|i18n( 'cjw_newsletter/subscription_list' )}
                                </th>
                                <th>
                                    {'Format'|i18n( 'cjw_newsletter/subscription_list' )}
                                </th>
                                <th>
                                    {'Status'|i18n( 'cjw_newsletter/subscription_list' )}
                                </th>
                                <th>
                                    {'Created'|i18n( 'cjw_newsletter/subscription_list' )}
                                </th>
                                <th>
                                    {'Modified'|i18n( 'cjw_newsletter/subscription_list' )}
                                </th>
                                <th>
                                    {'Remote id'|i18n( 'cjw_newsletter/subscription_list' )}
                                </th>
                                <th>
                                    {'Import Id'|i18n( 'cjw_newsletter/subscription_list' )}
                                </th>
                                <th>
                                    {'NL user import id'|i18n( 'cjw_newsletter/subscription_list' )}
                                </th>
                            </tr>

                            {foreach $import_subscription_list as $subscription sequence array( bglight, bgdark ) as $style}
                            {def $newsletter_user = $subscription.newsletter_user}
                            <tr class="{$style}">
                                <td>
                                    <a href={concat('newsletter/subscription_view/', $subscription.id)|ezurl}>{$subscription.id|wash} </a>
                                </td>
                                <td>
                                    <a href={concat('content/view/full/', $subscription.newsletter_list.main_node_id)|ezurl}>{$subscription.newsletter_list.name|wash} </a>
                                </td>
                                <td>
                                    <a href={concat('newsletter/user_view/', $subscription.newsletter_user_id)|ezurl}>{$newsletter_user.email} ({$newsletter_user.name|wash}) </a>
                                </td>
                                <td>
                                    {$subscription.output_format_array|implode(', ')}
                                </td>
                                <td title="{$subscription.status|wash}">
                                    {$subscription.status_string|wash}
                                </td>
                                <td>
                                    {cond( $subscription.created|gt(0), $subscription.created|l10n( shortdatetime ), 'n/a'|i18n( 'cjw_newsletter/import_view' ) )}
                                </td>
                                <td>
                                    {cond( $subscription.modified|gt(0), $subscription.modified|l10n( shortdatetime ), 'n/a'|i18n( 'cjw_newsletter/import_view' ) )}
                                </td>
                                <td>
                                    {$subscription.remote_id|wash}
                                </td>
                                <td>
                                    {$subscription.import_id|wash}
                                </td>
                                <td>
                                    {$newsletter_user.import_id|wash}
                                </td>
                            </tr>
                            {undef $newsletter_user}
                            {/foreach}
                        </table>
                        </div>{* DESIGN: Table END *}

                        {* Navigator. *}
                        <div class="context-toolbar">
                            {include name='Navigator'
                                     uri='design:navigator/google.tpl'
                                     page_uri=$page_uri
                                     item_count=$import_subscription_list_count
                                     view_parameters=$view_parameters
                                     item_limit=$import_limit}
                        </div>
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
                                </div>{* DESIGN: Control bar END *}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


</div>{* =============  list of all send items ============ *}

{*$import_object|attribute(show)*}