{* newsletter/configure.tpl *}
<div class="newsletter newsletter-configure">

    <h1>{'Configure newsletter settings'|i18n( 'cjw_newsletter/configure' )}</h1>

    {def $newsletter_root_node_id = ezini( 'NewsletterSettings', 'RootFolderNodeId', 'cjw_newsletter.ini' )
         $available_output_formats = 2} {* for tables *}

    {if is_set( $confirm_all_result )}
    <div class="message-feedback">
         <h2>{'Newsletter confirmation successfull'|i18n( 'cjw_newsletter/configure' )}</h2>
    </div>
    {/if}

    {if is_set( $changes_saved )}
    <div class="message-feedback">
        <h2>{'Changes saved'|i18n( 'cjw_newsletter/configure' )}</h2>
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

    <form name="configure" method="post" action={concat('/newsletter/configure/', $newsletter_user.hash)|ezurl}>

    {* fetch all available newsletter systems *}
    {def $newsletter_system_node_list = fetch( 'content', 'tree', hash('parent_node_id', $newsletter_root_node_id,
                                                            'class_filter_type', 'include',
                                                            'class_filter_array', array('cjw_newsletter_system'),
                                                            'sort_by', array( 'name', true() ),
                                                            'limitation', hash( ) )) }

    {* check if a newsletter_system is available *}
    {if $newsletter_system_node_list|count|eq(0)}
        <p>
            {'No newsletters available for configure now.'|i18n( 'cjw_newsletter/configure' )}
        </p>
    {else}
    {* for every newsletter_system check if there are newsletter_list where a subscription for current siteaccess is possible *}
        {def $newsletter_list_node_list = fetch( 'content', 'tree', hash('parent_node_id', $newsletter_system_node_list.0.node_id,
                                                                'extended_attribute_filter',
                                                                      hash( 'id', 'CjwNewsletterListFilter',
                                                                            'params', hash( 'siteaccess', array( 'current_siteaccess' ) ) ),
                                                                'class_filter_type', 'include',
                                                                'class_filter_array', array('cjw_newsletter_list'),
                                                                'limitation', hash() ))
             $newsletter_available=false()
        }

        {foreach $newsletter_system_node_list as $system_node}
            {set $newsletter_list_node_list = fetch( 'content', 'tree', hash('parent_node_id', $system_node.node_id,
                                                                'extended_attribute_filter',
                                                                      hash( 'id', 'CjwNewsletterListFilter',
                                                                            'params', hash( 'siteaccess', array( 'current_siteaccess' ) ) ),
                                                                'class_filter_type', 'include',
                                                                'class_filter_array', array('cjw_newsletter_list'),
                                                                'limitation', hash( ) )) }
            {if $newsletter_list_node_list|count()|gt(0)}
                {set $newsletter_available=true()}
            {/if}
        {/foreach}
        {undef $newsletter_list_node_list}

        {* no nl_list available *}
        {if $newsletter_available|not()}
            <p>
                {'No newsletters available for configure now.'|i18n( 'cjw_newsletter/configure' )}
            </p>
        {* nl_list available *}
        {else}
                <p>{'Here you can edit your attitutes for newsletter.'|i18n( 'cjw_newsletter/configure' )}</p>
                <p>{'Please select the newsletter for subscribe or unselect the newsletter for unsubsucribe.'|i18n( 'cjw_newsletter/configure' )}</p>
                <p>{'You can also edit the small boxes "first name" and "last name".'|i18n( 'cjw_newsletter/configure' )}</p>
                <p>{'You can not edit your email address. Please, announce yourselves once more to newsletter to use another email address.'|i18n( 'cjw_newsletter/configure' )}</p>

                {foreach $newsletter_system_node_list as $system_node}
                    {def $newsletter_list_node_list = fetch( 'content', 'tree',
                                                                hash('parent_node_id', $system_node.node_id,
                                                                     'extended_attribute_filter',
                                                                          hash( 'id', 'CjwNewsletterListFilter',
                                                                                'params', hash( 'siteaccess', array( 'current_siteaccess' ) ) ),
                                                                     'class_filter_type', 'include',
                                                                     'class_filter_array', array('cjw_newsletter_list'),
                                                                     'limitation', hash( ) )) }
                {if $newsletter_list_node_list|count|ne(0)}
                <div class="newsletter-system-design">
                    <h2>{$system_node.data_map.title.content|wash}</h2>
                    <table border="0" width="100%">

                    {foreach $newsletter_list_node_list as $list_node}
                        <tr>
                        {def $list_id = $list_node.contentobject_id
                             $subscription_array = $newsletter_user.subscription_array
                             $confirmed = 0
                             $approved = 0
                             $removed = 0
                             $subscription = null
                             $list_selected_output_format_array = array()
                             $status = 0
                             $is_removed = false()
                             $subscription_hash = ''
                             $td_counter = 0}

                        {if is_set($subscription_array[$list_id])}

                            {set $subscription = $subscription_array[$list_id]
                                 $list_selected_output_format_array = $subscription.output_format_array
                                 $confirmed = $subscription.confirmed
                                 $removed = $subscription.removed
                                 $approved = $subscription.approved
                                 $status = $subscription.status
                                 $is_removed = $subscription.is_removed
                                 $subscription_hash = $subscription.hash}

                        {/if}
                        {if $list_node.data_map.newsletter_list.content.output_format_array|count()|ne(0)}
                            <td>
                                {*<li>status: ({$status|wash}) - confirmed( {if $confirmed|ne(0)} {$confirmed|datetime( 'custom', '%j.%m.%Y %H:%i' )}{else} n/a {/if}) | approved({if $approved|ne(0)}  {$approved|datetime( 'custom', '%j.%m.%Y %H:%i' )} {else} n/a {/if}) | removed({if $removed|ne(0)}  {$removed|datetime( 'custom', '%j.%m.%Y %H:%i' )} {else} n/a {/if})<br>*}
                                <input type="hidden" name="Subscription_IdArray[]" value="{$list_id}" title="" />
                                <input type="checkbox" name="Subscription_ListArray[]" value="{$list_id}"{if and( $is_removed|not , is_set( $subscription_array[ $list_id ] ) )} checked="checked"{/if} title="{$list_node.data_map.title.content|wash}" /> {$list_node.data_map.title.content|wash}
                                {*$list_node.data_map.newsletter_list|attribute(show)*}
                            </td>
                                {if $list_node.data_map.newsletter_list.content.output_format_array|count()|gt(1)}

                                    {foreach $list_node.data_map.newsletter_list.content.output_format_array as $output_format_id => $output_format_name}
                            <td class="newsletter-list"><input type="radio" name="Subscription_OutputFormatArray_{$list_id}[]" value="{$output_format_id|wash}" {if is_set( $list_selected_output_format_array[ $output_format_id ] )} checked="checked"{/if} title="{$output_format_name|wash}" /> {$output_format_name|wash}</td>
                                    {set $td_counter = $td_counter|inc}
                                    {/foreach}

                                {else}

                                    {foreach $list_node.data_map.newsletter_list.content.output_format_array as $output_format_id => $output_format_name}
                            <td class="newsletter-list">&nbsp;<input type="hidden" name="Subscription_OutputFormatArray_{$list_id}[]" value="{$output_format_id|wash}" title="{$output_format_name|wash}" /></td>
                                    {set $td_counter = $td_counter|inc}
                                    {/foreach}

                                {/if}


                            {*if $subscription_hash|ne('')}
                                <a href={concat('newsletter/unsubscribe/', $subscription_hash )|ezurl()}>unsubscribeDirektLink</a>
                            {/if*}
                        {/if}
                            {* insert missing <td> *}
                            {while $td_counter|lt( $available_output_formats )}
                            <td>&nbsp;{*$td_counter} < {$available_output_formats*}</td>
                            {set $td_counter = $td_counter|inc}
                            {/while}
                        {undef $list_id $list_selected_output_format_array $subscription_array $subscription $confirmed $subscription $removed $approved $status $is_removed $subscription_hash $td_counter}
                        </tr>
                    {/foreach}
                    </table>
                </div>
                {/if}
                {undef $newsletter_list_node_list}

                {/foreach}

                {def $available_saluation_array = $newsletter_user.available_salutation_name_array}

                {* salutation *}
                <div class="block" id="nl-salutation">
                    <label>{"Salutation"|i18n( 'cjw_newsletter/configure' )}:</label>
                    {foreach $available_saluation_array as $saluation_id => $salutataion_name}
                        <input type="radio" name="Subscription_Salutation" value="{$saluation_id}"{if $newsletter_user.salutation|eq($saluation_id)} checked="checked"{/if} title="{$salutataion_name|wash}" />{$salutataion_name|wash}
                    {/foreach}
                </div>

                {* First name. *}
                <div class="block">
                    <label for="Subscription_FirstName">{"First name"|i18n( 'cjw_newsletter/configure' )}:</label>
                    <input class="halfbox" id="Subscription_FirstName" type="text" name="Subscription_FirstName" value="{$newsletter_user.first_name|wash}" title="{'First name of the subscriber.'|i18n( 'cjw_newsletter/configure' )}"{*cond( is_set( $user ), 'disabled="disabled"', '')*} />
                </div>

                {* Last name. *}
                <div class="block">
                    <label for="Subscription_LastName">{"Last name"|i18n( 'cjw_newsletter/configure' )}:</label>
                    <input class="halfbox" id="Subscription_LastName" type="text" name="Subscription_LastName" value="{$newsletter_user.last_name|wash}"{*cond( is_set( $user ), 'disabled="disabled"', '')*} />
                </div>

                {* Email. *}
                <div class="block">
                    <label for="Subscription_Email">{"Email"|i18n( 'cjw_newsletter/configure' )}*:</label>
                    <input class="halfbox" id="Subscription_Email" type="text" name="Subscription_Email" value="{$newsletter_user.email|wash}" title="{'Email of the subscriber.'|i18n( 'cjw_newsletter/configure' )}" {cond( is_set( $newsletter_user ), 'disabled="disabled"', '')} />
                </div>

                <div class="block">
                    <input class="button" type="submit" name="ConfirmButton" value="{'Confirm'|i18n( 'cjw_newsletter/configure' )}" title="{'Add to subscription.'|i18n( 'cjw_newsletter/configure' )}" />
                </div>

                <div class="block">
                {'* mandatory fields'|i18n( 'cjw_newsletter/configure' )}
                </div>
            </form>

        {/if}
        {undef $newsletter_available}
    {/if}
</div>