{*  newsletter/user_create.tpl

    create a newsletter user
*}

<div class="newsletter newsletter-user_create">

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

<form action={'newsletter/user_create/'|ezurl} method="post">

<input type="hidden" name="RedirectUrlActionCancel" value="{$redirect_url_action_cancel}" />
<input type="hidden" name="RedirectUrlActionStore" value="{$redirect_url_action_store}" />

<input type="hidden" name="OldPostVarSerialized" value="{$old_post_var_serialized}" />

    <div class="context-block">
        {* DESIGN: Header START *}
        <div class="box-header">
            <div class="box-tc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tl">
                            <div class="box-tr">
                                <h1 class="context-title">{'Create a new newsletter user'|i18n( 'cjw_newsletter/user_create',, hash() )|wash}</h1>
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

                            <label>
                                {'Email'|i18n( 'cjw_newsletter/user_view' )}
                            </label>

                            <input  class="halfbox" type="text" name="Subscription_Email" value="{$subscription_data_array['email']|wash}">
                        </div>
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
                                        <input class="button" type="submit" name="CreateEditButton" value="{'Create and edit'|i18n( 'cjw_newsletter/user_create' )}" />
                                        <input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'cjw_newsletter/user_create' )}" />
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


