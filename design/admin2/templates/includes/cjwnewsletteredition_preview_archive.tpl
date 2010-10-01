{*
    {include uri="design:includes/cjwnewsletteredition_preview.tpl" $newsletter_edition_attribute=$object show_iframes=true()}

newsletter edition vorschau in iframes oder nur links zu den full views
*}
{*if is_set( $show_iframes )|not() }
    {def $show_iframes = true()}
{/if*}

{if is_set( $iframe_height )|not() }
    {def $iframe_height = 200}
{/if}

{* siehe template weiter oben def $newsletter_edition_attribute_content = $newsletter_edition_attribute.content*}

{*def $list_attribute_content = $newsletter_edition_attribute_content.list_attribute_content *}

{def
     $edition_send_current = $newsletter_edition_attribute_content.edition_send_current
     $edition_send_id = $edition_send_current.id
     $archive_url = concat('/newsletter/archive/' , $edition_send_current.hash )}
{set $output_format_array = $edition_send_current.output_format_array}

{foreach $output_format_array as $output_format_id => $output_format_name}

{if or( is_set( $show_output_format_id )|not, and( is_set( $show_output_format_id ), $show_output_format_id|eq( $output_format_id ) ) )}

{def $src_url = concat('/newsletter/preview_archive/' , $edition_send_id, '/', $output_format_id)}
{*<img src={concat('newsletter/icons/crystal-newsletter/16x16/preview_', $output_format_id, '.png')|ezimage} title="{$output_format_name}" /> {'Preview'|i18n('cjw_newsletter/cjwnewsletteredition_preview')} "{$output_format_name|wash}"
[{'Skin'|i18n('cjw_newsletter/cjwnewsletteredition_preview')}: {$skin_name}] <a href={$src_url|ezurl} target="new_{$output_format_id}"><img src={'window_fullscreen.png'|ezimage} title="{'Fullscreen'|i18n('cjw_newsletter/cjwnewsletteredition_preview')}" /></a> *}
<p>{*{'Archived'|i18n('cjw_newsletter/cjwnewsletteredition_preview')} "{$output_format_name}" - *}<a href={$src_url|ezurl} target="new_{$output_format_id}"> [{'Fullscreen'|i18n('cjw_newsletter/cjwnewsletteredition_preview')}]</a></p>

{if $show_iframes}
    <iframe src={$src_url|ezurl} width="100%" height="{$iframe_height}" name="EDITION_PREVIEW_{$output_format_id}">
        <p>your browser does not support iframes!</p>
    </iframe>
{/if}
{undef $src_url}
{/if}
{/foreach}


<p><a href={$archive_url|ezurl} target="_blank">{'Archive view'|i18n('cjw_newsletter/cjwnewsletteredition_preview_archive')}</a></p>


{*$newsletter_edition_attribute.content|attribute(show,1)*}
{undef $show_iframes}