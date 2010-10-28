{* send_newsletter_test_result.tpl *}

{def $newsletter_edition_attribute = $object_version.data_map.newsletter_edition
     $edition_attribute_content = $newsletter_edition_attribute.content
     $list_attribute_content = $newsletter_edition_attribute.content.list_attribute_content
     $email_receiver_test = $list_attribute_content.email_receiver_test}

{if is_unset( $node_id )}
    {def $node_id = $object_version.contentobject.main_node_id}
{/if}

{def $node_url = concat( 'content/view/full/', $node_id )
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
                                <h1 class="context-title">{"Test newsletter sent result"|i18n("cjw_newsletter/send")}: {$node_name|wash}</h1>
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


                            {if is_set( $email_test_send_result_array )}

                                {* Newsletter test sent result *}
                                <table class="list">
                                    <tr>
                                        <th>{"Nr"|i18n("cjw_newsletter/send")}</th>

                                        {*<th>{"Result"|i18n("cjw_newsletter/send")}</th>*}
                                        <th>{"Subject"|i18n("cjw_newsletter/send")}</th>
                                        <th>{"Email Sender"|i18n("cjw_newsletter/send")}<br />{"Email Receiver"|i18n("cjw_newsletter/send")}</th>

                                        <th>{"Content Type"|i18n("cjw_newsletter/send")}<br />{"Charset"|i18n("cjw_newsletter/send")}<br />{"Transport"|i18n("cjw_newsletter/send")}</th>
                                    </tr>
                                    {def $i=1}
                                    {foreach $email_test_send_result_array as $result sequence array( 'bglight','bgdark' ) as $style}
                                        <tr class="{$style}">
                                            <td>{$i}.</td>

                                            {*<td>{$result.email_result|cond('failed','ok')}</td>*}
                                            <td>{$result.email_subject|wash}</td>
                                            <td>{$result.email_sender|wash}<br />{$result.email_receiver|wash}</td>
                                            <td><b>{$result.email_content_type|wash}</b><br />{$result.email_charset|wash}<br />{$result.transport_method|wash}</td>

                                        </tr>
                                        {set $i=inc($i)}
                                    {/foreach}
                                    {undef $i}
                                </table>
                            {/if}

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

                                        <div class="left">

                                            <a href={$node_url|ezurl}><input type="button" class="button" value="{"Back"|i18n("cjw_newsletter/send")}" /></a>

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

