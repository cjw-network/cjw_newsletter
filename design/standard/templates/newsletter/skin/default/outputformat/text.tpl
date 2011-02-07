{set-block variable=$subject scope=root}{ezini('NewsletterMailSettings', 'EmailSubjectPrefix', 'cjw_newsletter.ini')} {$contentobject.name|wash}{/set-block}
{def $edition_data_map = $contentobject.data_map}

{if $edition_data_map.title.has_content}
<h1>{$edition_data_map.title.content|wash()}</h1>
{/if}

{* Text *}
{if $edition_data_map.description.has_content}
     {attribute_view_gui attribute=$edition_data_map.description}
{/if}

{def $list_items = fetch('content', 'list', hash( 'parent_node_id', $contentobject.contentobject.main_node_id,
                                                  'sort_by', array( 'priority' , true() ),
                                                  'class_filter_type', 'include',
                                                  'class_filter_array', array( 'cjw_newsletter_article' ) ) )
}
{if $list_items|count|ne(0)}
{* show subarticles *}
{foreach $list_items as $attribute}

    {* title *}
    {if $attribute.data_map.title.has_content}
        <h2>{$attribute.data_map.title.content|wash}</h2>
    {/if}

    {* text *}
    {if $attribute.data_map.short_description.has_content}
        {attribute_view_gui attribute=$attribute.data_map.short_description}
    {/if}

{/foreach}
{/if}

<hr>
{'To unsubscribe from this newsletter please visit the following link'|i18n('cjw_newsletter/skin/default')}:
url:{'/newsletter/unsubscribe/#_hash_unsubscribe_#'|ezurl('no')}
<hr>
&copy; {currentdate()|datetime( 'custom', '%Y' )} www.CJW-Network.com

