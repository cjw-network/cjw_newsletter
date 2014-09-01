{* 3_newsletter_system.tpl
    Zeigt ein system an mit alle listen
*}


    {let newsletter_root_node_id = $newsletter_system_node.node_id
         nl_list_class_filter_array = array( 'cjw_newsletter_list',
                                              'cjw_newsletter_list_virtual' )
         children       = fetch( 'content', 'list', hash('parent_node_id', $newsletter_root_node_id,
                                                        'class_filter_type', 'include',
                                                        'class_filter_array',
                                                         $nl_list_class_filter_array
                                                         ))
         numChildren    = fetch( 'content', 'list_count', hash('parent_node_id', $newsletter_root_node_id,
                                                        'class_filter_type', 'include',
                                                        'class_filter_array',
                                                        $nl_list_class_filter_array ))
         haveChildren   = $numChildren|gt(0)
         showToolTips   = ezini( 'TreeMenu', 'ToolTips' , 'contentstructuremenu.ini' )
         translation    = ezini( 'URLTranslator', 'Translation', 'site.ini' )
         toolTip        = ""
         visibility     = 'Visible'
         isRootNode     = false() }

        {default classIconsSize = ezini( 'TreeMenu', 'ClassIconsSize', 'contentstructuremenu.ini' )
                 last_item      = false() }

        {section show=is_set($class_icons_size)}
            {set classIconsSize=$class_icons_size}
        {/section}

        {section show=is_set($is_root_node)}
            {set isRootNode=$is_root_node}
        {/section}

        <li id="n0_{$newsletter_system_node.node_id}" {cond( $:last_item, 'class="lastli"', '' )}>

            {* Fold/Unfold/Empty: [-]/[+]/[ ] *}
                   <a class="openclose" href="#" title="{'Fold/Unfold'|i18n('cjw_newsletter/contentstructuremenu')}"
                      onclick="ezpopmenu_hideAll(); ezcst_onFoldClicked( this.parentNode ); return false;"></a>

            {* Label *}
                    {set toolTip = ''}

                {* Text *}

                {* icon *}
                {*<img src={'/share/icons/crystal-admin/16x16_indexed/filesystems/folder_txt.png'|ezroot}>*}
                {'cjw_newsletter_system'|class_icon( small )}

                {section show=or( eq($ui_context, 'browse')|not(), eq($:parentNode.object.is_container, true()))}
                    <a class="nodetext" href={concat( 'content/view/full/',$newsletter_system_node.node_id )|ezurl} title="{$:toolTip}"><span class="node-name-normal">{$newsletter_system_node.name}</span></a>
                {section-else}
                            <span class="node-name-normal">{$newsletter_system_node.name|wash}</span>
                {/section}

                {* Show children *}
                {section show=$:haveChildren}
                    <ul>
{*
                        <li><span class="openclose"></span> system link 1</li>
                        <li><span class="openclose"></span> system link 2</li>
*}
                        {section var=child loop=$:children}
                            {include name=SubMenu uri="design:contentstructuremenu/4_newsletter_list.tpl" newsletter_list_node=$:child csm_menu_item_click_action=$:csm_menu_item_click_action last_item=eq( $child.number, $:numChildren ) ui_context=$ui_context}
                        {/section}
                    </ul>
                {/section}
        </li>
        {/default}
    {/let}
