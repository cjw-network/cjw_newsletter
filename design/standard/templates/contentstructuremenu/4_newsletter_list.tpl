{def $newsletter_list_node_id = $:newsletter_list_node.node_id}

    {let children       = array()
         numChildren    = array()
         haveChildren   = $numChildren|gt(0)
         showToolTips   = ezini( 'TreeMenu', 'ToolTips'         , 'contentstructuremenu.ini' )
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
            {set isRootNode=false}
        {/section}

        <li id="nt{$newsletter_list_node_id}" {section show=$:last_item} class="lastli"{/section}>

            {* Fold/Unfold/Empty: [-]/[+]/[ ] *}
                   <a class="openclose" href="#" title="{'Fold/Unfold'|i18n('cjw_newsletter/contentstructuremenu')}"
                      onclick="ezpopmenu_hideAll(); ezcst_onFoldClicked( this.parentNode ); return false;"></a>

            {* Label *}
                    {set toolTip = ''}


                {* icon *}
                {*<img src={'share/icons/crystal-admin/16x16_indexed/actions/view_tree.png'|ezroot} />*}
                {'cjw_newsletter_list'|class_icon( small )}

                {* Text *}
                {section show=or( eq($ui_context, 'browse')|not(), eq($:parentNode.object.is_container, true()))}
                    <a class="nodetext" href={concat( 'content/view/full/', $newsletter_list_node_id )|ezurl} title="{$:toolTip}"><span class="node-name-normal">{$:newsletter_list_node.name|wash}</span></a>
                {section-else}
                    <span class="node-name-normal">{$:newsletter_list_node.name|wash}</span>
                {/section}

                {* Show children *}

            <ul>
                {* subscription_list *}
                {def $subcription_user_statistic = $:newsletter_list_node.data_map.newsletter_list.content.user_count_statistic}
                <li id="n{$newsletter_list_node_id}_subscription_list">
                    <span class="openclose"></span>
                    <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_user.png'|ezdesign} />
                    <a class="nodetext" href={concat('newsletter/subscription_list/',$newsletter_list_node_id)|ezurl}>
                        <span class="node-name-normal">{'Subscriptions'|i18n('cjw_newsletter/contentstructuremenu')} (<b>{$subcription_user_statistic.approved}</b>/{$subcription_user_statistic.all})</span>
                    </a>
                </li>
                {undef $subcripion_user_statistic}
                {* draft *}
                {def $editionObjectListDraftCount = fetch('content','list_count',
                                                                hash('parent_node_id', $newsletter_list_node_id,
                                                                        'extended_attribute_filter',
                                                                        hash( 'id', 'CjwNewsletterEditionFilter',
                                                                              'params', hash( 'status', 'draft' ) )
                                                                                 ) )}
                <li id="n{$newsletter_list_node_id}_draft">
                    <span class="openclose"></span>
                    <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_draft.png'|ezdesign} />
                    <a class="nodetext" href={concat('content/view/full/',$newsletter_list_node_id, '/(status)/draft')|ezurl}>
                        <span class="node-name-normal">{'Draft'|i18n('cjw_newsletter/contentstructuremenu')} ({$editionObjectListDraftCount})</span>
                    </a>
                </li>
                {* process *}
                {def $editionObjectListProcessCount = fetch('content','list_count',
                                                                hash('parent_node_id', $newsletter_list_node_id,
                                                                        'extended_attribute_filter',
                                                                        hash( 'id', 'CjwNewsletterEditionFilter',
                                                                              'params', hash( 'status', 'process' ) )
                                                                                 ) )}
                <li id="n{$newsletter_list_node_id}_process">
                    <span class="openclose"></span>
                    <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_process.png'|ezdesign} />
                    <a class="nodetext" href={concat('content/view/full/',$newsletter_list_node_id, '/(status)/process')|ezurl}>
                        <span class="node-name-normal">{'Sending'|i18n('cjw_newsletter/contentstructuremenu')} ({$editionObjectListProcessCount})</span>
                    </a>
                </li>
                {* Archive *}
                {def $editionObjectListArchiveCount = fetch('content','list_count',
                                                                hash('parent_node_id', $newsletter_list_node_id,
                                                                        'extended_attribute_filter',
                                                                        hash( 'id', 'CjwNewsletterEditionFilter',
                                                                              'params', hash( 'status', 'archive' ) )
                                                                                 ) )}
                <li id="n{$newsletter_list_node_id}_archive">
                    <span class="openclose"></span>
                    <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_archive.png'|ezdesign} />
                    <a class="nodetext" href={concat('content/view/full/',$newsletter_list_node_id, '/(status)/archive')|ezurl}>
                        <span class="node-name-normal">{'Archived'|i18n('cjw_newsletter/contentstructuremenu')} ({$editionObjectListArchiveCount})</span>
                    </a>
                </li>
                {* Abort *}
                {def $editionObjectListAbortCount = fetch('content','list_count',
                                                                hash('parent_node_id', $newsletter_list_node_id,
                                                                        'extended_attribute_filter',
                                                                        hash( 'id', 'CjwNewsletterEditionFilter',
                                                                              'params', hash( 'status', 'abort' ) )
                                                                                 ) )}
                <li class="lastli" id="n{$newsletter_list_node_id}_abort">
                    <span class="openclose"></span>
                    <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_abort.png'|ezdesign} />
                    <a class="nodetext" href={concat('content/view/full/', $newsletter_list_node_id, '/(status)/abort')|ezurl}>
                        <span class="node-name-normal">{'Aborted'|i18n('cjw_newsletter/contentstructuremenu')} ({$editionObjectListAbortCount})</span>
                    </a>
                </li>

                {undef}
            </ul>

        </li>
        {/default}
    {/let}
