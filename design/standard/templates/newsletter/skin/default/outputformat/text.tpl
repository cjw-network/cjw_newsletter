{set-block variable=$subject scope=root}{ezini('NewsletterMailSettings', 'EmailSubjectPrefix', 'cjw_newsletter.ini')} {$contentobject.name|wash}{/set-block}
{def $edition_data_map = $contentobject.data_map}

{if $edition_data_map.title.has_content}
<h1>{$edition_data_map.title.content|wash()}</h1>
{/if}

{*
    Text
*}
{if $edition_data_map.description.has_content}
     {attribute_view_gui attribute=$edition_data_map.description}
{/if}

<hr>
{'To unsubscribe from this newsletter please visit the following link'|i18n('cjw_newsletter/skin/default')}:
url:{'/newsletter/unsubscribe/#_hash_unsubscribe_#'|ezurl('no')}
<hr>
&copy; {currentdate()|datetime( 'custom', '%Y' )} www.CJW-Network.com

