<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'Edition State'|i18n( 'cjw_newsletter/cjw_newsletter_edition_status' )}</h2>

{* DESIGN: Subline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<div class="mainobject-window">
<div class="block">
    {*<p><label>{'State'|i18n( 'cjw_newsletter/cjw_newsletter_edition_status' )}Status:</label>*}
        {switch match=$newsletter_edition_status}
            {case match='draft'}
                <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_draft.png'|ezdesign} alt="{$newsletter_edition_status}" title="{$newsletter_edition_status}" />
                {'Status draft'|i18n( 'cjw_newsletter/cjw_newsletter_edition_status' )}
                {*'Send Newsletter'|i18n( 'cjw_newsletter/cjw_newsletter_edition_status' )*}{*Newsletter verschicken*}
            {/case}
            {case match='process'}
                <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_process.png'|ezdesign} alt="{$newsletter_edition_status}" title="{$newsletter_edition_status}" />
                {'Status process'|i18n( 'cjw_newsletter/cjw_newsletter_edition_status' )}
                {*'in the dispatch'|i18n( 'cjw_newsletter/cjw_newsletter_edition_status' )*}    {*im Versand*}
            {/case}
            {case match='archive'}
                <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_archive.png'|ezdesign} alt="{$newsletter_edition_status}" title="{$newsletter_edition_status}" />
                {'Status archive'|i18n( 'cjw_newsletter/cjw_newsletter_edition_status' )}
                {*'sends'|i18n( 'cjw_newsletter/cjw_newsletter_edition_status' )*}      {*verschickt*}
            {/case}
            {case match='abort'}
                <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_abort.png'|ezdesign} alt="{$newsletter_edition_status}" title="{$newsletter_edition_status}" />
                {*'uncompletedly'|i18n( 'cjw_newsletter/cjw_newsletter_edition_status' )*}      {*abgebrochen*}
                {'Status abort'|i18n( 'cjw_newsletter/cjw_newsletter_edition_status' )}
            {/case}
        {/switch}
        {*$editionStatus*}
    {*</p>
    <p><label>{'Operation'|i18n( 'cjw_newsletter/cjw_newsletter_edition_status' )}{Aktion}:</label> *}
        {*<a href={concat('newsletter/send/', $node.main_node_id )|ezurl()}>Details</a></p>*}


</div>

</div>

{*include uri="design:includes/cjwnewsletteredition_preview.tpl" newsletter_edition_attribute=$editionAttribute show_iframes=false()*}

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>
