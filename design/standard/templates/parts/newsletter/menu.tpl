{* Newsletter related stuff *}
<div id="newsletter-menu">
{if $module_result.ui_context|ne('edit')}

    {include uri='design:parts/newsletter/newsletter_menu.tpl'}


    {* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
        <h4>{'Administer'|i18n( 'cjw_newsletter/menu' )}</h4>

    {* DESIGN: Header END *}</div></div></div></div></div></div>

    {* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">
        <ul>
            <li><a href={'/newsletter/user_list/'|ezurl}>{'Users'|i18n( 'cjw_newsletter/menu' )}{* User search*}</a></li>
            <li><a href={'/newsletter/blacklist_item_list'|ezurl}>{'Blacklists'|i18n( 'cjw_newsletter/menu' )}</a></li>
            <li><a href={'/newsletter/mailbox_item_list'|ezurl}>{'Bounces'|i18n( 'cjw_newsletter/menu' )}</a></li>
        </ul>

    {* DESIGN: Content END *}</div></div></div></div></div></div>

    {include uri='design:parts/newsletter/newsletter_menu_admin.tpl'}


{/if}
</div>

{* This is the border placed to the left for draging width, js will handle disabling the one above and enabling this *}
<div id="widthcontrol-handler" class="hide">
<div class="widthcontrol-grippy"></div>
</div>