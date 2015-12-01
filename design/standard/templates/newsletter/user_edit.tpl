{*  newsletter/user_edit.tpl
    $newsletter_user_object
    edit of a newsletter user - with all related data
*}

<div class="newsletter newsletter-user_edit">

{* feedback message *}
    {if and( is_set( $message_feedback ), $message_feedback|ne( '' ) )}
    <div class="block">
        <div class="message-feedback">
            <h2>{$message_feedback|wash}</h2>
        </div>
    </div>
    {/if}

{* warnings *}
    {if and( is_set( $warning_array ), $warning_array|count|ne( 0 ) )}
    <div class="block">
        <div class="message-warning">
            <h2>{'Input did not validate'|i18n('cjw_newsletter/subscribe')}</h2>
            <ul>
            {foreach $warning_array as $index => $messageArrayItem}
                <li><span class="key">{$messageArrayItem.field_key|wash}: </span><span class="text">{$messageArrayItem.message|wash()}</span></li>
            {/foreach}
            </ul>
        </div>
    </div>
    {/if}

<form action={concat( 'newsletter/user_edit/', $newsletter_user_id )|ezurl} method="post">

<input type="hidden" name="RedirectUrlActionCancel" value="{$redirect_url_action_cancel}" />
<input type="hidden" name="RedirectUrlActionStore" value="{$redirect_url_action_store}" />

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
                                        {'Status'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td title="{$newsletter_user.status|wash}">
                                        {$newsletter_user.status_string|wash}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Email'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td>
                                        <input  class="halfbox" type="text" name="Subscription_Email" value="{$newsletter_user.email|wash}">
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Salutation'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td title="{'Salutation'|i18n( 'cjw_newsletter/user_view' )}">
                                        {foreach $available_salutation_array as $salutation_id => $salutataion_name}
                                            <input type="radio" name="Subscription_Salutation" value="{$salutation_id|wash}"{if $newsletter_user.salutation|eq( $salutation_id )} checked="checked"{/if} title="{$salutataion_name|wash}" />{$salutataion_name|wash}&nbsp;
                                        {/foreach}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'First name'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td>
                                        <input class="halfbox" type="text" name="Subscription_FirstName" value="{$newsletter_user.first_name|wash}" title="{'First name of newsletter user.'|i18n( 'cjw_newsletter/user_edit' )}"
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Last name'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td>
                                        <input class="halfbox" type="text" name="Subscription_LastName" value="{$newsletter_user.last_name|wash}" title="{'Last name of newsletter user.'|i18n( 'cjw_newsletter/user_edit' )}"
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {'Note'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <td>
                                        <textarea class="box" name="Subscription_Note" cols="50" rows="10">{$newsletter_user.note|wash}</textarea>
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        {'Subscriptions'|i18n( 'cjw_newsletter/user_edit' )}
                                    </th>
                                    <td>


                                {* list subscribe, remove *}

                                 {* fetch all available newsletter systems *}
                                    {def $newsletter_root_node_id = ezini( 'NewsletterSettings', 'RootFolderNodeId', 'cjw_newsletter.ini' )
                                         $newsletter_system_node_list = fetch( 'content', 'tree', hash('parent_node_id', $newsletter_root_node_id,
                                                                                            'class_filter_type', 'include',
                                                                                            'class_filter_array', array( 'cjw_newsletter_system' ),
                                                                                            'sort_by', array( 'name', true() ),
                                                                                            'limitation', hash( ) ))
                                         $available_subscription_status_id_name_array = cjw_newsletter_variable( 'available_subscription_status_id_name_array' )
                                         $status_id_array_enabled =  array()}

                                         {* set available status id for status selection list *}
                                         {if $newsletter_user.status|eq( 8 )}
                                             {set $status_id_array_enabled = array()}
                                         {else}
                                             {* as admin only allow set subscription status approved, remove by admin *}
                                             {set $status_id_array_enabled = array( 2, 4 )}
                                         {/if}

                                            {foreach $newsletter_system_node_list as $system_node}
                                                {def $newsletter_list_node_list = fetch( 'content', 'tree',
                                                                                                hash('parent_node_id', $system_node.node_id,
                                                                                                     'class_filter_type', 'include',
                                                                                                     'class_filter_array', array( 'cjw_newsletter_list' ),
                                                                                                     'limitation', hash( ) )) }

                                                <div class="newsletter-system-design">
                                                    <h2>{$system_node.data_map.title.content|wash}</h2>
                                                    <table border="0" width="100%" class="list">

                                                    {foreach $newsletter_list_node_list as $list_node sequence array( bglight, bgdark ) as $style}

                                                        <tr class="{$style}"> {*$newsletter_user.subscription_array*}
                                                        {def $list_id = $list_node.contentobject_id
                                                             $list_content = $list_node.data_map.newsletter_list.content
                                                             $subscription_array = $newsletter_user_subscription_array
                                                             $created = 0
                                                             $confirmed = 0
                                                             $approved = 0
                                                             $removed = 0
                                                             $subscription = null
                                                             $list_selected_output_format_array = array()
                                                             $selected_output_format_array = array()

                                                             $status = -1
                                                             $is_removed = false()
                                                             $subscription_hash = ''
                                                             $td_counter = 0
                                                             $modified = 0

                                                             }
                                                        {if is_set( $subscription_array[ $list_id ] )}
                                                            {set $subscription = $subscription_array[ $list_id ]
                                                                 $list_selected_output_format_array = $subscription.output_format_array

                                                                 $selected_output_format_array = $list_selected_output_format_array[ $list_id ]
                                                                 $created = $subscription.created
                                                                 $confirmed = $subscription.confirmed
                                                                 $removed = $subscription.removed
                                                                 $approved = $subscription.approved
                                                                 $bounced = $subscription.bounced
                                                                 $blacklisted = $subscription.blacklisted
                                                                 $status = $subscription.status
                                                                 $is_removed = $subscription.is_removed
                                                                 $subscription_hash = $subscription.hash
                                                                 $modified = $subscription.modified
                                                                 $status_id_array_enabled = $status_id_array_enabled|append( $status )|unique
                                                                 }

                                                        {/if}

                                                            <td width="300">
                                                                <input type="hidden" name="Subscription_IdArray[]" value="{$list_id}" title="" />
                                                                <input type="checkbox" name="Subscription_ListArray[]" value="{$list_id}"{if and( $is_removed|not , is_set( $subscription_array[ $list_id ] ) )} checked="checked"{/if} title="{$list_node.data_map.title.content|wash}" disabled="disabled" /> {$list_node.data_map.title.content|wash}
                                                            </td>

                                                            {* default value *}
                                                            {if $list_selected_output_format_array|count|eq(0)}
                                                                {set $list_selected_output_format_array = array( 0 )}
                                                            {/if}

                                                            <td class="newsletter-list">
                                                            {foreach $list_node.data_map.newsletter_list.content.output_format_array as $output_format_id => $output_format_name}
                                                                <input type="radio" name="Subscription_OutputFormatArray_{$list_id}[]" value="{$output_format_id|wash}" {if is_set( $list_selected_output_format_array[ $output_format_id ] )} checked="checked"{/if} title="{$output_format_name|wash}" /> {$output_format_name|wash}
                                                            {/foreach}
                                                            </td>

                                                            <td>{* status selection box *}
                                                                <select name="Subscription_StatusId_{$list_id}">
                                                                {if $status|eq(-1)}<option value="-1">-</option>{/if}

                                                                {def $status_already_selected = false()}
                                                                {foreach $available_subscription_status_id_name_array as $status_id => $status_name}

                                                                    {def $status_timestamp = 0
                                                                         $extra_string = false()
                                                                         $status_is_disabled = false()
                                                                         $status_is_selected = false()}

                                                                    {if $status_id_array_enabled|contains($status_id)|not}
                                                                        {set $status_is_disabled = true()}
                                                                    {/if}

                                                                    {*
                                                                        if subscription create for current list => set status approve
                                                                    *}
                                                                    {if and( $status_id|eq( 2 ),
                                                                             $list_id|eq( $add_subscription_for_list_id ),
                                                                             $status_already_selected|not )}
                                                                        {set $status_is_selected = true()}
                                                                        {set $status_already_selected = true()}

                                                                    {elseif and( $status_id|eq( $status ),
                                                                             $status_already_selected|not )}
                                                                            {set $status_is_selected = true()}
                                                                            {set $status_already_selected = true()}
                                                                    {/if}

                                                                    {*pending*}
                                                                    {if $status_id|eq( 0 )}
                                                                        {set $status_timestamp = $created}
                                                                    {*confirmed*}
                                                                    {elseif $status_id|eq( 1 )}
                                                                         {set $status_timestamp = $confirmed}
                                                                         {if $list_content.auto_approve_registered_user}
                                                                             {set $extra_string = concat(' [', 'auto approve'|i18n( 'cjw_newsletter/user_edit' ), ']')}
                                                                             {set $status_is_disabled = true()}
                                                                         {/if}

                                                                    {*approved*}
                                                                    {elseif $status_id|eq( 2 )}
                                                                        {set $status_timestamp = $approved}
                                                                    {*removed self*}
                                                                    {elseif $status_id|eq( 3 )}
                                                                        {set $status_timestamp = $removed}
                                                                    {*removed by admin*}
                                                                    {elseif $status_id|eq( 4 )}
                                                                        {set $status_timestamp = $removed}
                                                                    {/if}
                                                                    <option value="{$status_id}"{if $status_is_selected} selected="selected"{/if}{if $status_is_disabled}disabled="disabled"{/if}>{if $status|eq( $status_id )}[{/if}{$status_name}{if $status|eq( $status_id )}]{/if}{$extra_string}{if $status_timestamp|ne(0)} - {$status_timestamp|datetime( 'custom', '%j.%m.%Y %H:%i' )}{/if}</option>
                                                                    {undef $status_timestamp
                                                                           $extra_string
                                                                           $status_is_disabled
                                                                           $status_is_selected}
                                                                {/foreach}
                                                                {undef $status_already_selected}
                                                                </select>

 {*{if $status|ne(-1)}<span>status: ({$status|wash}) - created( {if $created|ne(0)} {$created|datetime( 'custom', '%j.%m.%Y %H:%i' )}{else} n/a {/if})|confirmed( {if $confirmed|ne(0)} {$confirmed|datetime( 'custom', '%j.%m.%Y %H:%i' )}{else} n/a {/if}) | approved({if $approved|ne(0)}  {$approved|datetime( 'custom', '%j.%m.%Y %H:%i' )} {else} n/a {/if}) | removed({if $removed|ne(0)}  {$removed|datetime( 'custom', '%j.%m.%Y %H:%i' )} {else} n/a {/if})</span>{/if}</td>*}

                                                           </td>
                                                           <td>
                                                                {if $modified|ne(0)}{'Modified'|i18n( 'cjw_newsletter/user_edit' )}: {$modified|datetime( 'custom', '%j.%m.%Y %H:%i' )}{/if}
                                                           </td>


                                                        {undef $list_id
                                                               $list_content
                                                               $list_selected_output_format_array
                                                               $subscription_array
                                                               $subscription
                                                               $created
                                                               $confirmed
                                                               $subscription
                                                               $removed
                                                               $approved
                                                               $bounced
                                                               $blacklisted
                                                               $status
                                                               $is_removed
                                                               $subscription_hash
                                                               $td_counter
                                                               $selected_output_format_array
                                                               $modified}
                                                        </tr>
                                                    {/foreach}
                                                    </table>
                                                </div>
                                                {undef $newsletter_list_node_list}

                                                {/foreach}
                                </td></tr>
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
                                        {* if blacklisted - do not edit *}
                                        {if $newsletter_user.status|eq(8)}
                                        <input class="button-disabled" type="button" value="{'Store and exit'|i18n( 'cjw_newsletter/user_edit' )}" />
                                        {else}
                                        <input class="button" type="submit" name="StoreButton" value="{'Store and exit'|i18n( 'cjw_newsletter/user_edit' )}" />
                                        {/if}

                                        {*  <input class="button" type="submit" name="StoreDraftButton" value="{'Store draft'|i18n( 'cjw_newsletter/user_edit' )}" />*}
                                        <input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'cjw_newsletter/user_edit' )}" />

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



</form>
</div>
{*$subscription_data_array|attribute(show,3)*}

{*$newsletter_user|attribute(show)*}
