{*  newsletter/user_view.tpl
    $newsletter_user_object
    full view of a newsletter user - with all related data
*}
<div class="newsletter newsletter-user_view">
    <div class="context-block">
        {* DESIGN: Header START *}
        <div class="box-header">
            <div class="box-tc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tl">
                            <div class="box-tr">
                                <h1 class="context-title">{$newsletter_user.name|wash} &lt;{$newsletter_user.email|wash}&gt; [{'Newsletter user'|i18n( 'cjw_newsletter/user_view',, hash() )|wash}]</h1>
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
                            <table class="list">
                                <tr>
                                    <th>
                                        {'Name'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td>
                                        {$newsletter_user.name|wash}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Id'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td>
                                        {$newsletter_user.id|wash}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Email'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td>
                                        {$newsletter_user.email|wash}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        {'Salutation'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td title="{$newsletter_user.salutation|wash}">
                                        {$newsletter_user.salutation_name|wash}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'First name'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td>
                                        {$newsletter_user.first_name|wash}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Last name'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td>
                                        {$newsletter_user.last_name|wash}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Status'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td title="{$newsletter_user.status|wash}">
                                        {$newsletter_user.status_string|wash}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'eZ user id'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td title="{$newsletter_user.ez_user_id|wash}">
                                        {if is_object($newsletter_user.ez_user)}
                                            <a href={concat('content/view/full/', $newsletter_user.ez_user.contentobject.main_node_id )|ezurl}>{$newsletter_user.ez_user.contentobject.name|wash}</a> ({if $newsletter_user.ez_user.is_enabled}{'enabled'|i18n( 'cjw_newsletter/user_view' )}{else}{'disabled'|i18n( 'cjw_newsletter/user_view' )}{/if})
                                        {elseif $newsletter_user.ez_user_id|ne( 0 )}
                                            <b> {'Ez user with id %ez_user_id does not exist anymore!'|i18n( 'cjw_newsletter/user_view', '', hash('%ez_user_id', $newsletter_user.ez_user_id ) )}</b>
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Creator'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td title="{$newsletter_user.creator_contentobject_id|wash}">

                                        {if is_object($newsletter_user.creator)}
                                            {$newsletter_user.creator.name|wash}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Created'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td>
                                        {$newsletter_user.created|l10n( shortdatetime )}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Modifier'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td title="{$newsletter_user.modifier_contentobject_id|wash}">
                                        {if is_object($newsletter_user.modifier)}
                                            {$newsletter_user.modifier.name|wash}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Modified'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td>
                                        {if $newsletter_user.modified|ne(0)}
                                            {$newsletter_user.modified|l10n( shortdatetime )}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Confirmed'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td>
                                        {if $newsletter_user.confirmed|ne(0)}
                                            {$newsletter_user.confirmed|l10n( shortdatetime )}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Removed'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td>
                                        {if $newsletter_user.removed|ne(0)}
                                            {$newsletter_user.removed|l10n( shortdatetime )}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Bounced'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td>
                                        {if $newsletter_user.bounced|ne(0)}
                                            {$newsletter_user.bounced|l10n( shortdatetime )}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Blacklisted'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td>
                                        {if $newsletter_user.blacklisted|ne(0)}
                                            {$newsletter_user.blacklisted|l10n( shortdatetime )}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Hash'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td>
                                        {$newsletter_user.hash|wash}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        {'Bounce count'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td>
                                        {$newsletter_user.bounce_count|wash}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Remote id'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td>
                                        {$newsletter_user.remote_id|wash}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Import id'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td>
                                        {$newsletter_user.import_id|wash}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Note'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td>
                                        {$newsletter_user.note|nl2br}
                                    </td>
                                </tr>
                                 <tr>
                                    <th>
                                        {'Data xml'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td>
                                        {$newsletter_user.data_xml}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Data text'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td>
                                        {$newsletter_user.data_text}
                                    </td>
                                </tr>
                            </table>
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
                                        {* edit current user *}
                                        {* status is not blacklisted *}
                                        {if $newsletter_user.status|ne(8)}
                                        <form method="post" style="display:inline;" action={concat( 'newsletter/user_edit/', $newsletter_user.id )|ezurl}>
                                            <input class="button" type="submit" name="EditUserButton" value="{'Edit'|i18n( 'cjw_newsletter/user_view' )}" title="{'Edit by admin'|i18n( 'cjw_newsletter/user_view' )}" />
                                        </form>
                                        {else}
                                        <input class="button-disabled" type="submit" value="{'Edit'|i18n( 'cjw_newsletter/user_view' )}" title="{'Edit by admin'|i18n( 'cjw_newsletter/user_view' )}" />
                                        {/if}

                                        <form method="post" style="display:inline;" action={concat( 'newsletter/user_remove/', $newsletter_user.id )|ezurl}>
                                            <input type="hidden" name="RedirectUrlActionRemove" value="newsletter/user_list/" />
                                            <input type="hidden" name="RedirectUrlActionCancel" value="{concat( 'newsletter/user_view/', $newsletter_user.id )}" />
                                            <input class="button" type="submit" name="DeleteUserButton" value="{'Remove'|i18n( 'cjw_newsletter/user_view' )}" title="{'Delete newsletter user and all subscriptions from database'|i18n( 'cjw_newsletter/user_view' )}" />
                                        </form>

                                        {* status is not blacklisted *}
                                        {if $newsletter_user.status|ne(8)}
                                        <form method="post" style="display:inline;" action={'newsletter/blacklist_item_add'|ezurl}>
                                            <input class="button" type="hidden" name="Email" value="{$newsletter_user.email|wash}" />
                                            <input class="button" type="submit" name="CreateBlacklistEntryButton" value="{'Add to blacklist'|i18n( 'cjw_newsletter/user_view' )}" title="{'Add to blacklist'|i18n( 'cjw_newsletter/user_view' )}" />
                                        </form>
                                        {else}
                                            <input class="button-disabled" type="button" name="CreateBlacklistEntryButton" value="{'Add to blacklist'|i18n( 'cjw_newsletter/user_view' )}" title="{'Add to blacklist'|i18n( 'cjw_newsletter/user_view' )}" />

                                        {/if}

                                    </div>
                                </div>{* DESIGN: Control bar END *}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>{* =============  list of all subscriptions ============ *}
    <div class="context-block">
    {def $subscription_array = $newsletter_user.subscription_array
         $subscription_array_count = $subscription_array|count}
    {* DESIGN: Header START *}
    <div class="box-header">
        <div class="box-tc">
            <div class="box-ml">
                <div class="box-mr">
                    <div class="box-tl">
                        <div class="box-tr">
                            <h2 class="context-title">{'Newsletter subscriptions'|i18n( 'cjw_newsletter/user_view',, hash( ) )} [{$subscription_array_count}]</h2>
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
                                    {'Confirmed'|i18n( 'cjw_newsletter/subscription_list' )}
                                </th>
                                <th>
                                    {'Approved'|i18n( 'cjw_newsletter/subscription_list' )}
                                </th>
                                <th>
                                    {'Removed'|i18n( 'cjw_newsletter/subscription_list' )}
                                </th>
                              {*  <th>
                                    {'Hash'|i18n( 'cjw_newsletter/subscription_list' )}
                                </th> *}
                            </tr>
                            {foreach $subscription_array as $subscription sequence array( bglight, bgdark ) as $style}
                            <tr class="{$style}">
                                <td>
                                    <a href={concat('newsletter/subscription_view/', $subscription.id)|ezurl}>{$subscription.id|wash} </a>
                                </td>
                                <td>
                                    <a href={concat('content/view/full/', $subscription.newsletter_list.main_node_id)|ezurl}>{$subscription.newsletter_list.name|wash} </a>
                                </td>
                                <td>
                                    {$subscription.output_format_array|implode(', ')}
                                </td>
                                <td title="{$subscription.status|wash}">
                                    {$subscription.status_string|wash}
                                </td>
                                <td>
                                    {cond( $subscription.created|gt(0), $subscription.created|l10n( shortdatetime ), 'n/a'|i18n( 'cjw_newsletter/subscription_list' ) )}
                                </td>
                                <td>
                                    {cond( $subscription.modified|gt(0), $subscription.modified|l10n( shortdatetime ), 'n/a'|i18n( 'cjw_newsletter/subscription_list' ) )}
                                </td>
                                <td>
                                    {cond( $subscription.confirmed|gt(0), $subscription.confirmed|l10n( shortdatetime ), 'n/a'|i18n( 'cjw_newsletter/subscription_list' ) )}
                                </td>
                                <td>
                                    {cond( $subscription.approved|gt(0), $subscription.approved|l10n( shortdatetime ), 'n/a'|i18n( 'cjw_newsletter/subscription_list' ) )}
                                </td>
                                <td>
                                    {cond( $subscription.removed|gt(0), $subscription.removed|l10n( shortdatetime ), 'n/a'|i18n( 'cjw_newsletter/subscription_list' ) )}
                                </td>
                                {*<td>
                                    {$subscription.hash|wash}
                                </td>*}

                            </tr>{/foreach}
                        </table>
                    </div>{* DESIGN: Table END *}
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
                                {*
                                TODO: löschen, bearbeiten<input class="button" type="submit" name="RemoveSubscriptionButton" value="{'Remove selected'|i18n( 'cjw_newsletter/user_list' )}" title="{'Remove selected subscription.'|i18n( 'cjw_newsletter/user_list' )}" /><input class="button" type="submit" name="CreateSubscriptionButton" value="{'New subscription'|i18n( 'cjw_newsletter/user_list' )}" title="{'Create a new subscription.'|i18n( 'cjw_newsletter/user_list' )}" />
                                </form>*}
                            </div>{* DESIGN: Control bar END *}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>{* =============  list of all send items ============ *}
<div class="context-block">
    {def $edition_send_item_array = fetch( 'newsletter', 'edition_send_item_list', hash('newsletter_user_id', $newsletter_user.id ) )
         $edition_send_item_count = fetch( 'newsletter', 'edition_send_item_list_count', hash('newsletter_user_id', $newsletter_user.id ) )}
    {* DESIGN: Header START *}
    <div class="box-header">
        <div class="box-tc">
            <div class="box-ml">
                <div class="box-mr">
                    <div class="box-tl">
                        <div class="box-tr">
                            <h2 class="context-title">{'Newsletter received'|i18n( 'cjw_newsletter/user_view',, hash() )} [{$edition_send_item_count}]</h2>
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
                                    {'Id'|i18n( 'cjw_newsletter/user_view' )}
                                </th>
                                <th>
                                    {'Edition sent id'|i18n( 'cjw_newsletter/user_view' )}
                                </th>
                                <th>
                                    {'Format'|i18n( 'cjw_newsletter/user_view' )}
                                </th>
                                <th>
                                    {'Status'|i18n( 'cjw_newsletter/user_view' )}
                                </th>
                                <th>
                                    {'Created'|i18n( 'cjw_newsletter/user_view' )}
                                </th>
                                <th>
                                    {'Processed'|i18n( 'cjw_newsletter/user_view' )}
                                </th>
                                <th>
                                    {'Opened'|i18n( 'cjw_newsletter/user_view' )}
                                </th>
                                <th>
                                    {'Bounced'|i18n( 'cjw_newsletter/user_view' )}
                                </th>
                            </tr>
                            {foreach $edition_send_item_array as $send_item sequence array( bglight, bgdark ) as $style}
                            <tr class="{$style}">
                                <td>
                                    <a target="_blank" href={concat( 'newsletter/preview_archive/', $send_item.edition_send_id,  '/', $send_item.output_format_id,  '/', $send_item.newsletter_user_id )|ezurl}>{$send_item.id|wash} </a>
                                </td>
                                <td>
                                    {$send_item.edition_send_id|wash}
                                </td>
                                <td>
                                    {$send_item.output_format_id|wash}
                                </td>
                                <td title="{$send_item.status|wash}">
                                    {$send_item.status_string|wash}
                                </td>
                                <td>
                                    {cond( $send_item.created|gt(0), $send_item.created|l10n( shortdatetime ), '-' )}
                                </td>
                                <td>
                                    {cond( $send_item.processed|gt(0), $send_item.processed|l10n( shortdatetime ), '-' )}
                                </td>
                                <td>
                                {* TODO opened *}
                                    -
                                </td>
                                <td>
                                    {cond( $send_item.bounced|gt(0), $send_item.bounced|l10n( shortdatetime ), '-' )}
                                </td>
                            </tr>
                            {/foreach}
                        </table>
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
                                {*
                                TODO: löschen, bearbeiten<input class="button" type="submit" name="RemoveSubscriptionButton" value="{'Remove selected'|i18n( 'cjw_newsletter/user_list' )}" title="{'Remove selected subscription.'|i18n( 'cjw_newsletter/user_list' )}" /><input class="button" type="submit" name="CreateSubscriptionButton" value="{'New subscription'|i18n( 'cjw_newsletter/user_list' )}" title="{'Create a new subscription.'|i18n( 'cjw_newsletter/user_list' )}" />
                                </form>*}
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
</div>
{*$newsletter_user|attribute(show)*}