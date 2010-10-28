{* Newsletter - subscription_list_csvimport *}

{*
newsletter/import_list.tpl
list all blacklist items
*}

{def $limit = 50
     $base_uri = concat( 'newsletter/subscription_list_csvimport/', $list_node.node_id, '/', $import_id )
     $items_count = $csv_data_array|count
     $list_output_format_array = $list_node.data_map.newsletter_list.content.output_format_array}


{if ezpreference( 'admin_import_list_limit' )}
    {switch match=ezpreference( 'admin_import_list_limit' )}
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

<div class="newsletter newsletter-subscription_list_csvimport">

<form enctype="multipart/form-data" name="subscription_csvimport" method="post" action={$base_uri|ezurl}>


    <div class="context-block">
        {* DESIGN: Header START *}
        <div class="box-header">
            <div class="box-tc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tl">
                            <div class="box-tr">
                                <h1 class="context-title">{'Subscription CSV import'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}</h1>
                                {* DESIGN: Mainline *}
                                <div class="header-mainline">
                                </div>

                                {if is_set($warning)}
                                <div class="message-warning">
                                    <h2>{$warning|wash}</h2>
                                </div>
                                {elseif is_set( $view_parameters.error )}
                                <div class="message-warning">
                                    <h2>
                                        {switch match=$view_parameters.error}
                                            {case match='CSV_DELIM_ERROR'}
                                                {'Unsupported CSV delimiter. Please use one of the following: ",", ";", "|"'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}
                                            {/case}
                                        {/switch}
                                    </h2>
                                </div>
                                {/if}

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

                        </div>

{if $import_id|eq(0)}
                            <label>
                                {"Upload file"|i18n( 'cjw_newsletter/subscription_list_csvimport' )}:
                            </label>
                            <input type="hidden" name="MAX_FILE_SIZE" value="0" /><input name="UploadCsvFile" type="file" /><input type="submit" value="{'Upload file'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}" />
{else}


                                {if is_object( $import_object )}
                                <label>
                                <a href={concat( 'newsletter/import_view/', $import_object.id )|ezurl()}>{'Import   Id'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}: {$import_object.id|wash}  </a>
                                </label>

                                     {if $import_object.is_imported}
                                         <p>{'Import done'|i18n( 'cjw_newsletter/subscription_list_csvimport' )} {$import_object.created|l10n( shortdatetime )|wash}</p>
                                     {/if}
                                {/if}

                            <div class="block">
                                <label>
                                    {"Csv File Uploaded"|i18n( 'cjw_newsletter/subscription_list_csvimport' )}:
                                </label>
                                {*<input name="CsvFilePath" type="hidden" value="{$csv_file_path}" />*}{$csv_file_path|wash}
                            </div>
{/if}
                        <div class="break">
                        </div>

                            <input type="checkbox" name="FirstRowIsLabel" {if $first_row_is_label|eq(true())}checked="checked"{/if} />
                            {'First row is label'|i18n( 'cjw_newsletter/subscription_list_csvimport')}

                                <pre>email;first_name;last_name;salutation
user3@example.com;Julia;Mustermann;2
user4@example.com;Max;Mustermann;1</pre>{* Output format. *}

                            {* Output format. *}
                            <div class="block">
                                <label>
                                    {"Output format"|i18n( 'cjw_newsletter/subscription_list_csvimport' )}:
                                </label>
                                {foreach $list_output_format_array as $outputformat_id => $outputformat_name}
                                <input type="radio" name="SelectedOutputFormatArray[]" value="{$outputformat_id|wash}" {cond( $selected_output_format_array|contains( $outputformat_id ),  'checked',  '')} />
                                {$outputformat_name|wash}
                                {/foreach}
                            </div>
                            <div class="block">
                                <label>
                                    {'CSV field delimiter'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}: <input style="text-align:center" type="text" size="1" maxlength="1" name="CsvDelimiter" value="{$csv_delimiter}" /> {'(supported CSV delimiters: ",", ";", "|")'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}
                                </label>
                            </div>
                            {if $import_object.is_imported|not}
                            <div class="block">
                                <label>
                                    {"Import note"|i18n( 'cjw_newsletter/subscription_list_csvimport' )}:
                                </label>
                                <textarea class="box" name="Note">{$note|wash}</textarea>
                            </div>

                            {else}
                                <div class="block">
                                <label>
                                    {"Import note"|i18n( 'cjw_newsletter/subscription_list_csvimport' )}:
                                </label>
                                <p>{$note|wash}</p>
                            </div>
                            {/if}

                    </div>
                    {* DESIGN: Content END *}
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
                                            {if $import_object.is_imported|not}
                                                <input class="button" type="submit" name="Update" value="{'Update'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}" title="{'Update'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}" />
                                                <input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}" title="{'Cancel subscription import.'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}" />
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

    <div class="context-block">
        {* DESIGN: Header START *}
        <div class="box-header">
            <div class="box-tc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tl">
                            <div class="box-tr">
                                <h2 class="context-title">{'Imported items'|i18n( 'cjw_newsletter/subscription_list_csvimport' )} [{$items_count}]</h2>
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
                                        <a href={'/user/preferences/set/admin_import_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10</a>
                                        <span class="current">25</span>
                                        <a href={'/user/preferences/set/admin_import_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50</a>
                                    {/case}
                                    {case match=50}
                                        <a href={'/user/preferences/set/admin_import_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10</a>
                                        <a href={'/user/preferences/set/admin_import_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25</a>
                                        <span class="current">50</span>
                                    {/case}
                                    {case}
                                        <span class="current">10</span>
                                        <a href={'/user/preferences/set/admin_import_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25</a>
                                        <a href={'/user/preferences/set/admin_import_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50</a>
                                    {/case}
                                {/switch}
                                </p>
                            </div>
                        </div>
                        <div class="break float-break">
                        </div>


                        <div class="content-navigation-childlist overflow-table">
                                {* Subscription import table. *}
                                <table class="list" cellspacing="0">
                                    <tr>
                                        {*
                                        <th class="tight">
                                            <img src={'toggle-button-16x16.gif'|ezimage}  alt="{'Invert selection'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}" title="{'Invert selection'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}" onclick="ezjs_toggleCheckboxes( document.subscription_import, 'RowNum[]' ); return false;" />
                                        </th>*}
                                        <th class="tight">
                                            {'Row nr'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}
                                        </th>
                                        <th class="tight">
                                            {'Email'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}
                                        </th>
                                        <th class="tight">
                                            {'First name'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}
                                        </th>
                                        <th class="tight">
                                            {'Last name'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}
                                        </th>
                                        <th class="tight">
                                            {'Salutation'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}
                                        </th>
                                        <th class="tight">
                                            {'Email ok'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}
                                        </th>
                                        <th class="tight">
                                            {'Nl user created'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}
                                        </th>
                                        <th class="tight">
                                            {'Subscription created'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}
                                        </th>
                                        <th class="tight">
                                            {'Created / modified'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}
                                        </th>
                                    </tr>
                                    {foreach $csv_data_array as $row => $data_set max $limit offset $view_parameters.offset}
                                        {def $email_ok = '-'
                                             $user_created = '-'
                                             $subscription_created = '-'
                                             $subscription_object = false()
                                             $user_object = false()
                                             $user_status_old = '-'
                                             $user_status_new = '-'
                                             $subscription_status_old = '-'
                                             $subscription_status_new = '-'
                                             $newsletter_user_id = 0}
                                        {if is_set($list_subscription_array[$row])}
                                        {set $email_ok = $list_subscription_array[$row].email_ok
                                             $user_created = $list_subscription_array[$row].user_created
                                             $subscription_created = $list_subscription_array[$row].subscription_created
                                             $user_status_old = $list_subscription_array[$row].user_status_old
                                             $user_status_new = $list_subscription_array[$row].user_status_new
                                             $subscription_status_old = $list_subscription_array[$row].subscription_status_old
                                             $subscription_status_new = $list_subscription_array[$row].subscription_status_new
                                             $newsletter_user_id = $list_subscription_array[$row].newsletter_user_id
                                            }
                                            {if is_set( $list_subscription_array[$row]['subscription_object'] )}
                                                {set $subscription_object = $list_subscription_array[$row].subscription_object}
                                            {/if}
                                            {if is_object( $subscription_object )}
                                                {set $user_object = $subscription_object.newsletter_user}
                                            {/if}
                                        {/if}
                                        <tr>
                                            {*
                                            <td>
                                            </td>*}
                                            <td>
                                                {$row|wash}
                                            </td>
                                            <td>
                                                {if $newsletter_user_id|gt(0)}
                                                    <a href={concat( 'newsletter/user_view/', $newsletter_user_id )|ezurl} title="{$newsletter_user_id|wash}{if is_object( $user_object )} - {$user_object.name|wash}{/if}" >{$data_set.email|wash}</a>
                                                {else}
                                                    {$data_set.email|wash}
                                                {/if}
                                            </td>
                                            <td>
                                                {$data_set.first_name|wash}
                                                {*if is_object( $user_object )}
                                                    -&gt; {$user_object.first_name|wash}
                                                {/if*}
                                            </td>
                                            <td>
                                                {$data_set.last_name|wash}
                                                {*if is_object( $user_object )}
                                                    -&gt; {$user_object.last_name|wash}
                                                {/if*}
                                            </td>
                                            <td title="{if is_object($user_object)}{$user_object.salutation|wash}{$user_object.salutation_name|wash}{/if}">
                                                {$data_set.salutation|wash}
                                                {if is_object($user_object)}
                                                    -&gt; {$user_object.salutation|wash}
                                                {/if}

                                            </td>
                                            <td title="{$email_ok|wash}">
                                                {if $email_ok|eq('1')}
                                                   {'yes'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}
                                                {elseif $email_ok|eq('0')}
                                                   {'no'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}
                                                {else}
                                                    {$email_ok|wash}
                                                {/if}
                                            </td>
                                            <td title="status old: {$user_status_old|wash} -new: {$user_status_new|wash}">
                                                {if $user_created|eq('1')}
                                                    <b>{'created'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}</b>
                                                {elseif $user_created|eq('2')}
                                                    <b>{'updated'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}</b>
                                                {elseif $user_created|eq('0')}
                                                    <b>{'no'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}</b>
                                                    {if $subscription_status_old|eq(3)}
                                                        {'Removed by user'|i18n( 'cjw_newsletter/subscription/status' )}
                                                    {elseif $subscription_status_old|eq(8)}
                                                        {'Blacklisted'|i18n( 'cjw_newsletter/subscription/status' )}
                                                    {/if}
                                                {else}
                                                    {$user_created|wash}
                                                {/if}
                                            </td>
                                            <td title="status old: {$subscription_status_old|wash} - new: {$subscription_status_new|wash}">
                                                {if $subscription_created|eq('1')}
                                                    {'created'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}
                                                {elseif $subscription_created|eq('2')}
                                                    <b>{'updated'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}</b>
                                                {elseif $subscription_created|eq('0')}
                                                    <b>{'no'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}</b>
                                                    {if $subscription_status_old|eq(3)}
                                                        {'Removed by user'|i18n( 'cjw_newsletter/subscription/status' )}
                                                    {elseif $subscription_status_old|eq(8)}
                                                        {'Blacklisted'|i18n( 'cjw_newsletter/subscription/status' )}
                                                    {/if}
                                                {else}
                                                    {$subscription_created|wash}
                                                {/if}

                                            </td>
                                            <td>
                                                {if is_object($subscription_object)}{$subscription_object.modified|datetime( 'custom', "%Y%m%d-%H:%i:%s" )}{/if}
                                            </td>
                                        </tr>
                                        {undef $email_ok
                                               $user_created
                                               $subscription_created
                                               $subscription_object
                                               $user_object
                                               $subscription_status_old
                                               $subscription_status_new
                                               $user_status_old
                                               $user_status_new
                                               $newsletter_user_id}
                                    {/foreach}
                                </table>
                            </div>
                            {* Navigator. *}
                            <div class="context-toolbar subitems-context-toolbar">
                                {include name='Navigator'
                                        uri='design:navigator/google.tpl'
                                        page_uri=$base_uri
                                        item_count=$items_count
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
                                        {if and( is_object($import_object),
                                                 $import_object.is_imported|not )}
                                        <input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}" title="{'Cancel subscription import.'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}" />
                                        {def $access=fetch( 'user', 'has_access_to',
                                                                                hash( 'module',   'newsletter',
                                                                                      'function', 'subscription_list_csvimport_import' ) )}
                                            {if $access }
                                            {* Access is allowed. *}<input class="button" type="submit" name="ImportButton" value="{'Import all'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}" title="{'Import all'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}" />
                                            {else}
                                            {* Access is denied. *}<input class="disabled" type="button" value="{'Import all'|i18n( 'cjw_newsletter/subscription_list_csvimport' )}" title="{'Import all'|i18n( 'cjw_newsletter/subscription_list_csvimport' )} - disabled" />
                                            {/if}
                                        {/if}
                                    </div>
                                </div>{* DESIGN: Control bar END *}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>{* DESIGN: Content END *}
    </div>
</form>

</div>
