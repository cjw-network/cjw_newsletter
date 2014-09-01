{* Newsletter - user_remove *}

{def $base_uri = concat( 'newsletter/user_remove/', $newsletter_user_id )}

<div class="newsletter newsletter-user_remove">

<form enctype="multipart/form-data" name="user_remove" method="post" action={$base_uri|ezurl}>

    <div class="context-block">
        {* DESIGN: Header START *}
        <div class="box-header">
            <div class="box-tc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tl">
                            <div class="box-tr">
                                <h1 class="context-title">{'Remove newsletter user'|i18n( 'cjw_newsletter/user_remove',, hash() )|wash}</h1>
                                {* DESIGN: Mainline *}
                                <div class="header-mainline">
                                </div>

                                {if is_set($warning)}
                                <div class="message-warning">
                                    <h2>{$warning|wash}</h2>
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

                        <div class="block">
                             <table class="list">
                                <tr>
                                    <th>
                                        {'Id'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>

                                    <th>
                                        {'Email'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>

                                    <th>
                                        {'Salutation'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>

                                    <th>
                                        {'First name'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>

                                    <th>
                                        {'Last name'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>

                                    <th>
                                        {'Status'|i18n( 'cjw_newsletter/user_view' )}
                                    </th>
                                    <th title="{'Subscription count'|i18n( 'cjw_newsletter/user_remove' )}">
                                        {'S'|i18n( 'cjw_newsletter/user_remove' )}
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        {$newsletter_user.id|wash}
                                    </td>
                                    <td>
                                        {$newsletter_user.email|wash}
                                    </td>
                                    <td title="{$newsletter_user.salutation|wash}">
                                        {$newsletter_user.salutation_name|wash}
                                    </td>
                                    <td>
                                        {$newsletter_user.first_name|wash}
                                    </td>
                                    <td>
                                        {$newsletter_user.last_name|wash}
                                    </td>
                                    <td title="{$newsletter_user.status|wash}">
                                        {$newsletter_user.status_string|wash}
                                    </td>
                                    <td>
                                        {$newsletter_user.subscription_array|count}
                                    </td>
                                </tr>
                            </table>
                        </div>


                        <div class="break">
                        </div>

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

                                                <input type="hidden" name="RedirectUrlActionRemove" value="{$redirect_url_action_remove}" />
                                                <input type="hidden" name="RedirectUrlActionCancel" value="{$redirect_url_action_cancel}" />

                                                <input class="button" type="submit" name="RemoveButton" value="{'Remove'|i18n( 'cjw_newsletter/user_remove' )}" title="{'Remove'|i18n( 'cjw_newsletter/user_remove' )}" />

                                                <input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'cjw_newsletter/user_remove' )}" title="{'Cancel'|i18n( 'cjw_newsletter/user_remove' )}" />

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


    </div>
</form>

</div>
