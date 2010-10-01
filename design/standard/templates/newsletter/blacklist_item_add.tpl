{* Add a new blacklist item*}

{def $mailbox_id = 0}

{if is_set( $mailbox.id )}
    {set $mailbox_id = $mailbox.id }
{/if}

<div class="newsletter blacklist_item_add">

    {if $message|ne('')}
    <div class="message-warning">
        <h2>{$message|wash}</h2>
    </div>
    {/if}


    <form name="editform" id="editform" enctype="multipart/form-data" method="post" action={concat( '/newsletter/blacklist_item_add/', $mailbox_id )|ezurl}>
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
                                        {$mailbox.email|class_icon( normal, $mailbox.email )}&nbsp;{'Edit <%mailbox.email> '|i18n( 'cjw_newsletter/blacklist_item_add',, hash( '%mailbox.email', $mailbox.email ) )|wash}
                                    {else}
                                        {'Add a new Blacklist item '|i18n( 'cjw_newsletter/blacklist_item_add' )}
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
    {if $is_blacklist_done|not}
                    <div class="context-attributes">
                        <label>{'Email'|i18n( 'cjw_newsletter/blacklist_item_add' )}</label>
                        <input class="box" type="text" name="Email" value="{$blacklist_item.email}" />
                        <label>{'Note'|i18n( 'cjw_newsletter/blacklist_item_add' )}</label>
                        <textarea class="box" name="Note" cols="50" rows="10">{$blacklist_item.note|wash}</textarea>
                    </div>
    {else}
                    <div class="context-attributes">
                        <label>{'Email'|i18n( 'cjw_newsletter/blacklist_item_add' )}</label>
                        {$blacklist_item.email|wash}
                        <label>{'Note'|i18n( 'cjw_newsletter/blacklist_item_add' )}</label>
                        <p>{$blacklist_item.note|wash}</p>
                    </div>
    {/if}
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
                                    {if $is_blacklist_done|not}
                                        <input class="button" type="submit" name="AddButton" value="{'Add to Blacklist'|i18n( 'cjw_newsletter/blacklist_item_add' )}" title="" />
                                        <input class="button" type="submit" name="DiscardButton" value="{'Discard'|i18n( 'cjw_newsletter/blacklist_item_add' )}" />
                                    {else}
                                        <input class="button" type="submit" name="DiscardButton" value="{'Back'|i18n( 'cjw_newsletter/blacklist_item_add' )}" />
                                    {/if}
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

{*$blacklist_item|attribute(show)*}