{*  newsletter/subscription_view.tpl
$subscription
full view of an subscription - with all related data
*}
<div class="newsletter newsletter-subscription_view">
    <div class="context-block">
        {* DESIGN: Header START *}
        <div class="box-header">
            <div class="box-tc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tl">
                            <div class="box-tr">
                                <h1 class="context-title">{'Newsletter subscription'|i18n( 'cjw_newsletter/subscription_view',, hash() )}</h1>
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

                            {if $message|ne('')}
                            <div class="message">
                                <h2>{$message|wash}</h2>
                            </div>
                            {/if}

                            <table class="list">
                                <tr>
                                    <th>
                                        {'Subscription Id'|i18n( 'cjw_newsletter/subscription_view',, hash() )}
                                    </th>
                                    <td>
                                        {$subscription.id|wash}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Subscription list'|i18n( 'cjw_newsletter/subscription_view',, hash() )}
                                    </th>
                                    <td>
                                        <a href={concat( 'newsletter/subscription_list/',$newsletter_list_node_id )|ezurl}>{$newsletter_list_node.name|wash}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Newsletter user'|i18n( 'cjw_newsletter/subscription_view',, hash() )}
                                    </th>
                                    <td>
                                        <a href={concat('newsletter/user_view/',$subscription.newsletter_user_id )|ezurl} title="{$subscription.newsletter_user_id|wash}">{$subscription.newsletter_user.name|wash} &lt;{$subscription.newsletter_user.email|wash}&gt;</a> ({$subscription.newsletter_user.status_string})
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Status'|i18n( 'cjw_newsletter/subscription_view',, hash() )}
                                    </th>
                                    <td title="{$subscription.status|wash}">
                                        {$subscription.status_string|wash}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Format'|i18n( 'cjw_newsletter/subscription_view',, hash() )}
                                    </th>
                                    <td title="{$subscription.output_format_array_string|wash}">
                                        {$subscription.output_format_array|implode(',')}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Created'|i18n( 'cjw_newsletter/subscription_view',, hash() )}
                                    </th>
                                    <td>
                                        {$subscription.created|l10n( shortdatetime )}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Creator'|i18n( 'cjw_newsletter/subscription_view',, hash() )}
                                    </th>
                                    <td title="{$subscription.creator_contentobject_id|wash}">

                                        {if is_object($subscription.creator)}
                                            {$subscription.creator.name|wash}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Modified'|i18n( 'cjw_newsletter/subscription_view',, hash() )}
                                    </th>
                                    <td>
                                        {if $subscription.modified|ne(0)}
                                            {$subscription.modified|l10n( shortdatetime )}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Modifier'|i18n( 'cjw_newsletter/subscription_view',, hash() )}
                                    </th>
                                    <td title="{$subscription.modifier_contentobject_id|wash}">
                                        {if $subscription.modifier_contentobject_id|ne(0)}
                                            {if is_object( $subscription.modifier )}
                                                {$subscription.modifier.name|wash}
                                            {/if}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Confirmed'|i18n( 'cjw_newsletter/subscription_view',, hash() )}
                                    </th>
                                    <td>
                                        {if $subscription.confirmed|ne(0)}
                                            {$subscription.confirmed|l10n( shortdatetime )}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Approved'|i18n( 'cjw_newsletter/subscription_view',, hash() )}
                                    </th>
                                    <td>
                                        {if $subscription.approved|ne(0)}
                                            {$subscription.approved|l10n( shortdatetime )}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Removed'|i18n( 'cjw_newsletter/subscription_view',, hash() )}
                                    </th>
                                    <td>
                                        {if $subscription.removed|ne(0)}
                                            {$subscription.removed|l10n( shortdatetime )}
                                        {/if}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        {'Bounced'|i18n( 'cjw_newsletter/subscription_view',, hash() )}
                                    </th>
                                    <td>
                                        {if $subscription.bounced|ne(0)}
                                            {$subscription.bounced|l10n( shortdatetime )}
                                        {/if}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        {'Hash'|i18n( 'cjw_newsletter/subscription_view',, hash() )}
                                    </th>
                                    <td>
                                        {$subscription.hash|wash}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        {'Remote id'|i18n( 'cjw_newsletter/subscription_view',, hash() )}
                                    </th>
                                    <td>
                                        {$subscription.remote_id|wash}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Import id'|i18n( 'cjw_newsletter/subscription_view',, hash() )}
                                    </th>
                                    <td>
                                        {if $subscription.import_id|ne(0)}
                                        <a href={concat( 'newsletter/import_view/', $subscription.import_id )|ezurl}>{$subscription.import_id|wash}</a>
                                        {/if}
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

                                        {* only can approve if status not approve and not remove_self or blacklisted *}
                                        {if or( $subscription.status|eq(2), $subscription.status|eq(3), $subscription.status|eq(8) )}
                                            <input class="button-disabled" type="button" name="SubscriptionApproveButton" value="{'Approve subscription'|i18n( 'cjw_newsletter/subscription_view' )}" title="" />
                                        {else}
                                        <form name="ApproveForm" method="post" action=""  style="display:inline">
                                            <input class="button" type="submit" name="SubscriptionApproveButton" value="{'Approve subscription'|i18n( 'cjw_newsletter/subscription_view' )}" title="" />
                                        </form>
                                        {/if}
                                        {* only can remove if status not remove self / admin 3 our 4, or blacklisted 8 *}
                                        {if or( $subscription.status|eq(3), $subscription.status|eq(4), $subscription.status|eq(8) )}
                                            <input class="button-disabled" type="button" name="SubscriptionRemoveButton" value="{'Remove subscription'|i18n( 'cjw_newsletter/subscription_view' )}" title="" />
                                        {else}
                                        <form name="RemoveForm" method="post" action=""  style="display:inline">
                                            <input class="button" type="submit" name="SubscriptionRemoveButton" value="{'Remove subscription'|i18n( 'cjw_newsletter/subscription_view' )}" title="" />
                                        </form>
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
</div>

</div>
</div>
</div>
</div>
</div> {*$newsletter_user|attribute(show)*}