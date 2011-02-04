{* design:newsletter/send.tpl *}

{if is_unset( $node_id )}
    {def $node_id = $object_version.contentobject.main_node_id}
{/if}

{def $newsletter_edition_attribute = $object_version.data_map.newsletter_edition
     $edition_attribute_content = $newsletter_edition_attribute.content
     $list_attribute_content = $newsletter_edition_attribute.content.list_attribute_content
     $email_receiver_test = $list_attribute_content.email_receiver_test}

{def $node_url = concat( 'content/view/full/', $node_id )
     $node_name = $object_version.contentobject.main_node.name}

<div class="newsletter newsletter-send">

    <div class="border-box">
    <div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
    <div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

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
                                <h1 class="context-title">{"Send out newsletter"|i18n("cjw_newsletter/send")} - {$node_name|wash}</h1>
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
                                {'Do you really want to send out this newsletter?'|i18n( 'cjw_newsletter/send.tpl' )}
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
                                        {* Edit *}
                                        <div class="left">

                                            <form action={'newsletter/send'|ezurl()}  method="post" style="display: inline;">
                                                <input type="hidden" name="TopLevelNode" value="{$node_id}" />
                                                <input type="hidden" name="ContentNodeID" value="{$node_id}" />
                                                <input type="hidden" name="ContentObjectID" value="{$node_id}" />
                                                <input type="hidden" name="mail_newsletter" value="true" />
                                                <input type="hidden" name="SendOutConfirmationInput" value="true" />
                                                <input class="defaultbutton" type="submit" name="SendNewsletterButton" value="{"Send Newsletter"|i18n("cjw_newsletter/send")}" />
                                            </form>

                                            <a href={$node_url|ezurl}><input type="button" class="button" value="{"Cancel"|i18n("cjw_newsletter/send")}" /></a>

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

    </div></div></div>
    <div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
    </div>

</div>

