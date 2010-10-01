{* Window controls *}
{def $node_url_alias      = $node.url_alias
     $preview_enabled     = ezpreference( 'admin_navigation_content' )
     $default_tab         = cond( $preview_enabled, 'preview', 'details' )
     $node_tab_index      = first_set( $view_parameters.tab, $default_tab )
     $available_languages = fetch( 'content', 'prioritized_languages' )
     $translations        = $node.object.languages
     $translations_count  = $translations|count
     $states              = $node.object.allowed_assign_state_list
     $states_count        = $states|count
     $related_objects_count = fetch( 'content', 'related_objects_count', hash( 'object_id', $node.object.id , 'all_relations', true() ) )
     $reverse_related_objects_count = fetch( 'content', 'reverse_related_objects_count', hash( 'object_id', $node.object.id , 'all_relations', true() ) )
     $valid_tabs = array( $default_tab, 'details', 'translations', 'locations', 'relations', 'states' )
}
{if $valid_tabs|contains( $node_tab_index )|not()}
    {set $node_tab_index = $default_tab}
{/if}




<ul class="tabs">
    {* Content preview *}
    {if $preview_enabled}

    {foreach $output_format_array as $output_format_id => $output_format_name}
        <li id="node-tab-preview_{$output_format_id}" class="{if $node_tab_index|eq( concat( 'preview_', $output_format_id ) )} selected{/if}">
            <a href={concat( $node_url_alias, '/(tab)/preview_', $output_format_id )|ezurl} title="">{if $output_format_id|eq(0)}HTML / Text{else}{$output_format_name|wash}{/if}</a>
        </li>
    {/foreach}

  {*  <li id="node-tab-preview_html" class="first{if $node_tab_index|eq('preview_html')} selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/preview_html' )|ezurl} title="">HTML / Text</a>
    </li>
    <li id="node-tab-preview_text" class="{if $node_tab_index|eq('preview_text')}selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/preview_text' )|ezurl} title="">Text</a>
    </li> *}
    <li id="node-tab-preview" class="{if $node_tab_index|eq('preview')}selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/preview' )|ezurl} title="{'Show preview of content.'|i18n( 'design/admin/node/view/full' )}">{'Preview'|i18n( 'design/admin/node/view/full' )}</a>
    </li>
    {else}

    {foreach $output_format_array as $output_format_id => $output_format_name}
        <li id="node-tab-preview-disabled" class="{if $node_tab_index|eq('preview')} selected{/if}">
        <span class="disabled" title="{'Tab is disabled, enable on dashboard.'|i18n( 'design/admin/node/view/full' )}">{if $output_format_id|eq(0)}HTML / Text{else}{$output_format_name|wash}{/if}</span>
        </li>
    {/foreach}

    <li id="node-tab-preview-disabled" class="first">
        <span class="disabled" title="{'Tab is disabled, enable on dashboard.'|i18n( 'design/admin/node/view/full' )}">{'Preview'|i18n( 'design/admin/node/view/full' )}</span>
    </li>
    {/if}

    {* Details *}
    <li id="node-tab-details" class="middle{if $node_tab_index|eq('details')}  selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/details' )|ezurl} title="{'Show details.'|i18n( 'design/admin/node/view/full' )}">{'Details'|i18n( 'design/admin/node/view/full' )}</a>
    </li>

    {* Translations *}
    {if fetch( 'content', 'translation_list' )|count|gt( 1 )}
    {if $available_languages|count|gt( 1 ) }
    <li id="node-tab-translations" class="middle{if $node_tab_index|eq('translations')}  selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/translations' )|ezurl} title="{'Show available translations.'|i18n( 'design/admin/node/view/full' )}">{'Translations (%count)'|i18n( 'design/admin/node/view/full',,hash('%count', $translations_count ) )}</a>
    </li>
    {/if}
    {/if}

    {* Locations *}
    <li id="node-tab-locations" class="middle{if $node_tab_index|eq('locations')}  selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/locations' )|ezurl} title="{'Show location overview.'|i18n( 'design/admin/node/view/full' )}">{'Locations (%count)'|i18n( 'design/admin/node/view/full',, hash( '%count', $node.object.assigned_nodes|count ) )}</a>
    </li>

    {* Relations *}
    <li id="node-tab-relations" class="middle{if $node_tab_index|eq('relations')}  selected{/if}">
        <a href={concat( $node_url_alias, '/(tab)/relations' )|ezurl} title="{'Show object relation overview.'|i18n( 'design/admin/node/view/full' )}">{'Relations (%count)'|i18n( 'design/admin/node/view/full',, hash( '%count', sum( $related_objects_count, $reverse_related_objects_count ) ) )}</a>
    </li>
</ul>
<div class="float-break"></div>

{include uri='design:cjw_newsletter_edition_windows.tpl'}

{ezscript_require( 'node_tabs.js' )}
{undef}
