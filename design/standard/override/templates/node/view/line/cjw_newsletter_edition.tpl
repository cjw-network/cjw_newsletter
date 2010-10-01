{* admin linevie cjw_newsletter_edition *}
{def $node_name = $node.name
     $node_url = $node.url_alias
     $edition_attribute_content = $node.data_map.newsletter_edition.content
      }
{*(C {$node.contentobject_id} | S: {$edition_attribute_content.status}) *}<a href={$node.url_alias|ezurl} class="nodeicon" onclick="ezpopmenu_showTopLevel( event, 'SubitemsContextMenu', ez_createAArray( new Array( '%nodeID%', {$node.node_id}, '%objectID%', {$node.object.id}, '%languages%', {$:node.object.language_js_array} ) ) , '{$node.object.name|shorten(18)|wash(javascript)}', {$node.node_id} ); return false;">
{$node.class_identifier|class_icon( 'small', 'Click on the icon to get a context sensitive menu.'|i18n( 'design/admin/node/view/line' ) )}</a>&nbsp;
{if $node_url}<a href={$node_url|ezurl} title="{'Node ID: %node_id Visibility: %node_visibility'|i18n( 'design/admin/node/view/line',, hash( '%node_id', $node.node_id, '%node_visibility', $node.hidden_status_string ) )}">{/if}{$node_name|wash}{if $node_url}</a>{/if}

{undef $node_name $node_url $edition_attribute_content}
