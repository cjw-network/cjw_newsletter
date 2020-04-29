<div class="content-view-children">

{* Generic children list for admin interface. *}
{def $item_type    = ezpreference( 'admin_list_limit' )
     $number_of_items = min( $item_type, 3)|choose( 10, 10, 25, 50 )
     $can_remove   = false()
     $can_move     = false()
     $can_edit     = false()
     $can_create   = false()
     $can_copy     = false()
     $current_path = first_set( $node.path_array[1], 1 )
     $admin_children_viewmode = ezpreference( 'admin_children_viewmode' )
     $children_count = fetch( content, list_count, hash( 'parent_node_id', $node.node_id,
                                                         'objectname_filter', $view_parameters.namefilter ) )
     $children    = array()
     $priority    = and( eq( $node.sort_array[0][0], 'priority' ), $node.can_edit, $children_count )
     $priority_dd = and( $priority, $admin_children_viewmode|ne( 'thumbnail' ), $view_parameters.offset|eq( 0 ) )}


<!-- Children START -->

<div class="context-block">
{*
<form name="children" method="post" action={'content/action'|ezurl}>
<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
*}

    {def $status = ''}

    {if is_set( $view_parameters.status )}
        {set $status = $view_parameters.status}
    {/if}

    {set $children_count=fetch( 'content', 'list_count', hash( 'parent_node_id', $node.node_id,
                                                               'class_filter_type', 'include',
                                                               'class_filter_array', array( 'cjw_newsletter_edition' ),
                                                                  'objectname_filter', $view_parameters.namefilter,
                                                                  'extended_attribute_filter',
                                                                   hash( 'id', 'CjwNewsletterEditionFilter',
                                                                         'params', hash( 'status', $status )
                                                                   ) ) )
                 $children=fetch( 'content', 'list', hash( 'parent_node_id', $node.node_id,
                                                      'class_filter_type', 'include',
                                                      'class_filter_array', array( 'cjw_newsletter_edition' ),
                                                      'sort_by', $node.sort_array,
                                                      'limit', $number_of_items,
                                                      'offset', $view_parameters.offset,
                                                      'sort_by', array( 'modified', false() ),
                                                      'objectname_filter', $view_parameters.namefilter,
                                                      'extended_attribute_filter',
                                                       hash( 'id', 'CjwNewsletterEditionFilter',
                                                             'params', hash( 'status', $status ) )
                                                       ) )}



{* DESIGN: Header START *}<div class="box-header">

    <div class="button-left">
    <h2 class="context-title"><a href={$node.depth|gt(1)|choose('/'|ezurl,$node.parent.url_alias|ezurl )} title="{'Up one level.'|i18n(  'design/admin/node/view/full'  )}"><img src={'up-16x16-grey.png'|ezimage} alt="{'Up one level.'|i18n( 'design/admin/node/view/full' )}" title="{'Up one level.'|i18n( 'design/admin/node/view/full' )}" /></a>&nbsp;{'Newsletter editions'|i18n( 'cjw_newsletter/cjw_newsletter_list' )} [{$children_count}]</h2>
    </div>

    <div class="button-right button-header">
    </div>

<div class="float-break"></div>

{* DESIGN: Header END *}</div>

{* DESIGN: Content START *}<div class="box-content">

{* If there are children: show list and buttons that belong to the list. *}
{*if $children_count*}

{* Items per page and view mode selector. *}
<div class="context-toolbar">
<div class="button-left">
    <p class="table-preferences">
        {switch match=$number_of_items}
        {case match=25}
        <a href={'/user/preferences/set/admin_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10</a>
        <span class="current">25</span>
        <a href={'/user/preferences/set/admin_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50</a>
        {/case}

        {case match=50}
        <a href={'/user/preferences/set/admin_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10</a>
        <a href={'/user/preferences/set/admin_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25</a>
        <span class="current">50</span>
        {/case}

        {case}
        <span class="current">10</span>
        <a href={'/user/preferences/set/admin_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25</a>
        <a href={'/user/preferences/set/admin_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50</a>
        {/case}
        {/switch}
</p>
</div>
<div class="button-right">
    {* newsletter list selection *}
    <p class="table-preferences">

    {if $status|eq('')}
        <span class="current">{'All'|i18n('cjw_newsletter/cjw_newsletter_list')}</span>
    {else}
        <a href={concat( $node.url_alias,'')|ezurl}>
            {'All'|i18n('cjw_newsletter/cjw_newsletter_list')}
        </a>
    {/if}

    {if $status|eq('draft')}
        <span class="current"><img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_draft.png'|ezdesign} width="12" height="12" /> {'Draft'|i18n('cjw_newsletter/contentstructuremenu')}</span>
    {else}
        <a href={concat( $node.url_alias, '/(status)/draft' )|ezurl}>
            <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_draft.png'|ezdesign} width="12" height="12" /> {'Draft'|i18n('cjw_newsletter/contentstructuremenu')}
        </a>
    {/if}

    {if $status|eq('process')}
        <span class="current"><img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_process.png'|ezdesign} width="12" height="12" /> {'Sending'|i18n('cjw_newsletter/contentstructuremenu')}</span>
    {else}
        <a href={concat( $node.url_alias, '/(status)/process' )|ezurl}>
            <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_process.png'|ezdesign} width="12" height="12" /> {'Sending'|i18n('cjw_newsletter/contentstructuremenu')}
        </a>
    {/if}

    {if $status|eq('archive')}
        <span class="current"><img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_archive.png'|ezdesign} width="12" height="12" /> {'Archived'|i18n('cjw_newsletter/contentstructuremenu')}</span>
    {else}
        <a href={concat( $node.url_alias, '/(status)/archive' )|ezurl}>
            <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_archive.png'|ezdesign} width="12" height="12" /> {'Archived'|i18n('cjw_newsletter/contentstructuremenu')}
        </a>
    {/if}

    {if $status|eq('abort')}
        <span class="current"><img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_abort.png'|ezdesign} width="12" height="12" /> {'Aborted'|i18n('cjw_newsletter/contentstructuremenu')}</span>
    {else}
        <a href={concat( $node.url_alias, '/(status)/abort' )|ezurl}>
            <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_abort.png'|ezdesign} width="12" height="12" /> {'Aborted'|i18n('cjw_newsletter/contentstructuremenu')}
        </a>
    {/if}
    </p>
</div>

{if $children_count}

<div class="float-break"></div>
</div>

    {* Copying operation is allowed if the user can create stuff under the current node. *}
    {set can_copy=$node.can_create}

    {* Check if the current user is allowed to *}
    {* edit or delete any of the children.     *}
    {section var=Children loop=$children}
        {if $Children.item.can_remove}
            {set can_remove=true()}
        {/if}
        {if $Children.item.can_move}
            {set $can_move=true()}
        {/if}
        {if $Children.item.can_edit}
            {set can_edit=true()}
        {/if}
        {if $Children.item.can_create}
            {set can_create=true()}
        {/if}
    {/section}


    {* Display the actual list of nodes. *}
    {include uri = 'design:includes/cjwnewsletteredition_statistic_list.tpl'
                 name = 'EditionList'
                 edition_node_list = $children
                 edition_node_list_count = $children_count
                 show_actions_colum = true()}
    
    <div class="context-toolbar subitems-context-toolbar">
        {include  name = 'Navigator'
                  uri = 'design:navigator/google.tpl'
                  page_uri = $node.url_alias
                  item_count = $children_count
                  view_parameters = $view_parameters
                  item_limit = $number_of_items}
    </div>

        {def $viewmode_newsletter=true()}

{* Else: there are no children. *}
{else}

<div class="block">
    <p>{'The current selection has no result.'|i18n( 'cjw_newsletter/cjw_newsletter_list' )}</p>
</div>

{/if}

<div class="context-toolbar subitems-context-toolbar">
{* Alphabetical navigation can be enabled with content.ini [AlphabeticalFilterSettings] ContentFilterList[]  *}
{include name=navigator
         uri='design:navigator/alphabetical.tpl'
         page_uri=$node.url_alias
         item_count=$children_count
         view_parameters=$view_parameters
         node_id=$node.node_id
         item_limit=$number_of_items}
</div>

{* DESIGN: Content END *}</div>

{* Button bar for remove and update priorities buttons. *}



{* hide subitems functions *}
{if and( $children_count|ne( 0 ), is_set( $viewmode_newsletter )|not )}
<div class="controlbar subitems-controlbar">{* DESIGN: Control bar START *}

    <div class='block'>
        {* Remove and move button *}
        <div class="button-left">
            {if $can_remove}
                <input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/node/view/full' )}" title="{'Remove the selected items from the list above.'|i18n( 'design/admin/node/view/full' )}" />
            {else}
                <input class="button-disabled" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permission to remove any of the items from the list above.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
            {/if}
            {if $can_move}
                <input class="button" type="submit" name="MoveButton" value="{'Move selected'|i18n( 'design/admin/node/view/full' )}" title="{'Move the selected items from the list above.'|i18n( 'design/admin/node/view/full' )}" />
            {else}
                <input class="button-disabled" type="submit" name="MoveButton" value="{'Move selected'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permission to move any of the items from the list above.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
            {/if}
        </div>
        <div class="button-right">
        {* Update priorities button *}
        {if $priority}
            <input id="ezasi-update-priority" class="button" type="submit" name="UpdatePriorityButton" value="{'Update priorities'|i18n( 'design/admin/node/view/full' )}" title="{'Apply changes to the priorities of the items in the list above.'|i18n( 'design/admin/node/view/full' )}" />
        {else}
            <input id="ezasi-update-priority" class="button-disabled" type="submit" name="UpdatePriorityButton" value="{'Update priorities'|i18n( 'design/admin/node/view/full' )}" title="{'You cannot update the priorities because you do not have permission to edit the current item or because a non-priority sorting method is used.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
        {/if}
        </div>
        <div class="float-break"></div>
    </div>
    {* DESIGN: Control bar END *}</div>

    <div class='block'>
        <fieldset>
            <legend>{'Create'|i18n( 'design/admin/node/view/full' )}</legend>
            {* The "Create new here" thing: *}
            {if and( $node.is_container,  $node.can_create)}
                <input type="hidden" name="NodeID" value="{$node.node_id}" />
                {if $node.path_array|contains( ezini( 'NodeSettings', 'MediaRootNode', 'content.ini' ) )}
                    {def $group_id = array( ezini( 'ClassGroupIDs', 'Users', 'content.ini' ),
                                            ezini( 'ClassGroupIDs', 'Setup', 'content.ini' ) )}
                {elseif $node.path_array|contains( ezini( 'NodeSettings', 'UserRootNode', 'content.ini' ) )}
                    {def $group_id = array( ezini( 'ClassGroupIDs', 'Setup', 'content.ini' ),
                                            ezini( 'ClassGroupIDs', 'Content', 'content.ini' ),
                                            ezini( 'ClassGroupIDs', 'Media', 'content.ini' ) )}
                {else}
                    {def $group_id = false()}
                {/if}

                {def $can_create_classes = fetch( 'content', 'can_instantiate_class_list', hash( 'parent_node', $node,
                                                                                                 'filter_type', 'exclude',
                                                                                                 'group_id', $group_id,
                                                                                                 'group_by_class_group', true() ) )}


                {def $can_create_languages = fetch( 'content', 'prioritized_languages' )
                     $content_locale = ezini( 'RegionalSettings', 'ContentObjectLocale' )}

                {if and( is_set( $can_create_languages[0] ), eq( $can_create_languages|count, 1 ) )}
                    <select id="ClassID" name="ClassID" title="{'Use this menu to select the type of item you want to create then click the "Create here" button. The item will be created in the current location.'|i18n( 'design/admin/node/view/full' )|wash()}">
                {else}
                    <select id="ClassID" name="ClassID" onchange="updateLanguageSelector(this)" title="{'Use this menu to select the type of item you want to create then click the "Create here" button. The item will be created in the current location.'|i18n( 'design/admin/node/view/full' )|wash()}">
                {/if}
                    {foreach $can_create_classes as $group}
                        <optgroup label="{$group.group_name}">
                        {foreach $group.items as $can_create_class}
                            {if $can_create_class.can_instantiate_languages}
                            <option value="{$can_create_class.id}">{$can_create_class.name|wash()}</option>
                            {/if}
                        {/foreach}
                        </optgroup>
                    {/foreach}
                </select>

                {if and( is_set( $can_create_languages[0] ), eq( $can_create_languages|count, 1 ) )}
                    <input name="ContentLanguageCode" value="{$can_create_languages[0].locale}" type="hidden" />
                {else}
                    <label for="ClassContentLanguageCode" class="inline">{'in'|i18n( 'design/admin/node/view/full' )}</label>
                    <select id="ClassContentLanguageCode" name="ContentLanguageCode" onchange="checkLanguageSelector(this)" title="{'Use this menu to select the language you want to use for the creation then click the "Create here" button. The item will be created in the current location.'|i18n( 'design/admin/node/view/full' )|wash()}">
                        {foreach $can_create_languages as $tmp_language}
                            <option value="{$tmp_language.locale|wash()}"{if $content_locale|eq( $tmp_language.locale )} selected="selected"{/if}>{$tmp_language.name|wash()}</option>
                        {/foreach}
                   </select>
                {/if}

                <input class="button" type="submit" name="NewButton" value="{'Here'|i18n( 'design/admin/node/view/full' )}" title="{'Create a new item in the current location. Use the menu on the left to select the type of  item.'|i18n( 'design/admin/node/view/full' )}" />
                <input type="hidden" name="ViewMode" value="full" />

                {if ne( $can_create_languages|count, 1 )}
                <script type="text/javascript">
                <!--
                    {literal}
                    function updateLanguageSelector( classSelector )
                    {
                        var languageSelector = classSelector.form.ContentLanguageCode;
                        if ( !languageSelector )
                        {
                            return;
                        }

                        var classID = classSelector.value, languages = languagesByClassID[classID], candidateIndex = -1;
                        for ( var index = 0, length = languageSelector.options.length; index < length; index++ )
                        {
                            var value = languageSelector.options[index].value, disabled = true;

                            for ( var indexj = 0, lengthj = languages.length; indexj < lengthj; indexj++ )
                            {
                                if ( languages[indexj] == value )
                                {
                                    disabled = false;
                                    break;
                                }
                            }

                            if ( !disabled && candidateIndex == -1 )
                            {
                                candidateIndex = index;
                            }

                            languageSelector.options[index].disabled = disabled;
                            if ( disabled )
                            {
                                languageSelector.options[index].style.color = '#888';
                                if ( languageSelector.options[index].text.substring( 0, 1 ) != '(' )
                                {
                                    languageSelector.options[index].text = '(' + languageSelector.options[index].text + ')';
                                }
                            }
                            else
                            {
                                languageSelector.options[index].style.color = '#000';
                                if ( languageSelector.options[index].text.substring( 0, 1 ) == '(' )
                                {
                                    languageSelector.options[index].text = languageSelector.options[index].text.substring( 1, languageSelector.options[index].text.length - 1 );
                                }
                            }
                        }

                        if ( languageSelector.options[languageSelector.selectedIndex].disabled )
                        {
                            window.languageSelectorIndex = candidateIndex;
                            languageSelector.selectedIndex = candidateIndex;
                        }
                    }

                    function checkLanguageSelector( languageSelector )
                    {
                        if ( languageSelector.options[languageSelector.selectedIndex].disabled )
                        {
                            languageSelector.selectedIndex = window.languageSelectorIndex;
                            return;
                        }
                        window.languageSelectorIndex = languageSelector.selectedIndex;
                    }

                    setTimeout( function() { updateLanguageSelector( document.getElementById( 'ClassID' ) ); }, 100 );

                    var languagesByClassID = {};
                    {/literal}

                    {foreach $can_create_classes as $group}
                        {foreach $group.items as $class}
                        languagesByClassID[{$class.id}] = [ {foreach $class.can_instantiate_languages as $tmp_language}'{$tmp_language}'{delimiter}, {/delimiter} {/foreach} ];
                        {/foreach}
                    {/foreach}
                // -->
                </script>
                {/if}
                {undef $can_create_languages $can_create_classes}
            {else}
                <select id="ClassID" name="ClassID" disabled="disabled">
                <option value="">{'Not available'|i18n( 'design/admin/node/view/full' )}</option>
                </select>
                <input class="button-disabled" type="submit" name="NewButton" value="{'Here'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permission to create new items in the current location.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
            {/if}
        </fieldset>
    </div>
    {if $children_count}
    <div class="block">
        <fieldset>
            <legend>{'Published order'|i18n( 'design/admin/node/view/full' )}</legend>

            {let sort_fields=hash( 6, 'Class identifier'|i18n( 'design/admin/node/view/full' ),
                                   7, 'Class name'|i18n( 'design/admin/node/view/full' ),
                                   5, 'Depth'|i18n( 'design/admin/node/view/full' ),
                                   3, 'Modified'|i18n( 'design/admin/node/view/full' ),
                                   9, 'Name'|i18n( 'design/admin/node/view/full' ),
                                   8, 'Priority'|i18n( 'design/admin/node/view/full' ),
                                   2, 'Published'|i18n( 'design/admin/node/view/full' ),
                                   4, 'Section'|i18n( 'design/admin/node/view/full' ) )
                title='You cannot set the sorting method for the current location because you do not have permission to edit the current item.'|i18n( 'design/admin/node/view/full' )
                disabled=' disabled="disabled"' }

            {if $node.can_edit}
                {set title='Use these controls to set the sorting method for the sub items of the current location.'|i18n( 'design/admin/node/view/full' )}
                {set disabled=''}
                <input type="hidden" name="ContentObjectID" value="{$node.contentobject_id}" />
            {/if}

            <select id="ezasi-sort-field" name="SortingField" title="{$title}"{$disabled}>
            {section var=Sort loop=$sort_fields}
                <option value="{$Sort.key}" {if eq( $Sort.key, $node.sort_field )}selected="selected"{/if}>{$Sort.item}</option>
            {/section}
            </select>

            <select id="ezasi-sort-order" name="SortingOrder" title="{$title}"{$disabled}>
                <option value="0"{if eq($node.sort_order, 0)} selected="selected"{/if}>{'Descending'|i18n( 'design/admin/node/view/full' )}</option>
                <option value="1"{if eq($node.sort_order, 1)} selected="selected"{/if}>{'Ascending'|i18n( 'design/admin/node/view/full' )}</option>
            </select>

            <input  id="ezasi-sort-set" {if $disabled}class="button-disabled"{else}class="button"{/if} type="submit" name="SetSorting" value="{'Set'|i18n( 'design/admin/node/view/full' )}" title="{$title}" {$disabled} />

            {/let}
        </fieldset>
    </div>
    {/if}
</div>
{/if}

{*</form>*}


{* hide subitems functions *}
{if and( $children_count|ne( 0 ), is_set( $viewmode_newsletter )|not )}

{* Load drag and drop code if access rights are ok (but not depending on node sort as pagelayout cache-block does not include that in key) *}
{if $node.can_edit}
{ezscript_require( array( 'ezjsc::yui3', 'ezjsc::yui3io', 'ezajaxsubitems_sortdd.js' ) )}
{/if}

{* Execute drag and drop code if sortField=priority and access rights are ok *}
{if $priority_dd}
<script type="text/javascript">
eZAjaxSubitemsSortDD.init();
</script>
{/if}


{* Highlight "SetSorting" button on change *}
{literal}
<script type="text/javascript">
jQuery('#ezasi-sort-field, #ezasi-sort-order').each( function(){
        jQuery( this ).attr( 'initial', this.value );
} ).change(function(){
        var t = $(this), o = $(this.id === 'ezasi-sort-field' ? '#ezasi-sort-order' : '#ezasi-sort-field'), s = $('#ezasi-sort-set');
        // signal in gui if user needs to save this or not
        if ( t.val() === t.attr('initial') && o.val() === o.attr('initial') )
                s.removeClass('defaultbutton').addClass('button');
        else
                s.removeClass('button').addClass('defaultbutton');
});
</script>
{/literal}


{/if}

<!-- Children END -->

{undef $item_type $number_of_items $can_remove $can_move $can_edit $can_create $can_copy $current_path $admin_children_viewmode $children_count $children}
</div>
