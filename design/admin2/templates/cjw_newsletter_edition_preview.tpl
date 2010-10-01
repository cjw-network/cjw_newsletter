{def $show_iframes = true()}

{if ezpreference( 'admin_navigation_content' )|eq(0)}
    {set $show_iframes = false()}
{/if}

<div class="mainobject-window">
    {* iframes anzeigen oder iconliste *}
    {include uri="design:includes/cjwnewsletteredition_preview.tpl" newsletter_edition_attribute=$newsletter_edition_attribute show_iframes=$show_iframes height="100" show_output_format_id=$show_output_format_id}
    <div class="break">
    </div>{* Terminate overflow bug fix *}
</div>
{undef $show_iframes}
{* links für vorschau einblenden *}
{*include uri="design:includes/cjwnewsletteredition_preview.tpl" newsletter_edition_attribute=$editionAttribute show_iframes=false()*}
{* DESIGN: Content END 1.DIV*}


