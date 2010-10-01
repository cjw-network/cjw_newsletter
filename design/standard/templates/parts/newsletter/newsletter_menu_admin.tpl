{* admin function - only show if user has policy: supporttools admin*}

{def $has_admin_access = fetch( 'user', 'has_access_to',
                                hash( 'module', 'newsletter',
                                      'function', 'admin' ) )}
{if $has_admin_access}

    {* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
    <h4>{'Settings'|i18n( 'cjw_newsletter/menu' )}</h4>
            {* Sonstiges *}
    {* DESIGN: Header END *}</div></div></div></div></div></div>

    {* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

    <ul>
        <li><a href={'/newsletter/mailbox_list'|ezurl}>{'Mail accounts'|i18n( 'cjw_newsletter/menu' )}</a></li>
        <li><a href={'/newsletter/import_list'|ezurl}>{'Imports'|i18n( 'cjw_newsletter/menu' )}</a></li>
        <li><a href={'/newsletter/subscribe/'|ezurl}>{'Subscription form'|i18n( 'cjw_newsletter/menu' )}{*Newsletter Anmeldeformular*}</a></li>
        <li><a href={'/newsletter/settings'|ezurl}>{'INI Settings'|i18n( 'cjw_newsletter/menu' )}{*Settings*}</a></li>
    </ul>

    {* DESIGN: Content END *}</div></div></div></div></div></div>
{/if}