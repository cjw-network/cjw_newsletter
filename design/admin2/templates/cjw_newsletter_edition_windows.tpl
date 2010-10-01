{* Preview window *}
{if $preview_enabled}

{* archive view *}



{if $newsletter_edition_status|ne('draft')}

    {foreach $output_format_array as $output_format_id => $output_format_name}
        <div id="node-tab-preview_{$output_format_id}-content" class="tab-content{if $node_tab_index|ne( concat( 'preview_', $output_format_id ) )} hide{else} selected{/if}">
            {include uri='design:cjw_newsletter_edition_preview_archive.tpl' show_output_format_id=$output_format_id}
             <div class="break"></div>
        </div>
    {/foreach}

{* preview html, text *}
{else}

    {foreach $output_format_array as $output_format_id => $output_format_name}
        <div id="node-tab-preview_{$output_format_id}-content" class="tab-content{if $node_tab_index|ne( concat( 'preview_', $output_format_id ) )} hide{else} selected{/if}">
            {include uri='design:cjw_newsletter_edition_preview.tpl' show_output_format_id=$output_format_id}
             <div class="break"></div>
        </div>
    {/foreach}

{/if}


<div id="node-tab-preview-content" class="tab-content{if $node_tab_index|ne('preview')} hide{else} selected{/if}">
    {include uri='design:preview.tpl'}
<div class="break"></div>
</div>

{/if}

{* Details window *}
<div id="node-tab-details-content" class="tab-content{if $node_tab_index|ne('details')} hide{else} selected{/if}">
    {include uri='design:details.tpl'}
<div class="break"></div>
</div>

{* Translations window *}
<div id="node-tab-translations-content" class="tab-content{if $node_tab_index|ne('translations')} hide{else} selected{/if}">
    {include uri='design:translations.tpl'}
<div class="break"></div>
</div>

{* Locations window *}
<div id="node-tab-locations-content" class="tab-content{if $node_tab_index|ne('locations')} hide{else} selected{/if}">
    {include uri='design:locations.tpl'}
<div class="break"></div>
</div>

{* Relations window *}
<div id="node-tab-relations-content" class="tab-content{if $node_tab_index|ne('relations')} hide{else} selected{/if}">
    {include uri='design:relations.tpl'}
<div class="break"></div>
</div>
