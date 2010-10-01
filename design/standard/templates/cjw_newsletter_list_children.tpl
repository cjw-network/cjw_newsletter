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

            {* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
                 <h2 class="context-title"><a href={$node.depth|gt(1)|choose('/'|ezurl,$node.parent.url_alias|ezurl )} title="{'Up one level.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children'  )}"><img src={'back-button-16x16.gif'|ezimage} alt="{'Up one level.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}" title="{'Up one level.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}" /></a>&nbsp;{'Sub items [%children_count]'|i18n( 'cjw_newsletter/cjw_newsletter_list_children',, hash( '%children_count', $children_count ) )}</h2>
                 {* DESIGN: Subline *}<div class="header-subline"></div>
            {* DESIGN: Header END *}</div></div></div></div></div></div>

            {* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

                 {* If there are children: show list and buttons that belong to the list. *}
                 {section show=$children}

                     {* Items per page and view mode selector. *}
                     <div class="context-toolbar">
                     <div class="block">
                         <div class="left">
                             <p>
                                {switch match=$number_of_items}
                                    {case match=25}
                                        <a href={'/user/preferences/set/admin_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}">10</a>
                                        <span class="current">25</span>
                                        <a href={'/user/preferences/set/admin_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}">50</a>

                                    {/case}

                                    {case match=50}
                                        <a href={'/user/preferences/set/admin_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}">10</a>
                                        <a href={'/user/preferences/set/admin_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}">25</a>
                                        <span class="current">50</span>
                                    {/case}

                                    {case}
                                        <span class="current">10</span>
                                        <a href={'/user/preferences/set/admin_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}">25</a>
                                        <a href={'/user/preferences/set/admin_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}">50</a>
                                    {/case}

                                 {/switch}
                             </p>
                         </div>
                         <div class="right">
                             <p>
                                 {switch match=ezpreference( 'admin_children_viewmode' )}
                                     {case match='thumbnail'}
                                         <a href={'/user/preferences/set/admin_children_viewmode/list'|ezurl} title="{'Display sub items using a simple list.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}">{'List'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}</a>
                                         <span class="current">{'Thumbnail'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}</span>
                                         <a href={'/user/preferences/set/admin_children_viewmode/detailed'|ezurl} title="{'Display sub items using a detailed list.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}">{'Detailed'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}</a>
                                     {/case}

                                     {case match='detailed'}
                                         <a href={'/user/preferences/set/admin_children_viewmode/list'|ezurl} title="{'Display sub items using a simple list.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}">{'List'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}</a>
                                         <a href={'/user/preferences/set/admin_children_viewmode/thumbnail'|ezurl} title="{'Display sub items as thumbnails.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}">{'Thumbnail'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}</a>
                                         <span class="current">{'Detailed'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}</span>
                                     {/case}

                                     {case}
                                         <span class="current">{'List'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}</span>
                                         <a href={'/user/preferences/set/admin_children_viewmode/thumbnail'|ezurl} title="{'Display sub items as thumbnails.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}">{'Thumbnail'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}</a>
                                         <a href={'/user/preferences/set/admin_children_viewmode/detailed'|ezurl} title="{'Display sub items using a detailed list.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}">{'Detailed'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}</a>
                                     {/case}
                                 {/switch}
                             </p>
                         </div>

                         <div class="break"></div>

                     </div>
                </div>

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
                             item_limit=$number_of_items}
                </div>

            {* DESIGN: Content END *}</div></div></div>


            {* Button bar for remove and update priorities buttons. *}
            <div class="controlbar">

                {* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

                    <div class="block">
                        {* Remove button *}
                        <div class="left">
                            {section show=$can_remove}
                                <input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}" title="{'Remove the selected items from the list above.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}" />
                            {section-else}
                                <input class="button-disabled" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}" title="{'You do not have permission to remove any of the items from the list above.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}" disabled="disabled" />
                            {/section}
                        </div>

                        <div class="right">
                            {* Update priorities button *}
                            {section show=and( eq( $node.sort_array[0][0], 'priority' ), $node.can_edit, $children_count )}
                                <input class="button" type="submit" name="UpdatePriorityButton" value="{'Update priorities'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}" title="{'Apply changes to the priorities of the items in the list above.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}" />
                            {section-else}
                                <input class="button-disabled" type="submit" name="UpdatePriorityButton" value="{'Update priorities'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}" title="{'You cannot update the priorities because you do not have permission to edit the current item or because a non-priority sorting method is used.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}" disabled="disabled" />
                            {/section}
                        </div>

                        <div class="break"></div>
                    </div>


                    {* The "Create new here" thing: *}
                    <div class="block">
                        {section show=$node.can_create}
                            <div class="left">
                                <input type="hidden" name="NodeID" value="{$node.node_id}" />

                                {let can_create_classes=fetch( content, can_instantiate_class_list, hash( group_id, array( ezini( 'ClassGroupIDs', 'Users', 'content.ini' ), ezini( 'ClassGroupIDs', 'Setup', 'content.ini' ) ), parent_node, $node, filter_type, exclude ) )}

                                {section show=$node.path_array|contains(ezini( 'NodeSettings', 'UserRootNode', 'content.ini' ) )}
                                      {set can_create_classes=fetch( content, can_instantiate_class_list, hash( group_id, ezini( 'ClassGroupIDs', 'Users', 'content.ini' ), parent_node, $node ) )}
                                {/section}

                                {def $can_create_languages=fetch( content, prioritized_languages )}

                                {if and(eq( $can_create_languages|count, 1 ), is_set( $can_create_languages[0] ) )}
                                    <select id="ClassID" name="ClassID" title="{'Use this menu to select the type of item you want to create then click the "Create here" button. The item will be created in the current location.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )|wash()}">
                                {else}
                                    <select id="ClassID" name="ClassID" onchange="updateLanguageSelector(this)" title="{'Use this menu to select the type of item you want to create then click the "Create here" button. The item will be created in the current location.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )|wash()}">
                                {/if}
                                    {section var=CanCreateClasses loop=$can_create_classes}
                                        {if $CanCreateClasses.item.can_instantiate_languages}
                                            <option value="{$CanCreateClasses.item.id}">{$CanCreateClasses.item.name|wash()}</option>
                                        {/if}
                                    {/section}
                                </select>

                                {if and(eq( $can_create_languages|count, 1 ), is_set( $can_create_languages[0] ) )}
                                    <input name="ContentLanguageCode" value="{$can_create_languages[0].locale}" type="hidden" />
                                {else}
                                    <select name="ContentLanguageCode" onchange="checkLanguageSelector(this)" title="{'Use this menu to select the language you want to use for the creation then click the "Create here" button. The item will be created in the current location.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )|wash()}">
                                        {foreach $can_create_languages as $tmp_language}
                                            <option value="{$tmp_language.locale|wash()}">{$tmp_language.name|wash()}</option>
                                        {/foreach}
                                   </select>
                                {/if}
                                {undef $can_create_languages}
                                {/let}

                                <input class="button" type="submit" name="NewButton" value="{'Create here'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}" title="{'Create a new item in the current location. Use the menu on the left to select the type of  item.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}" />
                                <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
                                <input type="hidden" name="ContentObjectID" value="{$node.contentobject_id}" />
                                <input type="hidden" name="ViewMode" value="full" />
                            </div>
                        {section-else}
                            <div class="left">
                                <select id="ClassID" name="ClassID" disabled="disabled">
                                    <option value="">{'Not available'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}</option>
                                </select>
                                <input class="button-disabled" type="submit" name="NewButton" value="{'Create here'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}" title="{'You do not have permission to create new items in the current location.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}" disabled="disabled" />
                            </div>
                        {/section}

                        {* Sorting *}
                        <div class="right">
                            <label>{'Sorting'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}:</label>

                            {def $sort_fields=hash( 6, 'Class identifier'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' ),
                                                    7, 'Class name'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' ),
                                                    5, 'Depth'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' ),
                                                    3, 'Modified'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' ),
                                                    9, 'Name'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' ),
                                                    8, 'Priority'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' ),
                                                    2, 'Published'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' ),
                                                    4, 'Section'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' ) )
                                 $title='You cannot set the sorting method for the current location because you do not have permission to edit the current item.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )
                                 $disabled=' disabled="disabled"' }

                            {section show=$node.can_edit}
                                {set title='Use these controls to set the sorting method for the sub items of the current location.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}
                                {set disabled=''}
                                <input type="hidden" name="ContentObjectID" value="{$node.contentobject_id}" />
                            {/section}

                            <select name="SortingField" title="{$title}"{$disabled}>
                                {section var=Sort loop=$sort_fields}
                                    <option value="{$Sort.key}" {section show=eq( $Sort.key, $node.sort_field )}selected="selected"{/section}>{$Sort.item}</option>
                                {/section}
                            </select>

                            <select name="SortingOrder" title="{$title}"{$disabled}>
                                <option value="0"{section show=eq($node.sort_order, 0)} selected="selected"{/section}>{'Descending'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}</option>
                                <option value="1"{section show=eq($node.sort_order, 1)} selected="selected"{/section}>{'Ascending'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}</option>
                            </select>

                            <input {section show=$disabled}class="button-disabled"{section-else}class="button"{/section} type="submit" name="SetSorting" value="{'Set'|i18n( 'cjw_newsletter/cjw_newsletter_list_children' )}" title="{$title}" {$disabled} />

                        </div>
                        <div class="break"></div>
                    </div>

                {* DESIGN: Control bar END *}</div></div></div></div></div></div>

            </div>
        </form>
    </div>
    <!-- Children END -->
{undef}
</div>
