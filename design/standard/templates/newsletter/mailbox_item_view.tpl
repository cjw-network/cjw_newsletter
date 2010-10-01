{*  newsletter/mailbox_item_view.tpl

full view of an mailbox item
*}
<div class="newsletter newsletter-mailbox_item_view">
    <div class="context-block">
        {* DESIGN: Header DB Vars START *}
        <div class="box-header">
            <div class="box-tc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tl">
                            <div class="box-tr">
                                <h1 class="context-title">{$mailbox_item.id|wash} [{'Mailbox Item Database Infos'|i18n( 'cjw_newsletter/mailbox_item_view',, hash() )|wash}]</h1>
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
                                        id
                                    </th>
                                    <td>
                                        {$mailbox_item.id|wash}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        mailbox_id
                                    </th>
                                    <td>
                                        {$mailbox_item.mailbox_id|wash}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        message_id
                                    </th>
                                    <td>
                                        {$mailbox_item.message_id|wash}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        message_identifier
                                    </th>
                                    <td>
                                        {$mailbox_item.message_identifier|wash}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        message_size
                                    </th>
                                    <td>
                                        {$mailbox_item.message_size|si( 'byte', 'kilo' )}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        created
                                    </th>
                                    <td>
                                        {$mailbox_item.created|l10n( shortdatetime )}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        processed
                                    </th>
                                    <td>
                                        {if $mailbox_item.processed|ne(0)}
                                            {$mailbox_item.processed|l10n( shortdatetime )}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        bounce_code
                                    </th>
                                    <td>
                                        {$mailbox_item.bounce_code|wash}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        email_from
                                    </th>
                                    <td>
                                        {$mailbox_item.email_from|wash}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        email_to
                                    </th>
                                    <td>
                                        {$mailbox_item.email_to|wash}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        email_subject
                                    </th>
                                    <td>
                                        {$mailbox_item.email_subject|wash}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        email_send_date
                                    </th>
                                    <td>
                                        {if $mailbox_item.email_send_date|ne(0)}
                                            {$mailbox_item.email_send_date|l10n( shortdatetime )}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        edition_send_id
                                    </th>
                                    <td>
                                        {if $mailbox_item.edition_send_id|ne(0)}
                                            <a href={concat( 'newsletter/preview_archive/', $mailbox_item.edition_send_id)|ezurl}>{$mailbox_item.edition_send_id|wash}</a>
                                        {else}
                                            {$mailbox_item.edition_send_id|wash}
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        edition_send_item_id
                                    </th>
                                    <td>
                                        {$mailbox_item.edition_send_item_id|wash}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        newsletter_user_id
                                    </th>
                                    <td>
                                    {if eq( $mailbox_item.newsletter_user_id, 0 )}
                                        -
                                    {else}
                                        <a href={concat( 'newsletter/user_view/', $mailbox_item.newsletter_user_id)|ezurl}>{$mailbox_item.newsletter_user_id|wash}</a>
                                    {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        is_bounce
                                    </th>
                                    <td>
                                    {if $mailbox_item.is_bounce|eq(true())}x{else}-{/if}

                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        is_system_bounce
                                    </th>
                                    {if $mailbox_item.is_system_bounce|eq(true())}
                                        <td style="background-color:#FFF6BF;">
                                        x
                                        </td>
                                    {else}
                                       <td>
                                        -
                                        </td>
                                    {/if}
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

    {* =============  object header vars ============ *}
    <div class="context-block">

    {* DESIGN: Header START *}
    <div class="box-header">
        <div class="box-tc">
            <div class="box-ml">
                <div class="box-mr">
                    <div class="box-tl">
                        <div class="box-tr">
                            <h2 class="context-title">{'LIVE mailbox item parse infos'|i18n( 'cjw_newsletter/mailbox_item_view',, hash( ) )}</h2>
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
                        <table class="list">
                            {foreach $mailbox_header_hash as $header_key => $header_value}
                            <tr>
                                <th>
                                    {$header_key|wash}
                                </th>
                                <td>
                                    {$header_value|wash}
                                </td>
                            </tr>
                            {/foreach}
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

                                </div>
                            </div>{* DESIGN: Control bar END *}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    {* =============  raw content ============ *}
    <div class="context-block">

    {* DESIGN: Header START *}
    <div class="box-header">
        <div class="box-tc">
            <div class="box-ml">
                <div class="box-mr">
                    <div class="box-tl">
                        <div class="box-tr">
                            <h2 class="context-title">{'Raw mail content'|i18n( 'cjw_newsletter/mailbox_item_view',, hash( ) )}</h2>
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
                        <p>{$mailbox_item.file_path}</p>

                        <a href="?GetRawMailContent" target="_blank">{'Full View'|i18n( 'cjw_newsletter/mailbox_item_view',, hash( ) )}</a>
                        <a href="?DownloadRawMailContent">{'Download'|i18n( 'cjw_newsletter/mailbox_item_view',, hash( ) )}</a>
                        <hr />

                        {if ezpreference( 'admin_mailbox_item_view_raw_iframe' )|eq(0)}
                            <a href={'/user/preferences/set/admin_mailbox_item_view_raw_iframe/1'|ezurl}  title="{'Show raw mail content inline.'|i18n( 'cjw_newsletter/mailbox_item_view' )}">{'Show inline'|i18n( 'cjw_newsletter/mailbox_item_view' )}</a>
                        {else}
                            <a href={'/user/preferences/set/admin_mailbox_item_view_raw_iframe/0'|ezurl}  title="{'Hide raw mail content inline.'|i18n( 'cjw_newsletter/mailbox_item_view' )}">{'Hide inline'|i18n( 'cjw_newsletter/mailbox_item_view' )}</a>
                            <br /><br />
                            <iframe width="100%" height="300" frameborder="0" name="RawMailConent" src="?GetRawMailContent" marginwidth="0" marginheight="0" />
                        {/if}



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
                                <div class="box-br"></div>

                            </div>{* DESIGN: Control bar END *}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>{* =============  list of all send items ============ *}

</div> {*$newsletter_user|attribute(show)*}