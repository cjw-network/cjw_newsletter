<div class="content-view-children">
    <!-- Children START -->
    <div class="context-block">
        <form name="children" method="post" action={'content/action'|ezurl}>
            <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
            {* Generic children list for admin interface. *}
            {def $item_type=ezpreference( 'admin_list_limit' )
                 $number_of_items=min( $item_type, 3)|choose( 10, 10, 25, 50 )
                 $can_remove=false()
                 $can_edit=false()
                 $can_create=false()
                 $can_copy=false()
                 $status = ''}

            {if is_set( $view_parameters.status )}
                {set $status = $view_parameters.status}
            {/if}

            {def $children_count=fetch( 'content', 'list_count', hash( 'parent_node_id', $node.node_id,
                                                                  'objectname_filter', $view_parameters.namefilter,
                                                                  'extended_attribute_filter',
                                                                   hash( 'id', 'CjwNewsletterEditionFilter',
                                                                         'params', hash( 'status', $status )
                                                                   ) ) )
                 $children=fetch( 'content', 'list', hash( 'parent_node_id', $node.node_id,
                                                      'sort_by', $node.sort_array,
                                                      'limit', $number_of_items,
                                                      'offset', $view_parameters.offset,
                                                      'objectname_filter', $view_parameters.namefilter,
                                                      'extended_attribute_filter',
                                                       hash( 'id', 'CjwNewsletterEditionFilter',
                                                             'params', hash( 'status', $status ) )
                                                       ) ) }

            {* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

                 {* If there are children: show list and buttons that belong to the list. *}
                 {section show=$children}

                {* Copying operation is allowed if the user can create stuff under the current node. *}
                {set can_copy=$node.can_create}

                {* Check if the current user is allowed to *}
                {* edit or delete any of the children.     *}
                {section var=Children loop=$children}
                    {section show=$Children.item.can_remove}
                        {set can_remove=true()}
                    {/section}
                    {section show=$Children.item.can_edit}
                        {set can_edit=true()}
                    {/section}
                    {section show=$Children.item.can_create}
                        {set can_create=true()}
                    {/section}
                {/section}

                {* Display the actual list of nodes. *}
                {switch match=ezpreference( 'admin_children_viewmode' )}
                     {case match='thumbnail'}
                         {include uri='design:children_thumbnail.tpl'}
                     {/case}

                     {case match='detailed'}
                         {include uri='design:children_detailed.tpl'}
                     {/case}

                     {case}
                         {*include uri='design:children_list.tpl'*}
                         {include uri='design:cjw_newsletter_list_children_list.tpl'}
                     {/case}
                {/switch}

                {* Else: there are no children. *}
                {section-else}

                    <div class="block">
                        <p>{'The current item does not contain any sub items.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}</p>
                    </div>

                {/section}

                <div class="context-toolbar">
                    {include name=navigator
                             uri='design:navigator/alphabetical.tpl'
                             page_uri=$node.url_alias
                             item_count=$children_count
                             view_parameters=$view_parameters
                             node_id=$node.node_id
                             item_limit=10}
                </div>

            {* DESIGN: Content END *}</div></div></div>
        </form>
    </div>
    <!-- Children END -->
{undef}
</div>
