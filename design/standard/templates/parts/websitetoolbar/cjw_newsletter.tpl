{* icon for websitetoolbar to access newsletter index *}
{def $cjw_newsletter_access = fetch( 'user', 'has_access_to', hash( 'module', 'newsletter', 'function', 'index' ) )}
{if $cjw_newsletter_access}

    <a href={'newsletter/index'|ezurl} class="cjw_newsletter"><img class="ezwt-input-image" src={"websitetoolbar/ezwt-icon-cjw_newsletter.png"|ezimage} alt="{'Newsletter dashboard'|i18n( 'cjw_newsletter/index' )}" title="{'Newsletter dashboard'|i18n( 'cjw_newsletter/index' )}" border="0" /></a>
{/if}