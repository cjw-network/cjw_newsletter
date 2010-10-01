{* design:newsletter/send.tpl *}

{def $newsletter_edition_attribute = $object_version.data_map.newsletter_edition
     $edition_attribute_content = $newsletter_edition_attribute.content
     $list_attribute_content = $newsletter_edition_attribute.content.list_attribute_content
     $email_receiver_test = $list_attribute_content.email_receiver_test}

{def $node_id = $object_version.contentobject.main_node_id
     $node_url = concat( 'content/view/full/', $node_id )
     $node_name = $object_version.contentobject.main_node.name}

<div class="newsletter newsletter-send">

    {if and( is_set( $message_warning ), $message_warning|ne('') )}
    <div class="message-warning">
        <h2>{$message_warning|wash}</h2>
    </div>
    {/if}
    {if and( is_set( $message_feedback ), $message_feedback|ne('') )}
    <div class="message-feedback">
        <h2>{$message_feedback|wash}</h2>
    </div>
    {/if}

    <div class="context-block">
        {* DESIGN: Header START *}
        <div class="box-header">
            <div class="box-tc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tl">
                            <div class="box-tr">
                                <h1 class="context-title">{"Abort newsletter send out process"|i18n( 'cjw_newsletter/send_abort' )}</h1>
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

                            {if $has_message|not}
                                 {'Do you really want to abort the send out process?'|i18n( 'cjw_newsletter/send_abort.tpl' )}
                            {/if}

                        </div>

                        <div class="block">

                        <h2 class="context-title">{'Statistics'|i18n( 'cjw_newsletter/cjw_newsletter_edition_send_statistic' )}</h2>
                         <table class="list" cellspacing="0">
                                <tr>
                                    <th>{'Id'|i18n( 'cjw_newsletter/cjw_newsletter_edition_send_statistic' )}</th>
                                    <th>{'Emails count'|i18n( 'cjw_newsletter/cjw_newsletter_edition_send_statistic' )}</th>
                                    <th>{'Emails sent'|i18n( 'cjw_newsletter/cjw_newsletter_edition_send_statistic' )}</th>
                                    <th>{'Emails opened'|i18n( 'cjw_newsletter/cjw_newsletter_edition_send_statistic' )}</th>
                                    <th>{'Emails not sent'|i18n( 'cjw_newsletter/cjw_newsletter_edition_send_statistic' )}</th>
                                    <th>{'Emails bounced'|i18n( 'cjw_newsletter/cjw_newsletter_edition_send_statistic' )}</th>
                                  {*  <th>{'Creator'|i18n( 'cjw_newsletter/cjw_newsletter_edition_send_statistic' )}</th>*}
                                </tr>
                                 <tr>
                                    <td>{$edition_send_object.id}</td>
                                    <td>{$edition_send_object.send_items_statistic.items_count}</td>
                                    <td>{$edition_send_object.send_items_statistic.items_send}</td>
                                    <td>&nbsp;</td>
                                    <td>{$edition_send_object.send_items_statistic.items_not_send}</td>
                                    <td>{$edition_send_object.send_items_statistic.items_bounced}</td>
                                 {*   <td>{$edition_send_object.creator_id}</td>*}
                                </tr>

                                </table>

                        </div>

 <div class="block">
                        <table class="list" cellspacing="0">
                        <tr>
                        <th width="99%">{'Cronjob status'|i18n( 'cjw_newsletter/cjw_newsletter_edition_send_statistic' )}</th>

                        </tr>

                        <tr>
                        <td nowrap>
                            <ul>
                            <li>{if $edition_send_object.status|eq(0)}<b>{/if}0 - wait_for_process ( {$edition_send_object.created|l10n( shortdatetime )} ){if $edition_send_object.status|eq(0)}</b>{/if}</li>
                            <li>{if $edition_send_object.status|eq(1)}<b>{/if}1 - mailqueue_created ( {cond( $edition_send_object.mailqueue_created|eq(0), '-',  $edition_send_object.mailqueue_created|l10n( shortdatetime ) )} ){if $edition_send_object.status|eq(1)}</b>{/if}</li>
                            <li>{if $edition_send_object.status|eq(2)}<b>{/if}2 - mailqueue_process_started ( {cond( $edition_send_object.mailqueue_process_started|eq(0), '-',  $edition_send_object.mailqueue_process_started|l10n( shortdatetime ) )} ){if $edition_send_object.status|eq(2)}</b>{/if}</li>
                            <li>{if $edition_send_object.status|eq(3)}<b>{/if}3 - mailqueue_process_finished ( {cond( $edition_send_object.mailqueue_process_finished|eq(0), '-',  $edition_send_object.mailqueue_process_finished|l10n( shortdatetime ) )} ){if $edition_send_object.status|eq(3)}</b>{/if}</li>
                            <li>{if $edition_send_object.status|eq(9)}<b>{/if}9 - mailqueue_process_aborted ( {cond( $edition_send_object.mailqueue_process_aborted|eq(0), '-',  $edition_send_object.mailqueue_process_aborted|l10n( shortdatetime ) )} ){if $edition_send_object.status|eq(4)}</b>{/if}</li>
                            </ul>
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

                                            <form action={concat( 'newsletter/send_abort/', $edition_send_id)|ezurl()}  method="post" style="display: inline;">
                                                {if $has_message|not}
                                                <input class="defaultbutton" type="submit" name="AbortSendOutButton" value="{"Abort send out process"|i18n("cjw_newsletter/send_abort")}" />
                                                <input class="button" type="submit" name="CancelButton" value="{"Cancel"|i18n("cjw_newsletter/send_abort")}" />

                                                {else}
                                                <input class="button" type="submit" name="CancelButton" value="{"Back"|i18n("cjw_newsletter/send_abort")}" />
                                                {/if}
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
    </div>

</div>
