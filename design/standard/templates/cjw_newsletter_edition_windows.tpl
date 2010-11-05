
{def $newsletter_edition_attribute = $node.data_map.newsletter_edition
     $newsletter_edition_attribute_content = $newsletter_edition_attribute.content
     $newsletter_list_attribute_content = $newsletter_edition_attribute.content.list_attribute_content
     $newsletter_edition_status = $newsletter_edition_attribute_content.status}

{* Newsletter status *}
{include uri='design:cjw_newsletter_edition_status.tpl'}

{* Newsletter testmail *}
{include uri='design:cjw_newsletter_edition_send_testmail.tpl'}

{if $newsletter_edition_status|ne('draft')}
    {* Newsletter testmail *}
    {include uri='design:cjw_newsletter_edition_send_statistic.tpl'}

    {* Newsletter preview iframes *}
    {include uri='design:cjw_newsletter_edition_preview_archive.tpl'}

{else}
    {* Newsletter preview iframes *}
    {include uri='design:cjw_newsletter_edition_preview.tpl'}
{/if}



{* Details window. *}
{if ezpreference( 'admin_navigation_details' )}
    {include uri='design:details.tpl'}
{/if}

{* Translations window. *}
{if ezpreference( 'admin_navigation_translations' )}
    {include uri='design:translations.tpl'}
{/if}

{* Locations window. *}
{if ezpreference( 'admin_navigation_locations' )}
    {include uri='design:locations.tpl'}
{/if}

{* Relations window. *}
{if or( ezpreference( 'admin_navigation_relations' ),
                  and( is_set( $view_parameters.show_relations ), eq( $view_parameters.show_relations, 1 ) ) )}
    {include uri='design:relations.tpl'}
{/if}

{* only show children if edition is in draft status *}
{*if $newsletter_edition_status|eq('draft')*}

    {* Children window.*}
    {if $node.object.content_class.is_container}
        {include uri='design:children.tpl'}
    {else}
        {include uri='design:no_children.tpl'}
    {/if}

{*/if*}