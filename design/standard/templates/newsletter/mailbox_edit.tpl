{* Edit or add mailboxes *}

{def $mailbox_id = 0}

{if is_set( $mailbox.id )}
    {set $mailbox_id = $mailbox.id }
{/if}

<div class="newsletter newsletter-mailbox_edit">
    <form name="editform" id="editform" enctype="multipart/form-data" method="post" action={concat( '/newsletter/mailbox_edit/', $mailbox_id )|ezurl}>

        <div class="context-block">
            {* DESIGN: Header START *}
            <div class="box-header">
                <div class="box-tc">
                    <div class="box-ml">
                        <div class="box-mr">
                            <div class="box-tl">
                                <div class="box-tr">
                                    <h1 class="context-title">
                                        {if is_set( $mailbox.email )}
                                            {$mailbox.email|class_icon( normal, $mailbox.email )}&nbsp;{'Edit <%mailbox.email> '|i18n( 'cjw_newsletter/mailbox_edit',, hash( '%mailbox.email', $mailbox.email ) )|wash}
                                        {else}
                                            {'Add new mail account'|i18n( 'cjw_newsletter/mailbox_edit' )}
                                        {/if}
                                    </h1>

                                    {* DESIGN: Mainline *}
                                    <div class="header-mainline"></div>

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
                            <label>{'Email'|i18n( 'cjw_newsletter/mailbox_edit' )}</label>
                            <input type="text" name="email" value="{$mailbox.email}" />
                            <label>{'Server'|i18n( 'cjw_newsletter/mailbox_edit' )}</label>
                            <input type="text" name="server" value="{$mailbox.server}" />
                            <label>{'Port'|i18n( 'cjw_newsletter/mailbox_edit' )}</label>
                            <input type="text" name="port" value="{$mailbox.port}" />
                            <label>{'User'|i18n( 'cjw_newsletter/mailbox_edit' )}</label>
                            <input type="text" name="user" value="{$mailbox.user}" />
                            <label>{'Password'|i18n( 'cjw_newsletter/mailbox_edit' )}</label>
                            <input type="password" name="password" value="{$mailbox.password}" />
                            <label>{'Type'|i18n( 'cjw_newsletter/mailbox_edit' )}</label>
                            <p>
                                <input type="radio" name="type" value="imap" {if or( eq( $mailbox.type, 'imap' ), eq( $mailbox.type, 0 ) )}checked{/if}> IMAP
                                <input type="radio" name="type" value="pop3" {if eq( $mailbox.type, 'pop3' )}checked{/if}> POP3
                            </p>
                            <label>{'SSL'|i18n( 'cjw_newsletter/mailbox_edit' )}</label>
                            <p>
                                <input type="radio" name="is_ssl" value="1" {if eq( $mailbox.is_ssl, 1 )}checked{/if}> True
                                <input type="radio" name="is_ssl" value="0" {if eq( $mailbox.is_ssl, 0 )}checked{/if}> False
                            </p>
                            <label>{'Delete mails from server'|i18n( 'cjw_newsletter/mailbox_edit' )}</label>
                            <p>
                                <input type="radio" name="delete_mails_from_server" value="1" {if eq( $mailbox.delete_mails_from_server, 1 )}checked{/if}> True
                                <input type="radio" name="delete_mails_from_server" value="0" {if eq( $mailbox.delete_mails_from_server, 0 )}checked{/if}> False
                            </p>
                            <label>{'Active'|i18n( 'cjw_newsletter/mailbox_edit' )}</label>
                            <p>
                                <input type="radio" name="is_activated" value="1" {if or( eq( $mailbox.is_activated, '1' ), eq( $mailbox.is_activated, 0 ) )}checked{/if}> True
                                <input type="radio" name="is_activated" value="0" {if eq( $mailbox.is_activated, '0' )}checked{/if}> False
                            </p>
                        </div>
                        {* DESIGN: Content END *}
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
                                        <div class="block">
                                            <input class="button" type="submit" name="PublishButton" value="{'Store Changes'|i18n( 'cjw_newsletter/mailbox_edit' )}" />
                                            <input type="hidden" name="edit" value="true" />
                                            <input type="hidden" name="redirect" value="{$view_parameters.redirect}" />
                                            <input class="button" type="submit" name="DiscardButton" value="{'Discard'|i18n( 'cjw_newsletter/mailbox_edit' )}" />
                                        </div>
                                        {* DESIGN: Control bar END *}
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