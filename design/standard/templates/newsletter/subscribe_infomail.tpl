{* subsribe_infomail -
 template with email input field to request the configure link

*}

<div class="newsletter newsletter-subscribe_infomail">
<h1>{'Newsletter - Edit profile'|i18n( 'cjw_newsletter/subscribe_infomail' )}</h1>
    {* warnings *}
    {if and( is_set( $warning_array ), $warning_array|count|ne( 0 ) )}
    <div class="block">
        <div class="message-warning">
            <h2>{'Input did not validate'|i18n('cjw_newsletter/subscribe_infomail')}</h2>
            <ul>
            {foreach $warning_array as $index => $messageArrayItem}
                <li><span class="key">{$messageArrayItem.field_key|wash}: </span><span class="text">{$messageArrayItem.message|wash()}</span></li>
            {/foreach}
            </ul>
        </div>
    </div>
    {/if}

    <p>
        {'Enter the e-mail address you originally used to subscribe and you will be sent a link to edit you data.'|i18n( 'cjw_newsletter/subscribe_infomail' )}
    </p>

    <form action={'newsletter/subscribe_infomail'|ezurl()} method="post">
        <label for="EmailInput">{'E-mail'|i18n( 'cjw_newsletter/subscribe_infomail' )}*:</label>
        <input class="input" type="text" id="EmailInput" name="EmailInput" size="50" />
        <br/>
        <input class="button" type="submit" value="{'Send'|i18n( 'cjw_newsletter/subscribe_infomail' )}" name="SubscribeInfoMailButton" />
        <input type="hidden" name="BackUrlInput" value={'newsletter/subscribe'|ezurl()} />
    </form>
    <div class="block">
    {'* mandatory fields'|i18n( 'cjw_newsletter/subscribe_infomail' )}
    </div>
</div>